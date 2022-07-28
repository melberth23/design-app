<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSettings;
use App\Models\Requests;
use App\Models\Brand;
use App\Models\Payments;
use App\Models\NewAttempt;
use App\Models\DeleteReason;
use App\Models\Invoices;
use App\Models\Country;
use App\Mail\DigitalMail;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Infinitypaul\LaravelPasswordHistoryValidation\Rules\NotFromPasswordHistory;
use Illuminate\Support\Carbon;
use App\Lib\PaymentHelper;
use App\Lib\SystemHelper;
use App\Rules\IsValidPassword;
use Redirect;
use File;

class HomeController extends Controller
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
            $queue = Requests::where('user_id', $user->id)->where('status', 2);
        } elseif($user->hasRole('Designer')) {
            $overall = Requests::where('status', '!=', 1)->orderBy('user_id', 'ASC');
            $completed = Requests::where('designer_id', $user->id)->where('status', 0);
            $inprogress = Requests::where('designer_id', $user->id)->where('status', 3);
            $forreview = Requests::where('designer_id', $user->id)->where('status', 4);
            $queue = Requests::whereNull('designer_id')->where('status', 2);
        } else {
            $overall = Requests::where('user_id', '!=', $user->id);
            $completed = Requests::where('user_id', '!=', $user->id)->where('status', 0);
            $inprogress = Requests::where('user_id', '!=', $user->id)->where('status', 3);
            $forreview = Requests::where('user_id', '!=', $user->id)->where('status', 4);
            $queue = Requests::where('user_id', '!=', $user->id)->where('status', 2);
        }

        if($filter == 'today') {
            $today = Carbon::now();
            $overall->where('created_at', $today);
            $completed->where('updated_at', $today);
            $inprogress->where('updated_at', $today);
            $forreview->where('updated_at', $today);
            $queue->where('updated_at', $today);
        }
        if($filter == 'yesterday') {
            $yesterday = Carbon::yesterday();
            $overall->where('created_at', $yesterday);
            $completed->where('updated_at', $yesterday);
            $inprogress->where('updated_at', $yesterday);
            $forreview->where('updated_at', $yesterday);
            $queue->where('updated_at', $yesterday);
        }
        if($filter == 'last7') {
            $last7 = Carbon::now()->subDays(7);
            $overall->where('created_at', '>=', $last7);
            $completed->where('updated_at', '>=', $last7);
            $inprogress->where('updated_at', '>=', $last7);
            $forreview->where('updated_at', '>=', $last7);
            $queue->where('updated_at', '>=', $last7);
        }
        if($filter == 'thismonth') {
            $overall->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month);
            $completed->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', Carbon::now()->month);
            $inprogress->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', Carbon::now()->month);
            $forreview->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', Carbon::now()->month);
            $queue->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', Carbon::now()->month);
        }
        if($filter == 'custom') {
            $fromd = date('Y-m-d', strtotime($from));
            $from = date('m/d/Y', strtotime($from));
            $tod = date('Y-m-d', strtotime($to));
            $to = date('m/d/Y', strtotime($to));

            $overall->whereBetween('created_at', [$fromd, $tod]);
            $completed->whereBetween('updated_at', [$fromd, $tod]);
            $inprogress->whereBetween('updated_at', [$fromd, $tod]);
            $forreview->whereBetween('updated_at', [$fromd, $tod]);
            $queue->whereBetween('updated_at', [$fromd, $tod]);
        }

        $total_requests = $overall->count();
        $completedreq = $completed->count();
        $inprogressreq = $inprogress->count();
        $reqforreview = $forreview->count();
        $reqqueue = $queue->count();


        if($user->hasRole('User')) {
            // Get all in progress requests
            $allinprogressreq = Requests::where('user_id', $user->id)->where('status', 3)->get();

            $payment_url = auth()->user()->payments->payment_url;
            if(auth()->user()->payments->status == 'cancelled') {
                $payment_url = route('profile.subscription');
            }

            return view('home', ['payment_status' => auth()->user()->payments->status, 'payment_url' => $payment_url, 'user_fullname' => $user->fullname, 'total_requests' => $total_requests, 'completed_req' => $completedreq, 'inprogressreq' => $inprogressreq, 'reqforreview' => $reqforreview, 'reqqueue' => $reqqueue, 'allinprogressreq' => $allinprogressreq, 'filter' => $filter, 'from' => $from, 'to' => $to]);
        } elseif($user->hasRole('Designer')) {
            return view('designer.home', ['total_requests' => $total_requests, 'completed_req' => $completedreq, 'inprogressreq' => $inprogressreq, 'reqforreview' => $reqforreview, 'reqqueue' => $reqqueue, 'filter' => $filter, 'from' => $from, 'to' => $to]);
        } else {

            $completeddata = $completed->groupBy('date')
                            ->get(array(
                                DB::raw('Date(updated_at) as date'),
                                DB::raw('COUNT(*) as "records"')
                            ));
            $queuedata = $queue->groupBy('date')
                            ->get(array(
                                DB::raw('Date(created_at) as date'),
                                DB::raw('COUNT(*) as "records"')
                            ));

            $overalldata = array();
            foreach($completeddata as $datav) {
                $overalldata[] = array(
                    'x' => $datav->date,
                    'y' => $datav->records
                );
            }

            $queuealldata = array();
            foreach($queuedata as $datae) {
                $queuealldata[] = array(
                    'x' => $datae->date,
                    'y' => $datae->records
                );
            }

            if($filter == 'today') {
                $numberofdays = 1;
                $today = Carbon::now();
                $today = date('Y-m-d', strtotime($today));

                $period = $this->helper->getDatesFromRange($today, $today);
            } elseif($filter == 'yesterday') {
                $numberofdays = 1;
                $yesterday = Carbon::yesterday();
                $yesterday = date('Y-m-d', strtotime($yesterday));

                $period = $this->helper->getDatesFromRange($yesterday, $yesterday);
            } elseif($filter == 'last7') {
                $numberofdays = 7;
                $firstdate = date('Y-m-d', strtotime('-7 days'));
                $lastdate = date('Y-m-d');
                $period = $this->helper->getDatesFromRange($firstdate, $lastdate);
            } elseif($filter == 'custom') {
                $fromn = strtotime($from);
                $ton = strtotime($to);
                
                $firstdate = date('Y-m-d', $fromn);
                $lastdate = date('Y-m-d', $ton);
                $period = $this->helper->getDatesFromRange($firstdate, $lastdate);
            } else {
                $firstdate = date('Y-m-01');
                $lastdate = date('Y-m-t');
                $period = $this->helper->getDatesFromRange($firstdate, $lastdate);
            }

            return view('admin.home', ['total_requests' => $total_requests, 'completed_req' => $completedreq, 'inprogressreq' => $inprogressreq, 'reqforreview' => $reqforreview, 'reqqueue' => $reqqueue, 'filter' => $filter, 'from' => $from, 'to' => $to, 'chartlabels' => $period, 'queuealldata' => $queuealldata, 'overalldata' => $overalldata]);
        }

    }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     */
    public function getProfile()
    {
        $countries = Country::all();

        return view('account.profile', compact('countries'));
    }

    /**
     * User Security Profile
     * @param Nill
     * @return View Profile
     */
    public function securityProfile()
    {
        return view('account.security');
    }

    /**
     * User Upgrade Plan
     * @param Nill
     * @return View Profile
     */
    public function upgrade()
    {  
        if(auth()->user()->payments->status == 'cancelled') {
            return redirect()->route('profile.subscription');
        } else {
            $duration = auth()->user()->payments->duration;
            $durationLabel = !empty($duration)?$duration:'monthly';
            return view('account.upgrade', ['durationlabel' => $durationLabel]);
        }
    }

    /**
     * User Upgrade Plan
     * @param Nill
     * @return View Profile
     */
    public function subscription()
    {  
        if(auth()->user()->payments->status == 'cancelled' || auth()->user()->payments->plan_status == 1) {
            return view('account.subscription');
        } else {
            return redirect()->route('profile.upgrade');
        }
    }

    /**
     * Invoices
     * @return All user invoices
     * */
    public function invoices()
    {
        $invoices = Invoices::where('user_id', auth()->user()->id)->paginate(10);
        return view('account.invoices', ['invoices' => $invoices]);
    }

    /**
     * Payment method
     * @return User Payment method
     * */
    public function paymentmethods()
    {
        $data = [
            'countries' => Country::all(),
            'billing_fname' => $this->helper->getUserSetting(auth()->user()->id, 'billing_fname'),
            'billing_lname' => $this->helper->getUserSetting(auth()->user()->id, 'billing_lname'),
            'billing_address1' => $this->helper->getUserSetting(auth()->user()->id, 'billing_address1'),
            'billing_address2' => $this->helper->getUserSetting(auth()->user()->id, 'billing_address2'),
            'billing_city' => $this->helper->getUserSetting(auth()->user()->id, 'billing_city'),
            'billing_state' => $this->helper->getUserSetting(auth()->user()->id, 'billing_state'),
            'billing_zipcode' => $this->helper->getUserSetting(auth()->user()->id, 'billing_zipcode'),
            'billing_country' => $this->helper->getUserSetting(auth()->user()->id, 'billing_country'),
            'card_brand' => $this->helper->getUserSetting(auth()->user()->id, 'card_brand'),
            'card_last4' => $this->helper->getUserSetting(auth()->user()->id, 'card_last4'),
            'card_expires_at' => $this->helper->getUserSetting(auth()->user()->id, 'card_expires_at')
        ];
        return view('account.paymentmethod', ['data' => $data]);
    }

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     */
    public function updateProfile(Request $request)
    {
        #Validations
        $rules = [];
        if($request->action == 'fullname') {
            $rules = [
                'first_name'    => 'required',
                'last_name'     => 'required'
            ];
        } elseif($request->action == 'email') {
            $rules = [
                'email' => 'required|unique:users,email'
            ];
        } elseif($request->action == 'phone') {
            $rules = [
                'mobile_number' => 'required|numeric|digits:10'
            ];
        } elseif($request->action == 'address') {
            $rules = [
                'address_1' => 'required',
                'city' => 'required',
                'state' => 'required',
                'zip' => 'required',
                'country' => 'required'
            ];
        } elseif($request->action == 'timezone') {
            $rules = [
                'time_zone' => 'required'
            ];
        } elseif($request->action == 'language') {
            $rules = [
                'language' => 'required'
            ];
        } elseif($request->action == 'currency') {
            $rules = [
                'currency' => 'required'
            ];
        } elseif($request->action == 'password') {
            $rules = [
                'current_password' => ['required', new MatchOldPassword],
                'new_password' => ['required', new NotFromPasswordHistory($request->user()), new isValidPassword()],
                'new_confirm_password' => ['same:new_password']
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return response()->json(array('error' => 1, 'msg'=> $validator->errors()->all()), 200);
        }

        try {
            DB::beginTransaction();
            
            $userid = auth()->user()->id;
            $key = 0;
            $value = 0;

            #Update Profile Data
            $data = [];
            if($request->action == 'fullname') {
                $data = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name
                ];
                User::whereId($userid)->update($data);
            } elseif($request->action == 'email') {
                $data = [
                    'email' => $request->email,
                ];
                $key = 'email';
                $value = $request->email;
                $this->helper->updateOrCreateUserSetting($userid, 'new_email', $request->email);

                // Send Code
                $code = sprintf("%04d", mt_rand(1, 9999));
                $details = array(
                    'subject' => 'Request change email code',
                    'heading' => 'Hi '. auth()->user()->fullname,
                    'message' => 'This email to confirm that you are about to change your email.',
                    'fieldlabel' => 'Email Address',
                    'code' => $code,
                    'template' => 'confirmcode'
                );
                Mail::to($request->email)->send(new DigitalMail($details));

                $this->helper->updateOrCreateUserSetting($userid, 'email_code', $code);

            } elseif($request->action == 'phone') {
                $data = [
                    'mobile_number' => $request->mobile_number
                ];
                User::whereId($userid)->update($data);
                // $key = 'phone';
                // $value = $request->mobile_number;
                // $this->helper->updateOrCreateUserSetting($userid, 'new_phone', $request->mobile_number);

                // // Send Code
                // $pcode = sprintf("%04d", mt_rand(1, 9999));
                // $details = array(
                //     'subject' => 'Request change phone code',
                //     'heading' => 'Hi '. auth()->user()->fullname,
                //     'message' => 'This email to confirm that you are about to change your phone number.',
                //     'fieldlabel' => 'phone number',
                //     'code' => $pcode,
                //     'template' => 'confirmcode'
                // );
                // Mail::to(auth()->user()->email)->send(new DigitalMail($details));

                // $this->helper->updateOrCreateUserSetting($userid, 'phone_code', $pcode);

            } elseif($request->action == 'address') {
                $data = [
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'country' => $request->country
                ];
                User::whereId($userid)->update($data);
            } elseif($request->action == 'timezone') {
                $data = [
                    'time_zone' => $request->time_zone
                ];
                $this->helper->updateOrCreateUserSetting($userid, 'time_zone', $request->time_zone);
            } elseif($request->action == 'language') {
                $data = [
                    'language' => $request->language
                ];
                $this->helper->updateOrCreateUserSetting($userid, 'language', $request->language);
            } elseif($request->action == 'currency') {
                $data = [
                    'currency' => $request->currency
                ];
                $this->helper->updateOrCreateUserSetting($userid, 'currency', $request->currency);
            } elseif($request->action == 'password') {
                User::find($userid)->update(['password'=> Hash::make($request->new_password)]);
            } elseif($request->action == 'billing') {
                $this->helper->updateOrCreateUserSetting($userid, 'billing_fname', $request->billing_fname);
                $this->helper->updateOrCreateUserSetting($userid, 'billing_lname', $request->billing_lname);
                $this->helper->updateOrCreateUserSetting($userid, 'billing_address1', $request->billing_address1);
                $this->helper->updateOrCreateUserSetting($userid, 'billing_address2', $request->billing_address2);
                $this->helper->updateOrCreateUserSetting($userid, 'billing_city', $request->billing_city);
                $this->helper->updateOrCreateUserSetting($userid, 'billing_state', $request->billing_state);
                $this->helper->updateOrCreateUserSetting($userid, 'billing_zipcode', $request->billing_zipcode);
                $this->helper->updateOrCreateUserSetting($userid, 'billing_country', $request->billing_country);
            }

            #Commit Transaction
            DB::commit();

            $request->session()->flash('success', 'Profile Updated Successfully.');

            return response()->json(array('error' => 0, 'msg'=> ['Profile updated successfully!'], 'key' => $key, 'value' => $value), 200);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            
            return response()->json(array('error' => 1, 'msg'=> $th->getMessage()), 200);
        }
    }

    /**
     * Verify new email
     * @param $email
     * @return Boolean with success
     */
    public function emailverifyProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'email_code' => 'required'
                    ]);

        if ($validator->fails())
        {
            return response()->json(array('error' => 1, 'msg'=> $validator->errors()->all()), 200);
        }

        try {
            DB::beginTransaction();
            
            $userid = auth()->user()->id;
            $email_code = UserSettings::where('user_id', $userid)->where('name', 'email_code')->first();
            if(!empty($email_code) && $email_code->value == $request->email_code) {
                $new_email = UserSettings::where('user_id', $userid)->where('name', 'new_email')->first();

                #Update Profile Data
                $data = [
                    'email' => $new_email->value,
                ];
                User::whereId($userid)->update($data);

                #Commit Transaction
                DB::commit();

                $request->session()->flash('success', 'Email updated and verified Successfully.');

                return response()->json(array('error' => 0, 'msg'=> 'Email updated and verified successfully!'), 200);
            } else {
                DB::rollBack();
            
                return response()->json(array('error' => 1, 'msg'=> ['Invalid code. Please try again!']), 200);
            }
            
        } catch (\Throwable $th) {
            DB::rollBack();
            
            return response()->json(array('error' => 1, 'msg'=> $th->getMessage()), 200);
        }
    }

    /**
     * Verify new phone
     * @param $phone
     * @return Boolean with success
     */
    public function phoneverifyProfile(Request $request)
    {

    }

    /**
     * Resend new code
     * @param $type
     * @return Boolean with success
     */
    public function resendcodeProfile(Request $request)
    {
        $userid = auth()->user()->id;

        // Send Code
        $code = sprintf("%04d", mt_rand(1, 9999));

        $field = 'phone_code';
        $label = 'phone number';
        $email = auth()->user()->email;
        if($request->type == 'email') {
            $field = 'email_code';
            $label = 'Email Address';

            $new_email = UserSettings::where('user_id', $userid)->where('name', 'new_email')->first();
            $email = $new_email->value;
        }

        $details = array(
            'subject' => 'Request change '. $request->type .' code',
            'heading' => 'Hi '. auth()->user()->fullname,
            'message' => 'This email to confirm that you are about to change your '. $label .'.',
            'fieldlabel' => $label,
            'code' => $code,
            'template' => 'confirmcode'
        );
        Mail::to($email)->send(new DigitalMail($details));

        $this->helper->updateOrCreateUserSetting($userid, $field, $code);

        return response()->json(array('error' => 0, 'msg'=> 'New code sent!'), 200);
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
            'new_password' => ['required', new NotFromPasswordHistory($request->user())],
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

    /**
     * Delete Account
     * @param Email address
     * @return Bolean with success message
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'email' => ['required']
        ]);

        try {
            #Match User
            if($request->email == auth()->user()->email) {

                // Send Email
                $details = array(
                    'subject' => 'Request status changed',
                    'heading' => 'Hi '. auth()->user()->fullname,
                    'message' => 'This email to confirm that you are about to delete your account in Designsowl.',
                    'template' => 'deleteaccount'
                );
                Mail::to(auth()->user()->email)->send(new DigitalMail($details));

                #Return To Profile page with success
                return response()->json(array('error' => 0, 'msg'=> ''), 200);
            } else {
                return response()->json(array('error' => 1, 'msg'=> 'You provide different email acount. Please try again.'), 200);
            }   
        } catch (\Throwable $th) {
            return response()->json(array('error' => 1, 'msg'=> $th->getMessage()), 200);
        }
    }

    /**
     * Delete Confirmation
     * @param token
     */
    public function delete()
    {
        return view('account.delete');
    }

    /**
     * Confirmation Delete
     * @param reasons
     * @return Redirect to login page
     */
    public function confirmDeleteAccount(Request $request)
    {
        $request->validate([
            'reason' => ['required']
        ]);

        try {
            DB::beginTransaction();

            // Get Payment Config
            $apikey = config('services.hitpay.key');
            $isStg = config('services.hitpay.environment');
            $payment = new PaymentHelper($apikey, $isStg);
            $response = $payment->recurringDeleteAccount(auth()->user()->payments->reference);
            if(!empty($response['status']) && $response['status'] == 'canceled') {
                $reason = $this->helper->reasons($request->reason);

                // Save reason
                DeleteReason::create([
                    'email' => auth()->user()->email,
                    'reason' => $reason
                ]);

                // Delete Account
                User::whereId(auth()->user()->id)->delete();

                #Commit Transaction
                DB::commit();

                #Redirect To Login page with success
                return redirect()->route('login')->with('message', 'Your account is successfully deleted!');
            } else {
                DB::rollBack();
                return back()->with('error', 'There was a problem deleting account. Please try again.');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Cancel Subscription
     * @param plan information
     * @return redirect to new select plan
     */
    public function cancel()
    {
        try {
            DB::beginTransaction();

            // Get Payment Config
            $apikey = config('services.hitpay.key');
            $isStg = config('services.hitpay.environment');
            $payment = new PaymentHelper($apikey, $isStg);
            $response = $payment->recurringDeleteAccount(auth()->user()->payments->reference);
            if(!empty($response['status']) && $response['status'] == 'canceled') {
                
                Payments::whereId(auth()->user()->payments->id)->update(['plan_status' => 1]);

                #Commit Transaction
                DB::commit();

                #Redirect To Login page with success
                return redirect()->route('profile.upgrade')->with('success', 'Your subscription is successfully cancelled!');
            } else {
                DB::rollBack();
                return back()->with('error', 'There was a problem cancelling subscription. Please try again.');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Upgrade plan 
     * @param plan informatio
     * @return redirect to payment page
     */
    public function upgradeplan(Request $request)
    {
        try {
            DB::beginTransaction();

            // Start date
            $startdate = auth()->user()->payments->created_at;
            if(auth()->user()->payments->recurring_date) {
                $startdate = auth()->user()->payments->recurring_date;
            }

            // Add new plan
            $selectedplan = $request->plan;
            $selectedduration = $request->duration;
            $planInfo = $this->helper->getPlanInformation($selectedplan, $selectedduration);
            // Get Payment Config
            $apikey = config('services.hitpay.key');
            $isStg = config('services.hitpay.environment');
            $payment = new PaymentHelper($apikey, $isStg);
            $respayment = $payment->recurringRequestCreate(array(
                'plan_id'    =>  $planInfo['id'],
                'customer_email'  =>  auth()->user()->email,
                'customer_name'  =>  auth()->user()->first_name .' '. auth()->user()->last_name,
                'start_date'  =>  date('Y-m-d'),
                'redirect_url'  =>  url("change-payment-success"),
                'reference'  =>  time()
            ));

            if(!empty($respayment['status']) && $respayment['status'] == 'scheduled') {
                NewAttempt::create([
                    'user_id' => auth()->user()->id,
                    'reference' => $respayment['id'],
                    'business_recurring_plans_id' => $respayment['business_recurring_plans_id'],
                    'plan' => $selectedplan,
                    'cycle' => $respayment['cycle'],
                    'currency' => $respayment['currency'],
                    'price' => $respayment['price'],
                    'status' => $respayment['status'],
                    'payment_methods' => json_encode($respayment['payment_methods']),
                    'payment_url' => $respayment['url'],
                    'duration' => $selectedduration,
                ]);

                // Send Email
                $details = array(
                    'subject' => 'Payment Confirmation Details!',
                    'message' => 'Welcome '. auth()->user()->first_name .' '. auth()->user()->last_name .',',
                    'extra_msg' => 'Please see details below:',
                    'plan' => $planInfo['label'],
                    'amount' => number_format($planInfo['amount']),
                    'paymentlink' => 'Please pay to continue use your account '. $respayment['url'] .' or disregard if already paid.',
                    'thank_msg' => 'Thank you!',
                    'template' => 'payment'
                );

                Mail::to(auth()->user()->email)->send(new DigitalMail($details));

                #Commit Transaction
                DB::commit();

                return Redirect::away($respayment['url']);
            } else {
                $errormsg = 'Please try again! Something wen\'t wrong.';
                if(!empty($respayment['errors'])) {
                    $errormsg = $respayment['message'];
                }
                return Redirect::back()->with('error', $errormsg);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Select new plan
     * @param plan
     * @return redirect to payment page
     * */
    public function addplan(Request $request)
    {
        try {
            $selectedplan = $request->plan;
            $planInfo = $this->helper->getPlanInformation($selectedplan);

            // Get Payment Config
            $apikey = config('services.hitpay.key');
            $isStg = config('services.hitpay.environment');
            $payment = new PaymentHelper($apikey, $isStg);
            $respayment = $payment->recurringRequestCreate(array(
                'plan_id'    =>  $planInfo['id'],
                'customer_email'  =>  auth()->user()->email,
                'customer_name'  =>  auth()->user()->first_name .' '. auth()->user()->last_name,
                'start_date'  =>  date('Y-m-d', strtotime("+1 day")),
                'redirect_url'  =>  url("payment-success"),
                'reference'  =>  time()
            ));

            if(!empty($respayment['status']) && $respayment['status'] == 'scheduled') {

                Payments::create([
                    'user_id' => auth()->user()->id,
                    'reference' => $respayment['id'],
                    'business_recurring_plans_id' => $respayment['business_recurring_plans_id'],
                    'plan' => $selectedplan,
                    'cycle' => $respayment['cycle'],
                    'currency' => $respayment['currency'],
                    'price' => $respayment['price'],
                    'status' => $respayment['status'],
                    'payment_methods' => json_encode($respayment['payment_methods']),
                    'payment_url' => $respayment['url'],
                ]);

                // Send Email
                $details = array(
                    'subject' => 'Payment Confirmation Details!',
                    'message' => 'Welcome '. auth()->user()->first_name .' '. auth()->user()->last_name .',',
                    'extra_msg' => 'Please see details below:',
                    'plan' => $planInfo['label'],
                    'amount' => number_format($planInfo['amount']),
                    'paymentlink' => 'Please pay to continue use your account '. $respayment['url'] .' or disregard if already paid.',
                    'thank_msg' => 'Thank you!',
                    'template' => 'payment'
                );

                Mail::to(auth()->user()->email)->send(new DigitalMail($details));

                #Commit Transaction
                DB::commit();

                return Redirect::away($respayment['url']);
            } else {
                $errormsg = 'Please try again! Something wen\'t wrong.';
                if(!empty($respayment['errors'])) {
                    $errormsg = $respayment['message'];
                }
                return Redirect::back()->with('error', $errormsg);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Change card
     * @param confirmation
     * @return redirect to payment page
     * */
    public function changecard()
    {
        $plan = auth()->user()->payments->plan;
        $planInfo = $this->helper->getPlanInformation($plan);

        // Start date
        $startdate = auth()->user()->payments->created_at;
        if(auth()->user()->payments->recurring_date) {
            $startdate = auth()->user()->payments->recurring_date;
        }

        // Get Payment Config
        $apikey = config('services.hitpay.key');
        $isStg = config('services.hitpay.environment');
        $payment = new PaymentHelper($apikey, $isStg);
        $respayment = $payment->recurringRequestCreate(array(
            'plan_id'    =>  $planInfo['id'],
            'customer_email'  =>  auth()->user()->email,
            'customer_name'  =>  auth()->user()->first_name .' '. auth()->user()->last_name,
            'start_date'  =>  date('Y-m-d', strtotime($startdate)),
            'redirect_url'  =>  url("change-payment-success"),
            'reference'  =>  time()
        ));

        if(!empty($respayment['status']) && $respayment['status'] == 'scheduled') {
            NewAttempt::create([
                'user_id' => auth()->user()->id,
                'reference' => $respayment['id'],
                'business_recurring_plans_id' => $respayment['business_recurring_plans_id'],
                'plan' => $plan,
                'cycle' => $respayment['cycle'],
                'currency' => $respayment['currency'],
                'price' => $respayment['price'],
                'status' => $respayment['status'],
                'payment_methods' => json_encode($respayment['payment_methods']),
                'payment_url' => $respayment['url'],
            ]);

            // Send Email
            $details = array(
                'subject' => 'Change Payment Method Card!',
                'message' => 'Hi '. auth()->user()->first_name .' '. auth()->user()->last_name .',',
                'extra_msg' => 'Please see details below:',
                'plan' => $planInfo['label'],
                'amount' => number_format($planInfo['amount']),
                'paymentlink' => 'Please click or visit link '. $respayment['url'] .' to continue change your card for subscription.',
                'thank_msg' => 'Thank you!',
                'template' => 'payment'
            );

            Mail::to(auth()->user()->email)->send(new DigitalMail($details));

            #Commit Transaction
            DB::commit();

            return Redirect::away($respayment['url']);
        } else {
            $errormsg = 'Please try again! Something wen\'t wrong.';
            if(!empty($respayment['errors'])) {
                $errormsg = $respayment['message'];
            }
            return Redirect::back()->with('error', $errormsg);
        }
    }

    public function notifications()
    {
        return view('account.notifications');
    }

    public function updateProfileImage(Request $request)
    {
        $userid = $request->user()->id;

        if($request->hasFile('file')) {
            $requestprofilepath = public_path('storage/profiles') .'/'. $userid;
            if(!File::isDirectory($requestprofilepath)){
                // Create Path
                File::makeDirectory($requestprofilepath, 0777, true, true);
            }

            $allowedprofileExtension = ['jpg','png'];
            $profile = $request->file('file');

            $filename = $profile->getClientOriginalName();
            $extension = $profile->getClientOriginalExtension();
            $check = in_array($extension, $allowedprofileExtension);

            if($check) { 
                $randomfilename = $this->helper->generateRandomString(15);
                $profilepath = $randomfilename .'.'. $extension;
                $profile->move($requestprofilepath, $profilepath);

                User::where('id', $userid)->update([
                    'profile_img' => $profilepath
                ]);
            }
        }

    }
}
