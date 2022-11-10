<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandAssets;
use App\Models\Payments;
use App\Models\TempFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DigitalMail;
use App\Lib\SystemHelper;
use File;

class BrandController extends Controller
{
    public $helper;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->helper = new SystemHelper();
    }

    /**
     * List brands 
     * @param Nill
     * @return Array $brands
     */
    public function index($type=false, $sort=false)
    {
        $userid = Auth::id();

        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            // Get lists of brands
            $brands = Brand::where('user_id', $userid);
            if(!empty($type) && $type == 'date') {
                $brands->orderByRaw('created_at '. $sort);
            }
            if(!empty($type) && $type == 'name') {
                $brands->orderBy('name', $sort);
            }
            $brands = $brands->paginate(10);

            // Check limit
            $limit = $this->helper->userActionRules($userid, 'brand');

            return view('brands.index', ['brands' => $brands, 'limit' => $limit['allowed']]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Drafts List brands 
     * @param Nill
     * @return Array $brands
     */
    public function drafts($type=false, $sort=false)
    {
        $userid = Auth::id();

        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            // Get lists of brands
            $brands = Brand::where('user_id', $userid)->where('status', 0);
            $brands = $brands->paginate(10);

            // Check limit
            $limit = $this->helper->userActionRules($userid, 'brand');

            return view('brands.drafts', ['brands' => $brands, 'limit' => $limit['allowed']]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Archived List brands 
     * @param Nill
     * @return Array $brands
     */
    public function archived($type=false, $sort=false)
    {
        $userid = Auth::id();

        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            // Get lists of brands
            $brands = Brand::where('user_id', $userid)->where('status', 2);
            $brands = $brands->paginate(10);

            // Check limit
            $limit = $this->helper->userActionRules($userid, 'brand');

            return view('brands.archived', ['brands' => $brands, 'limit' => $limit['allowed']]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * View single brand
     * @param brand
     * @return Single brand
     */
    public function view(Brand $brand)
    {
        $userid = Auth::id();
        /* Get all assets by type */
        // Get Logo
        $logos = BrandAssets::where('brand_id', $brand->id)->where('type', 'logo')->get();
        $secondary_logos = BrandAssets::where('brand_id', $brand->id)->where('type', 'logo_second')->get();

        // Get Colors
        $colors = BrandAssets::where('brand_id', $brand->id)->where('type', 'color')->get();
        $secondary_colors = BrandAssets::where('brand_id', $brand->id)->where('type', 'color_second')->get();

        // Get Fonts
        $fonts = BrandAssets::where('brand_id', $brand->id)->where('type', 'font')->get();
        $secondary_fonts = BrandAssets::where('brand_id', $brand->id)->where('type', 'font_second')->get();

        // Get images
        $images = BrandAssets::where('brand_id', $brand->id)->where('type', 'picture')->get();

        // Get Guidelines
        $guidelines = BrandAssets::where('brand_id', $brand->id)->where('type', 'guideline')->get();

        // Get Templates
        $templates = BrandAssets::where('brand_id', $brand->id)->where('type', 'template')->get();

        // Get Inspirations
        $inspirations = BrandAssets::where('brand_id', $brand->id)->where('type', 'inspiration')->get();

        // get next and previous
        $previous = Brand::where('user_id', $userid)->where('id', '<', $brand->id)->max('id');
        $next = Brand::where('user_id', $userid)->where('id', '>', $brand->id)->min('id');

        $backurl = route('brand.index');
        if(auth()->user()->hasRole('Admin')) {
            $backurl = route('adminrequest.index', ['customer_id' => $brand->user_id]);
        }
        if(auth()->user()->hasRole('Designer')) {
            $backurl = route('designer.index', ['customer_id' => $brand->user_id]);
        }

        return view('brands.view')->with([
            'brand'  => $brand,
            'backurl'  => $backurl,
            'previous'  => $previous,
            'next'  => $next,
            'logos' => $logos,
            'secondary_logos' => $secondary_logos,
            'colors' => $colors,
            'secondary_colors' => $secondary_colors,
            'fonts' => $fonts,
            'secondary_fonts' => $secondary_fonts,
            'images' => $images,
            'guidelines' => $guidelines,
            'templates' => $templates,
            'inspirations' => $inspirations
        ]);
    }

    /**
     * Create Brand 
     * @param Nill
     * @return Array $brands
     */
    public function create()
    {
        $userid = Auth::id();

        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            return view('brands.add');
        } else {
            return redirect()->route('dashboard');
        }
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
        $messages = [
          'colors.*.distinct' => 'Please make colors unique.',
          'colors_second.*.distinct' => 'Please make colors unique.'
        ];
        $validate = Validator::make($request->all(), [
            'name'    => 'required',
            'target_audience'    => 'required',
            'description'     => 'required',
            'industry'     => 'required',
            'services_provider'     => 'required',
            'website'     => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'status'       =>  'required|numeric|in:0,1',
            'colors'       =>  'nullable|distinct',
            'colors.*'       =>  'nullable|distinct',
            'colors_second.*' =>  'nullable|distinct'
        ], $messages);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->back()->withInput()->withErrors($validate);
        }

        DB::beginTransaction();
        try {
            $allowed = $this->helper->userActionRules($userid, 'brand');
            if($allowed['allowed']) {
                // Store Data
                $brand = Brand::create([
                    'name'    => $request->name,
                    'target_audience'    => $request->target_audience,
                    'description'     => $request->description,
                    'industry'     => $request->industry,
                    'services_provider'     => $request->services_provider,
                    'website'     => $request->website,
                    'other_inspirations'     => $request->other_inspirations,
                    'facebook'     => $request->facebook,
                    'linkedin'     => $request->linkedin,
                    'instagram'     => $request->instagram,
                    'twitter'     => $request->twitter,
                    'youtube'     => $request->youtube,
                    'tiktok'     => $request->tiktok,
                    'user_id'        => $userid,
                    'status'        => $request->status,
                ]);

                // Check upload logos
                $templogos = TempFile::where('module', 'logo')->where('code', $request->tempfile_code)->get();
                if(!empty($templogos)) {
                    foreach($templogos as $templogo) {
                        $assets = BrandAssets::create([
                            'filename' => $templogo->file,
                            'brand_id' => $brand->id,
                            'type' => 'logo',
                            'file_type' => $templogo->file_type
                        ]);

                        // remove tempfile
                        TempFile::whereId($templogo->id)->delete();
                    }
                }

                // Check upload secondary logos
                $tempsecondlogos = TempFile::where('module', 'logo_second')->where('code', $request->tempfile_code)->get();
                if(!empty($tempsecondlogos)) {
                    foreach($tempsecondlogos as $tempsecondlogo) {
                        $assets = BrandAssets::create([
                            'filename' => $tempsecondlogo->file,
                            'brand_id' => $brand->id,
                            'type' => 'logo_second',
                            'file_type' => $tempsecondlogo->file_type
                        ]);

                        // remove tempfile
                        TempFile::whereId($tempsecondlogo->id)->delete();
                    }
                }

                // Check upload pictures
                $temppictures = TempFile::where('module', 'picture')->where('code', $request->tempfile_code)->get();
                if(!empty($temppictures)) {
                    foreach($temppictures as $temppicture) {
                        $assets = BrandAssets::create([
                            'filename' => $temppicture->file,
                            'brand_id' => $brand->id,
                            'type' => 'picture',
                            'file_type' => $temppicture->file_type
                        ]);

                        // remove tempfile
                        TempFile::whereId($temppicture->id)->delete();
                    }
                }

                // Check upload colors
                if(!empty($request->colors)) {
                    foreach($request->colors as $color) {
                        if(!empty($color)) {
                            $assets = BrandAssets::create([
                                'filename' => $color,
                                'brand_id' => $brand->id,
                                'type' => 'color',
                                'file_type' => ''
                            ]);
                        }
                    }
                }

                // Check upload secondary colors
                if(!empty($request->colors_second)) {
                    foreach($request->colors_second as $color_second) {
                        if($color_second) {
                            $assets = BrandAssets::create([
                                'filename' => $color_second,
                                'brand_id' => $brand->id,
                                'type' => 'color_second',
                                'file_type' => ''
                            ]);
                        }
                    }
                }

                // Check upload fonts
                $tempfonts = TempFile::where('module', 'font')->where('code', $request->tempfile_code)->get();
                if(!empty($tempfonts)) {
                    foreach($tempfonts as $tempfont) {
                        $assets = BrandAssets::create([
                            'filename' => $tempfont->file,
                            'brand_id' => $brand->id,
                            'type' => 'font',
                            'file_type' => $tempfont->file_type
                        ]);

                        // remove tempfile
                        TempFile::whereId($tempfont->id)->delete();
                    }
                }

                // Check upload secondary fonts
                $tempsecondfonts = TempFile::where('module', 'font_second')->where('code', $request->tempfile_code)->get();
                if(!empty($tempsecondfonts)) {
                    foreach($tempsecondfonts as $tempsecondfont) {
                        $assets = BrandAssets::create([
                            'filename' => $tempsecondfont->file,
                            'brand_id' => $brand->id,
                            'type' => 'font_second',
                            'file_type' => $tempsecondfont->file_type
                        ]);

                        // remove tempfile
                        TempFile::whereId($tempsecondfont->id)->delete();
                    }
                }

                // Check upload inspirations
                $tempinspirations = TempFile::where('module', 'inspiration')->where('code', $request->tempfile_code)->get();
                if(!empty($tempinspirations)) {
                    foreach($tempinspirations as $tempinspiration) {
                        $assets = BrandAssets::create([
                            'filename' => $tempinspiration->file,
                            'brand_id' => $brand->id,
                            'type' => 'inspiration',
                            'file_type' => $tempinspiration->file_type
                        ]);

                        // remove tempfile
                        TempFile::whereId($tempinspiration->id)->delete();
                    }
                }

                // Check upload templates
                $temptemplates = TempFile::where('module', 'template')->where('code', $request->tempfile_code)->get();
                if(!empty($temptemplates)) {
                    foreach($temptemplates as $temptemplate) {
                        $assets = BrandAssets::create([
                            'filename' => $temptemplate->file,
                            'brand_id' => $brand->id,
                            'type' => 'template',
                            'file_type' => $temptemplate->file_type
                        ]);

                        // remove tempfile
                        TempFile::whereId($temptemplate->id)->delete();
                    }
                }

                // Check upload guidelines
                $tempguidelines = TempFile::where('module', 'guideline')->where('code', $request->tempfile_code)->get();
                if(!empty($tempguidelines)) {
                    foreach($tempguidelines as $tempguideline) {
                        $assets = BrandAssets::create([
                            'filename' => $tempguideline->file,
                            'brand_id' => $brand->id,
                            'type' => 'guideline',
                            'file_type' => $tempguideline->file_type
                        ]);

                        // remove tempfile
                        TempFile::whereId($tempguideline->id)->delete();
                    }
                }

                // Send admin notification
                $admins = User::where('role_id', 1)->get();
                if(!empty($admins)) {
                    foreach($admins as $admin) {
                        // Send email for notification
                        $details = array(
                            'subject' => 'Brand Profile notification',
                            'fromemail' => 'hello@designsowl.com',
                            'fromname' => 'DesignsOwl',
                            'heading' => 'Hi there,',
                            'message' => 'New brand profile '. $request->name .' added.',
                            'sub_message' => 'Please login using your login information to check. Thank you!',
                            'template' => 'status'
                        );
                        Mail::to($admin->email)->send(new DigitalMail($details));
                    }
                }

                // Commit And Redirected To Listing
                DB::commit();
                return redirect()->route('brand.index')->with('success','Brand Created Successfully.');
            } else {
                DB::rollBack();
                return redirect()->back()->withInput()->with('limiterror', 'Account limit: Your are not allowed to add more than '. $allowed['allowedbrand'] .' brand profile.');
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
            'status'    =>  'required|in:0,1,2',
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
    public function edit($section='all', Brand $brand)
    {
        /* Get all assets by type */
        // Get Logo
        $logos = BrandAssets::where('brand_id', $brand->id)->where('type', 'logo')->get();
        $secondary_logos = BrandAssets::where('brand_id', $brand->id)->where('type', 'logo_second')->get();

        // Get Colors
        $colors = BrandAssets::where('brand_id', $brand->id)->where('type', 'color')->get();
        $secondary_colors = BrandAssets::where('brand_id', $brand->id)->where('type', 'color_second')->get();

        // Get Fonts
        $fonts = BrandAssets::where('brand_id', $brand->id)->where('type', 'font')->get();
        $secondary_fonts = BrandAssets::where('brand_id', $brand->id)->where('type', 'font_second')->get();

        // Get images
        $images = BrandAssets::where('brand_id', $brand->id)->where('type', 'picture')->get();

        // Get Guidelines
        $guidelines = BrandAssets::where('brand_id', $brand->id)->where('type', 'guideline')->get();

        // Get Templates
        $templates = BrandAssets::where('brand_id', $brand->id)->where('type', 'template')->get();

        // Get Inspirations
        $inspirations = BrandAssets::where('brand_id', $brand->id)->where('type', 'inspiration')->get();

        return view('brands.edit')->with([
            'section'  => $section,
            'brand'  => $brand,
            'logos' => $logos,
            'secondary_logos' => $secondary_logos,
            'colors' => $colors,
            'secondary_colors' => $secondary_colors,
            'fonts' => $fonts,
            'secondary_fonts' => $secondary_fonts,
            'images' => $images,
            'guidelines' => $guidelines,
            'templates' => $templates,
            'inspirations' => $inspirations
        ]);
    }

    /**
     * Update Brand
     * @param Request $request, Brand $brand
     * @return View brands
     */
    public function update(Request $request, Brand $brand)
    {
        $userid = $request->user()->id;

        // Validations
        $messages = [
          'colors.*.distinct' => 'Please make colors unique.',
          'colors_second.*.distinct' => 'Please make colors unique.'
        ];
        $validate = Validator::make($request->all(), [
            'name'    => 'required',
            'target_audience'    => 'required',
            'description'     => 'required',
            'industry'     => 'required',
            'services_provider'     => 'required',
            'website'     => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'status'       =>  'required|numeric|in:0,1',
            'colors'       =>  'nullable|distinct',
            'colors.*'       =>  'nullable|distinct',
            'colors_second.*' =>  'nullable|distinct'
        ], $messages);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->back()->withInput()->withErrors($validate);
        }

        DB::beginTransaction();
        try {

            // Store Data
            $brand_updated = Brand::whereId($brand->id)->update([
                'name'    => $request->name,
                'target_audience'    => $request->target_audience,
                'description'     => $request->description,
                'industry'     => $request->industry,
                'services_provider'     => $request->services_provider,
                'website'     => $request->website,
                'other_inspirations'     => $request->other_inspirations,
                'facebook'     => $request->facebook,
                'linkedin'     => $request->linkedin,
                'instagram'     => $request->instagram,
                'twitter'     => $request->twitter,
                'youtube'     => $request->youtube,
                'tiktok'     => $request->tiktok,
                'status'        => $request->status
            ]);

            // Check upload logos
            $templogos = TempFile::where('module', 'logo')->where('code', $request->tempfile_code)->get();
            if(!empty($templogos)) {
                foreach($templogos as $templogo) {
                    $assets = BrandAssets::create([
                        'filename' => $templogo->file,
                        'brand_id' => $brand->id,
                        'type' => 'logo',
                        'file_type' => $templogo->file_type
                    ]);

                    // remove tempfile
                    TempFile::whereId($templogo->id)->delete();
                }
            }

            // Check upload secondary logos
            $tempsecondlogos = TempFile::where('module', 'logo_second')->where('code', $request->tempfile_code)->get();
            if(!empty($tempsecondlogos)) {
                foreach($tempsecondlogos as $tempsecondlogo) {
                    $assets = BrandAssets::create([
                        'filename' => $tempsecondlogo->file,
                        'brand_id' => $brand->id,
                        'type' => 'logo_second',
                        'file_type' => $tempsecondlogo->file_type
                    ]);

                    // remove tempfile
                    TempFile::whereId($tempsecondlogo->id)->delete();
                }
            }

            // Check upload pictures
            $temppictures = TempFile::where('module', 'picture')->where('code', $request->tempfile_code)->get();
            if(!empty($temppictures)) {
                foreach($temppictures as $temppicture) {
                    $assets = BrandAssets::create([
                        'filename' => $temppicture->file,
                        'brand_id' => $brand->id,
                        'type' => 'picture',
                        'file_type' => $temppicture->file_type
                    ]);

                    // remove tempfile
                    TempFile::whereId($temppicture->id)->delete();
                }
            }

            // Check upload colors
            if(!empty($request->colors) && !empty($request->colors[0])) {
                foreach($request->colors as $color) {
                    if(!empty($color)) {
                        $assets = BrandAssets::create([
                            'filename' => $color,
                            'brand_id' => $brand->id,
                            'type' => 'color',
                            'file_type' => ''
                        ]);
                    }
                }
            }

            // Check upload secondary colors
            if(!empty($request->colors_second) && !empty($request->colors_second[0])) {
                foreach($request->colors_second as $color_second) {
                    if(!empty($color_second)) {
                        $assets = BrandAssets::create([
                            'filename' => $color_second,
                            'brand_id' => $brand->id,
                            'type' => 'color_second',
                            'file_type' => ''
                        ]);
                    }
                }
            }

            // Check upload fonts
            $tempfonts = TempFile::where('module', 'font')->where('code', $request->tempfile_code)->get();
            if(!empty($tempfonts)) {
                foreach($tempfonts as $tempfont) {
                    $assets = BrandAssets::create([
                        'filename' => $tempfont->file,
                        'brand_id' => $brand->id,
                        'type' => 'font',
                        'file_type' => $tempfont->file_type
                    ]);

                    // remove tempfile
                    TempFile::whereId($tempfont->id)->delete();
                }
            }

            // Check upload secondary fonts
            $tempsecondfonts = TempFile::where('module', 'font_second')->where('code', $request->tempfile_code)->get();
            if(!empty($tempsecondfonts)) {
                foreach($tempsecondfonts as $tempsecondfont) {
                    $assets = BrandAssets::create([
                        'filename' => $tempsecondfont->file,
                        'brand_id' => $brand->id,
                        'type' => 'font_second',
                        'file_type' => $tempsecondfont->file_type
                    ]);

                    // remove tempfile
                    TempFile::whereId($tempsecondfont->id)->delete();
                }
            }

            // Check upload inspirations
            $tempinspirations = TempFile::where('module', 'inspiration')->where('code', $request->tempfile_code)->get();
            if(!empty($tempinspirations)) {
                foreach($tempinspirations as $tempinspiration) {
                    $assets = BrandAssets::create([
                        'filename' => $tempinspiration->file,
                        'brand_id' => $brand->id,
                        'type' => 'inspiration',
                        'file_type' => $tempinspiration->file_type
                    ]);

                    // remove tempfile
                    TempFile::whereId($tempinspiration->id)->delete();
                }
            }

            // Check upload templates
            $temptemplates = TempFile::where('module', 'template')->where('code', $request->tempfile_code)->get();
            if(!empty($temptemplates)) {
                foreach($temptemplates as $temptemplate) {
                    $assets = BrandAssets::create([
                        'filename' => $temptemplate->file,
                        'brand_id' => $brand->id,
                        'type' => 'template',
                        'file_type' => $temptemplate->file_type
                    ]);

                    // remove tempfile
                    TempFile::whereId($temptemplate->id)->delete();
                }
            }

            // Check upload guidelines
            $tempguidelines = TempFile::where('module', 'guideline')->where('code', $request->tempfile_code)->get();
            if(!empty($tempguidelines)) {
                foreach($tempguidelines as $tempguideline) {
                    $assets = BrandAssets::create([
                        'filename' => $tempguideline->file,
                        'brand_id' => $brand->id,
                        'type' => 'guideline',
                        'file_type' => $tempguideline->file_type
                    ]);

                    // remove tempfile
                    TempFile::whereId($tempguideline->id)->delete();
                }
            }

            // Commit And Redirected To Listing
            DB::commit();
            $redirecturl = redirect()->route('brand.index')->with('success','Brand Updated Successfully.');
            if(auth()->user()->hasRole('Admin')) {
                $redirecturl = redirect()->route('subscribers.view', ['subscriber' => $brand->user_id])->with('success','Brand Updated Successfully.');
            }
            return $redirecturl;

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

    /**
     * Delete Brand Asset
     * @param Brand $brand
     * @return Index brand
     */
    public function deleteAsset(Request $request)
    {
        DB::beginTransaction();
        try {

            $asset = BrandAssets::whereId($request->asset)->first();
            if($asset->type != 'color' || $asset->type != 'color_second') {
                $brand = Brand::whereId($asset->brand_id)->first();
                $directory = $this->helper->media_directories($asset->type);
                $filepath = public_path('storage/'. $directory .'/'. $brand->user_id .'/'. $asset->filename);

                // Delete file
                if(file_exists($filepath)) {
                    File::delete($filepath);
                }
                if(Storage::disk('s3')->exists($asset->filename)) {
                    Storage::disk('s3')->delete($asset->filename);
                }
            }

            BrandAssets::whereId($asset->id)->delete();

            DB::commit();
            return response()->json(['success' => 'Brand Asset Deleted Successfully!.']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
