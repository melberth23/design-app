<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandAssets;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Lib\SystemHelper;

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
        $userid = Auth::id();
        $brands = Brand::where('user_id', $userid)->paginate(10);
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
            $numberofbrands = Brand::where('user_id', $userid)->count();
            $helper = new SystemHelper();
            $payments = Payments::where('user_id', $userid)->first();
            $planrule = $helper->getPlanRules($payments->plan);
            $allowedbrand = $planrule['brand'];

            $allowed = false;
            if($allowedbrand > 0) {
                if($allowedbrand > $numberofbrands) {
                    $allowed = true;
                }
            } else {
                $allowed = true;
            }

            if($allowed) {
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

                        if($check) { 
                            $randomfilename = $helper->generateRandomString(15);
                            $picturepath = $randomfilename .'.'. $extension;
                            $picture->move(public_path('storage/pictures'), $picturepath);

                            $assets = BrandAssets::create([
                                'filename' => $picturepath,
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
                        $check = in_array($extension, $allowedFontsExtension);

                        if($check) {
                            $fontpath = $filename;
                            $font->move(public_path('storage/fonts'), $fontpath);

                            $assets = BrandAssets::create([
                                'filename' => $fontpath,
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
                        $check = in_array($extension, $allowedInspirationsExtension);

                        if($check) {
                            $randomfilename = $helper->generateRandomString(15);
                            $inspirationpath = $randomfilename .'.'. $extension;
                            $inspiration->move(public_path('storage/inspirations'), $inspirationpath);

                            $assets = BrandAssets::create([
                                'filename' => $inspirationpath,
                                'brand_id' => $brand->id,
                                'type' => 'inspiration'
                            ]);
                        }
                    }
                }

                // Commit And Redirected To Listing
                DB::commit();
                return redirect()->route('brand.index')->with('success','Brand Created Successfully.');
            } else {
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'Your are not allowed to add more than '. $allowedbrand .' brand profile.');
            }
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Update Status Of Brand
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
            return redirect()->route('brand.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            Brand::whereId($brand_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('brand.index')->with('success','Brand Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Edit Brand
     * @param Integer $brand
     * @return Collection $brand
     */
    public function edit(Brand $brand)
    {
        return view('brands.edit')->with([
            'brand'  => $brand
        ]);
    }

    /**
     * Update Brand
     * @param Request $request, Brand $brand
     * @return View brands
     */
    public function update(Request $request, Brand $brand)
    {
        // Validations
        $request->validate([
            'name'    => 'required',
            'target_audience' =>  'required',
            'description'     => 'required',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $brand_updated = Brand::whereId($brand->id)->update([
                'name'    => $request->name,
                'target_audience'    => $request->target_audience,
                'description'     => $request->description,
                'status'        => $request->status,
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('brand.index')->with('success','Brand Updated Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete Brand
     * @param Brand $brand
     * @return Index brand
     */
    public function delete(Brand $brand)
    {
        DB::beginTransaction();
        try {
            // Delete Blog
            Brand::whereId($brand->id)->delete();

            DB::commit();
            return redirect()->route('brand.index')->with('success', 'Brand Deleted Successfully!.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
