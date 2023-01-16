<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Coupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Lib\SystemHelper;

class CouponsController extends Controller
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
        $this->middleware('role:Admin');

        $this->helper = new SystemHelper();
    }

    /**
     * List request types 
     * @param Nill
     * @return Array $requests
     */
    public function index()
    {
        $coupons = Coupons::paginate(10);
        return view('admin.coupons.index', ['coupons' => $coupons]);
    }

    /**
     * Create Request Types 
     * @param Nill
     * @return Array $requests
     */
    public function create()
    {
        $codegenerated = $this->helper->generateRandomString(10);
        return view('admin.coupons.add', ['code' => $codegenerated]);
    }

    /**
     * Store Request Types
     * @param Request $request
     * @return View requests
     */
    public function store(Request $request)
    {
        // Validations
        $request->validate([
            'code'      => 'required',
            'discount_amount'      => 'required',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $requests = Coupons::create([
                'code'              => $request->code,
                'discount_type'     => $request->discount_type,
                'discount_amount'   => $request->discount_amount,
                'description'       => $request->description,
                'status'            => 1,
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('coupons.index')->with('success','Coupon Created Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Edit Requests
     * @param Integer $requests
     * @return Collection $requests
     */
    public function edit(Coupons $coupon)
    {
        return view('admin.coupons.edit')->with([
            'coupon'  => $coupon
        ]);
    }

    /**
     * Update Request Types
     * @param Request $request, Requests $requests
     * @return View requests
     */
    public function update(Request $request, Coupons $coupon)
    {
        // Validations
        $request->validate([
            'code'      => 'required'
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $requests_updated = Coupons::whereId($coupon->id)->update([
                'code'          => $request->code,
                'description'   => $request->description
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('coupons.index')->with('success','Coupon Updated Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete Request Types
     * @param Requests $requests
     * @return Index requests
     */
    public function delete(Coupons $coupon)
    {
        DB::beginTransaction();
        try {
            // Delete Requests
            Coupons::whereId($coupon->id)->delete();

            DB::commit();
            return redirect()->route('coupons.index')->with('success', 'Coupon Deleted Successfully!.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
