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
        $paymentinfo = Payments::where('user_id', $userid)->first();
        if($paymentinfo->status == 'active') {
            // Get lists of brands
            $brands = Brand::where('user_id', $userid);
            if(!empty($type) && $type == 'date') {
                $brands->orderByRaw('created_at '. $sort);
            }
            if(!empty($type) && $type == 'name') {
                $brands->orderBy('name', $sort);
            }
            $brands = $brands->paginate(10);

            return view('brands.index', ['brands' => $brands]);
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
        $paymentinfo = Payments::where('user_id', $userid)->first();
        if($paymentinfo->status == 'active') {
            // Get lists of brands
            $brands = Brand::where('user_id', $userid)->where('status', 0);
            $brands = $brands->paginate(10);

            return view('brands.drafts', ['brands' => $brands]);
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
        $paymentinfo = Payments::where('user_id', $userid)->first();
        if($paymentinfo->status == 'active') {
            // Get lists of brands
            $brands = Brand::where('user_id', $userid)->where('status', 2);
            $brands = $brands->paginate(10);

            return view('brands.archived', ['brands' => $brands]);
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
        if($brand->user_id == $userid) {
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

            return view('brands.view')->with([
                'brand'  => $brand,
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
        } else {
            return redirect()->route('dashboard');
        }
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
        $paymentinfo = Payments::where('user_id', $userid)->first();
        if($paymentinfo->status == 'active') {
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
        $request->validate([
            'name'    => 'required',
            'target_audience'    => 'required',
            'description'     => 'required',
            'industry'     => 'required',
            'services_provider'     => 'required',
            'website'     => 'required',
            'status'       =>  'required|numeric|in:0,1',
            'logos.*' => 'mimes:jpg,png',
            'logos_second.*' => 'mimes:jpg,png',
            'pictures.*' => 'mimes:jpg,png',
            'templates.*' => 'mimes:psd,doc,docx,pdf,ai',
            'guidelines.*' => 'mimes:pdf',
            'inspirations.*' => 'mimes:jpg,png,gif'
        ]);

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
                if($request->hasFile('logos')) {
                    $requestlogospath = public_path('storage/logos') .'/'. $userid;
                    if(!File::isDirectory($requestlogospath)){
                        // Create Path
                        File::makeDirectory($requestlogospath, 0777, true, true);
                    }

                    $allowedLogosExtension = ['jpg','png','svg'];
                    $logos = $request->file('logos');
                    foreach($logos as $logo) {
                        $filename = $logo->getClientOriginalName();
                        $extension = $logo->getClientOriginalExtension();
                        $check = in_array($extension, $allowedLogosExtension);

                        if($check) { 
                            $randomfilename = $this->helper->generateRandomString(15);
                            $logopath = $randomfilename .'.'. $extension;
                            $logo->move($requestlogospath, $logopath);

                            $assets = BrandAssets::create([
                                'filename' => $logopath,
                                'brand_id' => $brand->id,
                                'type' => 'logo',
                                'file_type' => $extension
                            ]);
                        }
                    }
                }

                // Check upload secondary logos
                if($request->hasFile('logos_second')) {
                    $requestlogosSecondpath = public_path('storage/logos') .'/'. $userid;
                    if(!File::isDirectory($requestlogosSecondpath)){
                        // Create Path
                        File::makeDirectory($requestlogosSecondpath, 0777, true, true);
                    }

                    $allowedLogosSecondExtension = ['jpg','png','svg'];
                    $logos_second = $request->file('logos_second');
                    foreach($logos_second as $logo_second) {
                        $filename = $logo_second->getClientOriginalName();
                        $extension = $logo_second->getClientOriginalExtension();
                        $check = in_array($extension, $allowedLogosSecondExtension);

                        if($check) { 
                            $randomfilename = $this->helper->generateRandomString(15);
                            $logo_secondpath = $randomfilename .'.'. $extension;
                            $logo_second->move($requestlogosSecondpath, $logo_secondpath);

                            $assets = BrandAssets::create([
                                'filename' => $logo_secondpath,
                                'brand_id' => $brand->id,
                                'type' => 'logo_second',
                                'file_type' => $extension
                            ]);
                        }
                    }
                }

                // Check upload pictures
                if($request->hasFile('pictures')) {
                    $requestpicturespath = public_path('storage/pictures') .'/'. $userid;
                    if(!File::isDirectory($requestpicturespath)){
                        // Create Path
                        File::makeDirectory($requestpicturespath, 0777, true, true);
                    }

                    $allowedPicturesExtension = ['jpg','png'];
                    $pictures = $request->file('pictures');
                    foreach($pictures as $picture) {
                        $filename = $picture->getClientOriginalName();
                        $extension = $picture->getClientOriginalExtension();
                        $check = in_array($extension, $allowedPicturesExtension);

                        if($check) { 
                            $randomfilename = $this->helper->generateRandomString(15);
                            $picturepath = $randomfilename .'.'. $extension;
                            $picture->move($requestpicturespath, $picturepath);

                            $assets = BrandAssets::create([
                                'filename' => $picturepath,
                                'brand_id' => $brand->id,
                                'type' => 'picture',
                                'file_type' => $extension
                            ]);
                        }
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
                if($request->hasFile('fonts')) {
                    $requestfontspath = public_path('storage/fonts') .'/'. $userid;
                    if(!File::isDirectory($requestfontspath)){
                        // Create Path
                        File::makeDirectory($requestfontspath, 0777, true, true);
                    }

                    $allowedFontsExtension = ['ttf', 'eot', 'woff'];
                    $fonts = $request->file('fonts');
                    foreach($fonts as $font) {
                        $filename = $font->getClientOriginalName();
                        $extension = $font->getClientOriginalExtension();
                        $check = in_array($extension, $allowedFontsExtension);

                        if($check) {
                            $fontpath = $filename;
                            $font->move($requestfontspath, $fontpath);

                            $assets = BrandAssets::create([
                                'filename' => $fontpath,
                                'brand_id' => $brand->id,
                                'type' => 'font',
                                'file_type' => $extension
                            ]);
                        }
                    }
                }

                // Check upload secondary fonts
                if($request->hasFile('fonts_second')) {
                    $requestfontsSecondarypath = public_path('storage/fonts') .'/'. $userid;
                    if(!File::isDirectory($requestfontsSecondarypath)){
                        // Create Path
                        File::makeDirectory($requestfontsSecondarypath, 0777, true, true);
                    }

                    $allowedFontsSecondaryExtension = ['ttf', 'eot', 'woff'];
                    $fonts_second = $request->file('fonts_second');
                    foreach($fonts_second as $font_second) {
                        $filename = $font_second->getClientOriginalName();
                        $extension = $font_second->getClientOriginalExtension();
                        $check = in_array($extension, $allowedFontsSecondaryExtension);

                        if($check) {
                            $fontpath = $filename;
                            $font_second->move($requestfontsSecondarypath, $fontpath);

                            $assets = BrandAssets::create([
                                'filename' => $fontpath,
                                'brand_id' => $brand->id,
                                'type' => 'font_second',
                                'file_type' => $extension
                            ]);
                        }
                    }
                }

                // Check upload inspirations
                if($request->hasFile('inspirations')) {
                    $requestinspirationspath = public_path('storage/inspirations') .'/'. $userid;
                    if(!File::isDirectory($requestinspirationspath)){
                        // Create Path
                        File::makeDirectory($requestinspirationspath, 0777, true, true);
                    }

                    $allowedInspirationsExtension = ['jpg','png','mp4','gif'];
                    $inspirations = $request->file('inspirations');
                    foreach($inspirations as $inspiration) {
                        $filename = $inspiration->getClientOriginalName();
                        $extension = $inspiration->getClientOriginalExtension();
                        $check = in_array($extension, $allowedInspirationsExtension);

                        if($check) {
                            $randomfilename = $this->helper->generateRandomString(15);
                            $inspirationpath = $randomfilename .'.'. $extension;
                            $inspiration->move($requestinspirationspath, $inspirationpath);

                            $assets = BrandAssets::create([
                                'filename' => $inspirationpath,
                                'brand_id' => $brand->id,
                                'type' => 'inspiration',
                                'file_type' => $extension
                            ]);
                        }
                    }
                }

                // Check upload templates
                if($request->hasFile('templates')) {
                    $requesttemplatespath = public_path('storage/templates') .'/'. $userid;
                    if(!File::isDirectory($requesttemplatespath)){
                        // Create Path
                        File::makeDirectory($requesttemplatespath, 0777, true, true);
                    }

                    $allowedTemplatesExtension = ['psd', 'ai', 'doc', 'docx', 'pdf', 'png', 'jpg'];
                    $templates = $request->file('templates');
                    foreach($templates as $template) {
                        $filename = $template->getClientOriginalName();
                        $extension = $template->getClientOriginalExtension();
                        $check = in_array($extension, $allowedTemplatesExtension);

                        if($check) {
                            $randomfilename = $this->helper->generateRandomString(15);
                            $templatepath = $randomfilename .'.'. $extension;
                            $template->move($requesttemplatespath, $templatepath);

                            $assets = BrandAssets::create([
                                'filename' => $templatepath,
                                'brand_id' => $brand->id,
                                'type' => 'template',
                                'file_type' => $extension
                            ]);
                        }
                    }
                }

                // Check upload guidelines
                if($request->hasFile('guidelines')) {
                    $requestguidelinespath = public_path('storage/guidelines') .'/'. $userid;
                    if(!File::isDirectory($requestguidelinespath)){
                        // Create Path
                        File::makeDirectory($requestguidelinespath, 0777, true, true);
                    }

                    $allowedGuidelinesExtension = ['psd', 'ai', 'doc', 'pdf', 'png', 'jpg', 'ppt'];
                    $guidelines = $request->file('guidelines');
                    foreach($guidelines as $guideline) {
                        $filename = $guideline->getClientOriginalName();
                        $extension = $guideline->getClientOriginalExtension();
                        $check = in_array($extension, $allowedGuidelinesExtension);

                        if($check) {
                            $randomfilename = $this->helper->generateRandomString(15);
                            $guidelinepath = $randomfilename .'.'. $extension;
                            $guideline->move($requestguidelinespath, $guidelinepath);

                            $assets = BrandAssets::create([
                                'filename' => $guidelinepath,
                                'brand_id' => $brand->id,
                                'type' => 'guideline',
                                'file_type' => $extension
                            ]);
                        }
                    }
                }

                // Commit And Redirected To Listing
                DB::commit();
                return redirect()->route('brand.index')->with('success','Brand Created Successfully.');
            } else {
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'Account limit: Your are not allowed to add more than '. $allowed['allowedbrand'] .' brand profile.');
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
        $request->validate([
            'name'    => 'required',
            'target_audience'    => 'required',
            'description'     => 'required',
            'industry'     => 'required',
            'services_provider'     => 'required',
            'website'     => 'required',
            'status'       =>  'required|numeric|in:0,1',
            'logos.*' => 'mimes:jpg,png',
            'logos_second.*' => 'mimes:jpg,png',
            'pictures.*' => 'mimes:jpg,png',
            'templates.*' => 'mimes:psd,doc,docx,pdf,ai',
            'guidelines.*' => 'mimes:pdf',
            'inspirations.*' => 'mimes:jpg,png,gif'
        ]);

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
            if($request->hasFile('logos')) {
                $requestlogospath = public_path('storage/logos') .'/'. $userid;
                if(!File::isDirectory($requestlogospath)){
                    // Create Path
                    File::makeDirectory($requestlogospath, 0777, true, true);
                }

                $allowedLogosExtension = ['jpg','png'];
                $logos = $request->file('logos');
                foreach($logos as $logo) {
                    $filename = $logo->getClientOriginalName();
                    $extension = $logo->getClientOriginalExtension();
                    $check = in_array($extension, $allowedLogosExtension);

                    if($check) { 
                        $randomfilename = $this->helper->generateRandomString(15);
                        $logopath = $randomfilename .'.'. $extension;
                        $logo->move($requestlogospath, $logopath);

                        $assets = BrandAssets::create([
                            'filename' => $logopath,
                            'brand_id' => $brand->id,
                            'type' => 'logo',
                            'file_type' => $extension
                        ]);
                    }
                }
            }

            // Check upload secondary logos
            if($request->hasFile('logos_second')) {
                $requestlogosSecondpath = public_path('storage/logos') .'/'. $userid;
                if(!File::isDirectory($requestlogosSecondpath)){
                    // Create Path
                    File::makeDirectory($requestlogosSecondpath, 0777, true, true);
                }

                $allowedLogosSecondExtension = ['jpg','png'];
                $logos_second = $request->file('logos_second');
                foreach($logos_second as $logo_second) {
                    $filename = $logo_second->getClientOriginalName();
                    $extension = $logo_second->getClientOriginalExtension();
                    $check = in_array($extension, $allowedLogosSecondExtension);

                    if($check) { 
                        $randomfilename = $this->helper->generateRandomString(15);
                        $logo_secondpath = $randomfilename .'.'. $extension;
                        $logo_second->move($requestlogosSecondpath, $logo_secondpath);

                        $assets = BrandAssets::create([
                            'filename' => $logo_secondpath,
                            'brand_id' => $brand->id,
                            'type' => 'logo_second',
                            'file_type' => $extension
                        ]);
                    }
                }
            }

            // Check upload pictures
            if($request->hasFile('pictures')) {
                $requestpicturespath = public_path('storage/pictures') .'/'. $userid;
                if(!File::isDirectory($requestpicturespath)){
                    // Create Path
                    File::makeDirectory($requestpicturespath, 0777, true, true);
                }

                $allowedPicturesExtension = ['jpg','png'];
                $pictures = $request->file('pictures');
                foreach($pictures as $picture) {
                    $filename = $picture->getClientOriginalName();
                    $extension = $picture->getClientOriginalExtension();
                    $check = in_array($extension, $allowedPicturesExtension);

                    if($check) { 
                        $randomfilename = $this->helper->generateRandomString(15);
                        $picturepath = $randomfilename .'.'. $extension;
                        $picture->move($requestpicturespath, $picturepath);

                        $assets = BrandAssets::create([
                            'filename' => $picturepath,
                            'brand_id' => $brand->id,
                            'type' => 'picture',
                            'file_type' => $extension
                        ]);
                    }
                }
            }

            // Check upload colors
            if(!empty($request->colors) && !empty($request->colors[0])) {
                BrandAssets::where('brand_id', $brand->id)->where('type', 'color')->delete();

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
                BrandAssets::where('brand_id', $brand->id)->where('type', 'color_second')->delete();

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
            if($request->hasFile('fonts')) {
                $requestfontspath = public_path('storage/fonts') .'/'. $userid;
                if(!File::isDirectory($requestfontspath)){
                    // Create Path
                    File::makeDirectory($requestfontspath, 0777, true, true);
                }

                $allowedFontsExtension = ['ttf', 'eot', 'woff'];
                $fonts = $request->file('fonts');
                foreach($fonts as $font) {
                    $filename = $font->getClientOriginalName();
                    $extension = $font->getClientOriginalExtension();
                    $check = in_array($extension, $allowedFontsExtension);

                    if($check) {
                        $fontpath = $filename;
                        $font->move($requestfontspath, $fontpath);

                        $assets = BrandAssets::create([
                            'filename' => $fontpath,
                            'brand_id' => $brand->id,
                            'type' => 'font',
                            'file_type' => $extension
                        ]);
                    }
                }
            }

            // Check upload secondary fonts
            if($request->hasFile('fonts_second')) {
                $requestfontsSecondarypath = public_path('storage/fonts') .'/'. $userid;
                if(!File::isDirectory($requestfontsSecondarypath)){
                    // Create Path
                    File::makeDirectory($requestfontsSecondarypath, 0777, true, true);
                }

                $allowedFontsSecondaryExtension = ['ttf', 'eot', 'woff'];
                $fonts_second = $request->file('fonts_second');
                foreach($fonts_second as $font_second) {
                    $filename = $font_second->getClientOriginalName();
                    $extension = $font_second->getClientOriginalExtension();
                    $check = in_array($extension, $allowedFontsSecondaryExtension);

                    if($check) {
                        $fontpath = $filename;
                        $font_second->move($requestfontsSecondarypath, $fontpath);

                        $assets = BrandAssets::create([
                            'filename' => $fontpath,
                            'brand_id' => $brand->id,
                            'type' => 'font_second',
                            'file_type' => $extension
                        ]);
                    }
                }
            }

            // Check upload inspirations
            if($request->hasFile('inspirations')) {
                $requestinspirationspath = public_path('storage/inspirations') .'/'. $userid;
                if(!File::isDirectory($requestinspirationspath)){
                    // Create Path
                    File::makeDirectory($requestinspirationspath, 0777, true, true);
                }

                $allowedInspirationsExtension = ['jpg','png','mp4','gif'];
                $inspirations = $request->file('inspirations');
                foreach($inspirations as $inspiration) {
                    $filename = $inspiration->getClientOriginalName();
                    $extension = $inspiration->getClientOriginalExtension();
                    $check = in_array($extension, $allowedInspirationsExtension);

                    if($check) {
                        $randomfilename = $this->helper->generateRandomString(15);
                        $inspirationpath = $randomfilename .'.'. $extension;
                        $inspiration->move($requestinspirationspath, $inspirationpath);

                        $assets = BrandAssets::create([
                            'filename' => $inspirationpath,
                            'brand_id' => $brand->id,
                            'type' => 'inspiration',
                            'file_type' => $extension
                        ]);
                    }
                }
            }

            // Check upload templates
            if($request->hasFile('templates')) {
                $requesttemplatespath = public_path('storage/templates') .'/'. $userid;
                if(!File::isDirectory($requesttemplatespath)){
                    // Create Path
                    File::makeDirectory($requesttemplatespath, 0777, true, true);
                }

                $allowedTemplatesExtension = ['psd', 'ai', 'doc', 'docx', 'pdf'];
                $templates = $request->file('templates');
                foreach($templates as $template) {
                    $filename = $template->getClientOriginalName();
                    $extension = $template->getClientOriginalExtension();
                    $check = in_array($extension, $allowedTemplatesExtension);

                    if($check) {
                        $randomfilename = $this->helper->generateRandomString(15);
                        $templatepath = $randomfilename .'.'. $extension;
                        $template->move($requesttemplatespath, $templatepath);

                        $assets = BrandAssets::create([
                            'filename' => $templatepath,
                            'brand_id' => $brand->id,
                            'type' => 'template',
                            'file_type' => $extension
                        ]);
                    }
                }
            }

            // Check upload guidelines
            if($request->hasFile('guidelines')) {
                $requestguidelinespath = public_path('storage/guidelines') .'/'. $userid;
                if(!File::isDirectory($requestguidelinespath)){
                    // Create Path
                    File::makeDirectory($requestguidelinespath, 0777, true, true);
                }

                $allowedGuidelinesExtension = ['psd', 'ai', 'doc', 'pdf'];
                $guidelines = $request->file('guidelines');
                foreach($guidelines as $guideline) {
                    $filename = $guideline->getClientOriginalName();
                    $extension = $guideline->getClientOriginalExtension();
                    $check = in_array($extension, $allowedGuidelinesExtension);

                    if($check) {
                        $randomfilename = $this->helper->generateRandomString(15);
                        $guidelinepath = $randomfilename .'.'. $extension;
                        $guideline->move($requestguidelinespath, $guidelinepath);

                        $assets = BrandAssets::create([
                            'filename' => $guidelinepath,
                            'brand_id' => $brand->id,
                            'type' => 'guideline',
                            'file_type' => $extension
                        ]);
                    }
                }
            }

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

    /**
     * Delete Brand Asset
     * @param Brand $brand
     * @return Index brand
     */
    public function deleteAsset(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $asset = BrandAssets::whereId($data['asset'])->first();

            // Delete Asset
            if($data['ftype'] == 'color' || $data['ftype'] == 'color_second') {
                BrandAssets::where('brand_id', $data['brand'])->where('type', $data['ftype'])->delete();
            } else {
                BrandAssets::whereId($asset->id)->delete();
            }

            DB::commit();
            return response()->json(['success' => 'Brand Asset Deleted Successfully!.']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
