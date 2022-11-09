<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activities;
use App\Models\UserVerify;
use App\Models\Payments;
use App\Models\Invoices;
use App\Mail\DigitalMail;
use App\Lib\PaymentHelper;
use App\Lib\SystemHelper;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
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
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create','store', 'updateStatus']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['delete']]);

        $this->helper = new SystemHelper();
    }


    /**
     * List User 
     * @param Nill
     * @return Array $user
     
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', ['users' => $users]);
    }
    
    /**
     * Create User 
     * @param Nill
     * @return Array $user
     */
    public function create()
    {
        $roles = Role::all();
       
        return view('users.add', ['roles' => $roles]);
    }

    /**
     * Store User
     * @param Request $request
     * @return View Users
     */
    public function store(Request $request)
    {
        // Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|unique:users,email',
            'mobile_number' => 'required|numeric|digits:10',
            'role_id'       =>  'required|exists:roles,id',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            $randomstring = $this->helper->generateRandomString(12);
            $password = Hash::make($randomstring);

            // Store Data
            $user = User::create([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'mobile_number' => $request->mobile_number,
                'role_id'       => $request->role_id,
                'status'        => $request->status,
                'password'      => $password,
                'manual'        => 1
            ]);

            $token = Str::random(64);
            $code = sprintf("%04d", mt_rand(1, 9999));
  
            UserVerify::create([
                'user_id' => $user->id, 
                'token' => $token,
                'code' => $code
            ]);

            $message = 'You\'re account is created please refer credentials below.';

            // Send email
            $details = array(
                'subject' => 'Welcome aboard',
                'fromemail' => 'hello@designsowl.com',
                'fromname' => 'DesignsOwl',
                'heading' => 'Hi '. $user->first_name,
                'message' => $message,
                'sub_message' => 'Please login using your email and this password '. $randomstring .' and the code '. $code .'. Thank you!',
                'template' => 'welcome'
            );
            Mail::to($user->email)->send(new DigitalMail($details));

            if($request->role_id == 2) {
                $customerfullname = $request->first_name .' '. $request->last_name;
                $selectedplan = $request->plan;
                $selectedduration = $request->duration;
                $planInfo = $this->helper->getPlanInformation($selectedplan, $selectedduration);

                $payments = Payments::create([
                    'user_id' => $user->id,
                    'reference' => 'manual_reference_'. time(),
                    'business_recurring_plans_id' => 'manual_business_recurring_'. time(),
                    'plan' => $selectedplan,
                    'cycle' => $selectedduration,
                    'currency' => 'sgd',
                    'price' => number_format($planInfo['amount']),
                    'status' => 'active',
                    'payment_methods' => 'manual',
                    'payment_url' => '',
                    'duration' => $selectedduration
                ]);

                $datetoday = date('Y-m-d');
                Invoices::create([
                    'user_id' => $user->id,
                    'payment_id' => $payments->id,
                    'number' => 100000000,
                    'date_invoice' => $datetoday,
                    'plan' => $payments->plan,
                    'amount' => $payments->price
                ]);

                // Send Email
                $details = array(
                    'subject' => 'Payment Confirmation Details',
                    'message' => 'Welcome '. $customerfullname .',',
                    'extra_msg' => 'Please see details below:',
                    'plan' => $planInfo['label'],
                    'amount' => number_format($planInfo['amount']),
                    'paymentlink' => 'Please pay to continue use your account or disregard if already paid.',
                    'thank_msg' => 'Thank you!',
                    'template' => 'payment'
                );

                Mail::to($user)->send(new DigitalMail($details));
            }

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            
            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success','User Created Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Update Status Of User
     * @param Integer $status
     * @return List Page With Success
     */
    public function updateStatus($user_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'user_id'   => $user_id,
            'status'    => $status
        ], [
            'user_id'   =>  'required|exists:users,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('users.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            User::whereId($user_id)->update(['status' => $status]);

            // get payments
            $payments = Payments::where('user_id', $user_id)->latest('created_at')->first();
            if(!empty($payments)) {
                Payments::whereId($payments->id)->update(['status' => 'active']);
            }

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('users.index')->with('success','User Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Edit User
     * @param Integer $user
     * @return Collection $user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit')->with([
            'roles' => $roles,
            'user'  => $user
        ]);
    }

    /**
     * Update User
     * @param Request $request, User $user
     * @return View Users
     */
    public function update(Request $request, User $user)
    {
        // Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|unique:users,email,'.$user->id.',id',
            'mobile_number' => 'required|numeric|digits:10',
            'role_id'       =>  'required|exists:roles,id',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $user_updated = User::whereId($user->id)->update([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'mobile_number' => $request->mobile_number,
                'role_id'       => $request->role_id,
                'status'        => $request->status,
            ]);

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            
            // Assign Role To User
            $user->assignRole($user->role_id);

            // Save to Activities
            Activities::create([
                'user_id' => auth()->user()->id,
                'subscriber_id' => $user->id,
                'activity_note' => auth()->user()->first_name ." edited an account of ". $request->first_name ." ". $request->last_name
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success','User Updated Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete User
     * @param User $user
     * @return Index Users
     */
    public function delete(User $user)
    {
        DB::beginTransaction();
        try {
            // Delete User
            User::whereId($user->id)->delete();

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Deleted Successfully!.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Import Users 
     * @param Null
     * @return View File
     */
    public function importUsers()
    {
        return view('users.import');
    }

    public function uploadUsers(Request $request)
    {
        Excel::import(new UsersImport, $request->file);
        
        return redirect()->route('users.index')->with('success', 'User Imported Successfully');
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

}
