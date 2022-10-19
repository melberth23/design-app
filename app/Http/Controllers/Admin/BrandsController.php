<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
    public function index($customer_id, $type=false, $sort=false)
    {
        // Get lists of brands
        $brands = Brand::where('user_id', $customer_id)->where('user_id', '!=', 0);
        if(!empty($type) && $type == 'date') {
            $brands->orderByRaw('created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $brands->orderBy('name', $sort);
        }
        $brands = $brands->paginate(10);

        return view('admin.brands.index', ['brands' => $brands, 'customer_id' => $customer_id]);
    }

    /**
     * Active List brands 
     * @param Nill
     * @return Array $brands
     */
    public function active($customer_id, $type=false, $sort=false)
    {
        // Get lists of brands
        $brands = Brand::where('user_id', $customer_id)->where('status', 1);
        if(!empty($type) && $type == 'date') {
            $brands->orderByRaw('created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $brands->orderBy('name', $sort);
        }
        $brands = $brands->paginate(10);

        return view('admin.brands.active', ['brands' => $brands, 'customer_id' => $customer_id]);
    }

    /**
     * Drafts List brands 
     * @param Nill
     * @return Array $brands
     */
    public function drafts($customer_id, $type=false, $sort=false)
    {
        // Get lists of brands
        $brands = Brand::where('user_id', $customer_id)->where('status', 0);
        if(!empty($type) && $type == 'date') {
            $brands->orderByRaw('created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $brands->orderBy('name', $sort);
        }
        $brands = $brands->paginate(10);

        return view('admin.brands.drafts', ['brands' => $brands, 'customer_id' => $customer_id]);
    }

    /**
     * Archived List brands 
     * @param Nill
     * @return Array $brands
     */
    public function archived($customer_id, $type=false, $sort=false)
    {
        // Get lists of brands
        $brands = Brand::where('user_id', $customer_id)->where('status', 2);
        if(!empty($type) && $type == 'date') {
            $brands->orderByRaw('created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $brands->orderBy('name', $sort);
        }
        $brands = $brands->paginate(10);

        return view('admin.brands.archived', ['brands' => $brands, 'customer_id' => $customer_id]);
    }


    /**
     * Customers requests 
     * @param $status
     * @return Array $customers
     */
    public function customerLists($status)
    {
        $userbrand = User::leftJoin('brands', function($join) {
                        $join->on('users.id', '=', 'brands.user_id');
                    });

        if($status == 'all') {
            $users = $userbrand->select('users.id as uid','users.first_name', 'users.last_name', 'brands.status as rstatus')->where('brands.user_id', '!=', 1)->groupBy('users.id')->paginate(10);
        } else {
            $users = $userbrand->select('users.id as uid','users.first_name', 'users.last_name', 'brands.status as rstatus')->where('brands.user_id', '!=', 1)->where('brands.status', $status)->groupBy('users.id')->paginate(10);
        }

        $brands = User::leftJoin('brands', function($join) {
                        $join->on('users.id', '=', 'brands.user_id');
                    })->where('brands.user_id', '!=', 1)->groupBy('users.id')->get();
        $active = User::leftJoin('brands', function($join) {
                        $join->on('users.id', '=', 'brands.user_id');
                    })->where('brands.user_id', '!=', 1)->where('brands.status', 1)->groupBy('users.id')->get();
        $draft = User::leftJoin('brands', function($join) {
                        $join->on('users.id', '=', 'brands.user_id');
                    })->where('brands.user_id', '!=', 1)->where('brands.status', 0)->groupBy('users.id')->get();
        $archived = User::leftJoin('brands', function($join) {
                        $join->on('users.id', '=', 'brands.user_id');
                    })->where('brands.user_id', '!=', 1)->where('brands.status', 2)->groupBy('users.id')->get();

        $templatebystatus = 'index';
        $labelStatus = 'All brands';
        if($status == 0) {
            $templatebystatus = 'drafts';
            $labelStatus = 'Draft brands';
        } elseif($status == 1) {
            $templatebystatus = 'active';
            $labelStatus = 'Active brands';
        } elseif($status == 2) {
            $templatebystatus = 'archived';
            $labelStatus = 'Archived brands';
        }

        return view('admin.brands.customer-lists', ['users' => $users, 'status' => $templatebystatus, 'labelstatus' => $labelStatus, 'brands' => $brands, 'active' => $active, 'draft' => $draft, 'archived' => $archived]);
    }
}
