<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandAssets;
use App\Models\Payments;
use App\Models\TempFile;
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

class BrandsController extends Controller
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
        // Get lists of brands
        $brands = Brand::where('user_id', '!=', 0);
        if(!empty($type) && $type == 'date') {
            $brands->orderByRaw('created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $brands->orderBy('name', $sort);
        }
        $brands = $brands->paginate(10);

        return view('admin.brands.index', ['brands' => $brands]);
    }

    /**
     * Drafts List brands 
     * @param Nill
     * @return Array $brands
     */
    public function drafts($type=false, $sort=false)
    {
        // Get lists of brands
        $brands = Brand::where('status', 0);
        if(!empty($type) && $type == 'date') {
            $brands->orderByRaw('created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $brands->orderBy('name', $sort);
        }
        $brands = $brands->paginate(10);

        return view('admin.brands.drafts', ['brands' => $brands]);
    }

    /**
     * Archived List brands 
     * @param Nill
     * @return Array $brands
     */
    public function archived($type=false, $sort=false)
    {
        // Get lists of brands
        $brands = Brand::where('status', 2);
        if(!empty($type) && $type == 'date') {
            $brands->orderByRaw('created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $brands->orderBy('name', $sort);
        }
        $brands = $brands->paginate(10);

        return view('admin.brands.archived', ['brands' => $brands]);
    }
}
