<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandAssets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List brands 
     * @param Nill
     * @return Array $brands
     */
    public function index()
    {
        $brands = Brand::paginate(10);
        return view('brands.index', ['brands' => $brands]);
    }

    /**
     * Create Brand 
     * @param Nill
     * @return Array $brands
     */
    public function create()
    {
        return view('brands.add');
    }

    /**
     * Store Brand
     * @param Request $request
     * @return View brands
     */
    public function store(Request $request)
    {
        $userid = $request->user()->id;

        // Validations
        $request->validate([
            'name'    => 'required',
            'target_audience'    => 'required',
            'description'     => 'required',
            'status'       =>  'required|numeric|in:0,1',
            'pictures.*' => 'mimes:jpg,png',
            'fonts.*' => 'mimes:ttf',
            'inspirations.*' => 'mimes:jpg,png,mp4,gif'
        ]);

        DB::beginTransaction();
        try {
            Brand::where('user_id', $userid)->count();

            // Store Data
            $brand = Brand::create([
                'name'    => $request->name,
                'target_audience'    => $request->target_audience,
                'description'     => $request->description,
                'user_id'        => $userid,
                'status'        => $request->status,
            ]);

            // Check upload pictures
            if($request->hasFile('pictures')) {
                $allowedPicturesExtension = ['jpg','png'];
                $pictures = $request->file('pictures');
                foreach($pictures as $picture) {
                    $filename = $picture->getClientOriginalName();
                    $extension = $picture->getClientOriginalExtension();
                    $check = in_array($extension, $allowedPicturesExtension);
                    $picturepath = $filename .'-'. time() .'.'. $extension;

                    if($check) {
                        Storage::disk('local')->put($picturepath, $picture);
                        $assets = BrandAssets::create([
                            'filename' => $filename,
                            'brand_id' => $brand->id,
                            'type' => 'picture'
                        ]);
                    }
                }
            }

            // Check upload fonts
            if($request->hasFile('fonts')) {
                $allowedFontsExtension = ['ttf'];
                $fonts = $request->file('fonts');
                foreach($fonts as $font) {
                    $filename = $font->getClientOriginalName();
                    $extension = $font->getClientOriginalExtension();
                    $fontpath = $font->store('public/fonts');
                    $check = in_array($extension, $allowedFontsExtension);

                    if($check) {
                        $assets = BrandAssets::create([
                            'filename' => $filename,
                            'brand_id' => $brand->id,
                            'type' => 'font'
                        ]);
                    }
                }
            }

            // Check upload inspirations
            if($request->hasFile('inspirations')) {
                $allowedInspirationsExtension = ['jpg','png','mp4','gif'];
                $inspirations = $request->file('inspirations');
                foreach($inspirations as $inspiration) {
                    $filename = $inspiration->getClientOriginalName();
                    $extension = $inspiration->getClientOriginalExtension();
                    $inspirationpath = $inspiration->store('public/inspirations');
                    $check = in_array($extension, $allowedInspirationsExtension);

                    if($check) {
                        $assets = BrandAssets::create([
                            'filename' => $filename,
                            'brand_id' => $brand->id,
                            'type' => 'inspiration'
                        ]);
                    }
                }
            }

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('brand.index')->with('success','Brand Created Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Update Status Of Blog
     * @param Integer $status
     * @return List Page With Success
     */
    public function updateStatus($brand_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'brand_id'   => $brand_id,
            'status'    => $status
        ], [
            'brand_id'   =>  'required|exists:brands,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('brands.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            User::whereId($brand_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('brands.index')->with('success','Blog Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Edit Blog
     * @param Integer $blog
     * @return Collection $blog
     */
    public function edit(Blog $blog)
    {
        $roles = Role::all();
        return view('brands.edit')->with([
            'blog'  => $blog
        ]);
    }

    /**
     * Update Blog
     * @param Request $request, Blog $blog
     * @return View brands
     */
    public function update(Request $request, Blog $blog)
    {
        // Validations
        $request->validate([
            'name'    => 'required',
            'description'     => 'required',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $blog_updated = Blog::whereId($blog->id)->update([
                'name'    => $request->name,
                'description'     => $request->description,
                'status'        => $request->status
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('brands.index')->with('success','Blog Updated Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete Blog
     * @param Blog $blog
     * @return Index brands
     */
    public function delete(Blog $blog)
    {
        DB::beginTransaction();
        try {
            // Delete Blog
            Blog::whereId($blog->id)->delete();

            DB::commit();
            return redirect()->route('brands.index')->with('success', 'Blog Deleted Successfully!.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
