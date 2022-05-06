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
    public function index(Request $request)
    {
        $user = Auth::user();

        $filter = $request->filter;
        $from = $request->from;
        $to = $request->to;

        if($user->hasRole('User')) {
            $overall = Requests::where('user_id', $user->id);
            $completed = Requests::where('user_id', $user->id)->where('status', 0);
            $inprogress = Requests::where('user_id', $user->id)->where('status', 3);
            $forreview = Requests::where('user_id', $user->id)->where('status', 4);
            $queue = Requests::where('user_id', $user->id)->whereIn('status', [2, 3]);
        } else {
            $overall = Requests::where('user_id', '!=', $user->id);
            $completed = Requests::where('user_id', '!=', $user->id)->where('status', 0);
            $inprogress = Requests::where('user_id', '!=', $user->id)->where('status', 3);
            $forreview = Requests::where('user_id', '!=', $user->id)->where('status', 4);
            $queue = Requests::where('user_id', '!=', $user->id)->whereIn('status', [2, 3]);
        }

        if($filter == 'today') {
            $today = Carbon::now();
            $overall->where('created_at', $today);
            $completed->where('created_at', $today);
            $inprogress->where('created_at', $today);
            $forreview->where('created_at', $today);
            $queue->where('created_at', $today);
        }
        if($filter == 'yesterday') {
            $yesterday = Carbon::yesterday();
            $overall->where('created_at', $yesterday);
            $completed->where('created_at', $yesterday);
            $inprogress->where('created_at', $yesterday);
            $forreview->where('created_at', $yesterday);
            $queue->where('created_at', $yesterday);
        }
        if($filter == 'last7') {
            $last7 = Carbon::now()->subDays(7);
            $overall->where('created_at', '>=', $last7);
            $completed->where('created_at', '>=', $last7);
            $inprogress->where('created_at', '>=', $last7);
            $forreview->where('created_at', '>=', $last7);
            $queue->where('created_at', '>=', $last7);
        }
        if($filter == 'thismonth') {
            $overall->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month);
            $completed->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month);
            $inprogress->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month);
            $forreview->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month);
            $queue->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month);
        }
        if($filter == 'custom') {
            $fromd = date('Y-m-d', strtotime($from));
            $from = date('m/d/Y', strtotime($from));
            $tod = date('Y-m-d', strtotime($to));
            $to = date('m/d/Y', strtotime($to));

            $overall->whereBetween('created_at', [$fromd, $tod]);
            $completed->whereBetween('created_at', [$fromd, $tod]);
            $inprogress->whereBetween('created_at', [$fromd, $tod]);
            $forreview->whereBetween('created_at', [$fromd, $tod]);
            $queue->whereBetween('created_at', [$fromd, $tod]);
        }

        $total_requests = $overall->count();
        $completedreq = $completed->count();
        $inprogressreq = $inprogress->count();
        $reqforreview = $forreview->count();
        $reqqueue = $queue->count();


        if($user->hasRole('User')) {
            // Get payment link if not yet paid
            $paymentinfo = Payments::where('user_id', $user->id)->first();

            // Get all in progress requests
            $allinprogressreq = Requests::where('user_id', $user->id)->where('status', 3)->get();

            return view('home', ['payment_status' => $paymentinfo->status, 'payment_url' => $paymentinfo->payment_url, 'user_fullname' => $user->fullname, 'total_requests' => $total_requests, 'completed_req' => $completedreq, 'inprogressreq' => $inprogressreq, 'reqforreview' => $reqforreview, 'reqqueue' => $reqqueue, 'allinprogressreq' => $allinprogressreq, 'filter' => $filter, 'from' => $from, 'to' => $to]);
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
