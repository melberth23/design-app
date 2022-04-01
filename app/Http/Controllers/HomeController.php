<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Requests;
use App\Models\Brand;
use App\Models\Payments;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        if($user->hasRole('User')) {
            // Get all request current month
            $currentmonthreq = Requests::where('user_id', $user->id)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->count();
            // Get number of completed requests
            $completedreq = Requests::where('user_id', $user->id)->where('status', 0)->count();
            // Get number of pending requests
            $reqforreview = Requests::where('user_id', $user->id)->where('status', 1)->count();
            // Get number of active requests
            $activereq = Requests::where('user_id', $user->id)->where('status', 2)->count();

            // Get payment link if not yet paid
            $paymentinfo = Payments::where('user_id', $user->id)->first();

            return view('home', ['payment_status' => $paymentinfo->status, 'payment_url' => $paymentinfo->payment_url, 'user_fullname' => $user->fullname, 'cur_month_req' => $currentmonthreq, 'completed_req' => $completedreq, 'req_for_review' => $reqforreview, 'active_req' => $activereq]);
        } else {
            // Get all request current month
            $currentmonthreq = Requests::where('user_id', '!=', $user->id)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->count();
            // Get number of completed requests
            $completedreq = Requests::where('user_id', '!=', $user->id)->where('status', 0)->count();
            // Get number of pending requests
            $reqforreview = Requests::where('user_id', '!=', $user->id)->where('status', 1)->count();
            // Get number of active requests
            $activereq = Requests::where('user_id', '!=', $user->id)->where('status', 2)->count();
            // Get number of active brands
            $activebrands = Brand::where('user_id', '!=', $user->id)->where('status', 1)->count();
            // Get number of active users
            $activeusers = User::where('id', '!=', $user->id)->where('role_id', 2)->where('status', 1)->count();
            // Get number of pending users
            $pendingusers = User::where('id', '!=', $user->id)->where('role_id', 2)->where('status', 0)->count();

            return view('admin.home', ['active_brands' => $activebrands, 'active_users' => $activeusers, 'pending_users' => $pendingusers, 'user_fullname' => $user->fullname, 'cur_month_req' => $currentmonthreq, 'completed_req' => $completedreq, 'req_for_review' => $reqforreview, 'active_req' => $activereq]);
        }

    }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     */
    public function getProfile()
    {
        return view('profile');
    }

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     */
    public function updateProfile(Request $request)
    {
        #Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_number' => 'required|numeric|digits:10',
        ]);

        try {
            DB::beginTransaction();
            
            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
            ]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Profile Updated Successfully.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        try {
            DB::beginTransaction();

            #Update Password
            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
            
            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Password Changed Successfully.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
