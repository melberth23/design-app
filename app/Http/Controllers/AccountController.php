<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Payments;
use App\Models\UserVerify;
use App\Mail\DigitalMail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Lib\PaymentHelper;
use App\Lib\SystemHelper;
use Redirect;
use DB;


class AccountController extends Controller
{
    public $helper;

    public function __construct()
    {
        $this->helper = new SystemHelper();
    }

    /**
    * Verify View 
    */
    public function verify($token=false)
    {
        if($token) {
            $existToken = UserVerify::where('token', $token)->first();
            if(!is_null($existToken)){
                if(!$existToken->user->is_email_verified) {
                    $email = $existToken->user->email;
                    return view('auth.verify', ['email' => $email, 'token' => $token]);
                } else {
                    return redirect()->route('login')->with('message', 'Your e-mail is already verified. You can now login.');
                }
            } else {
                return redirect()->route('register');
            }
        } else {
            return redirect()->route('register');
        }
    }
    
    /**
    * Plan View 
    */
    public function plan()
    {
        $email = Auth::user()->email;
        return view('auth.plan', ['email' => $email]);
    }
    
    /**
    * Check account token
    * @param array $data
    * @return Error and Success message
    */
    public function checkTokenAccount(Request $request)
    {
        $req = $request->all();

        $code1 = $req['code1'];
        $code2 = $req['code2'];
        $code3 = $req['code3'];
        $code4 = $req['code4'];
        $token = $req['token'];

        $code = $code1 . $code2 . $code3 . $code4;
        if(!empty($code)) {
            $verifyUser = UserVerify::where('code', $code)->where('token', $token)->first();
            if(!empty($verifyUser) ){
                $user = $verifyUser->user;

                if(!$user->is_email_verified) {
                    $verifyUser->user->is_email_verified = 1;
                    $verifyUser->user->save();

                    Auth::login($verifyUser->user);

                    return redirect()->route('user.plan');
                } else {
                    return Redirect::back()->with('error', 'Please try again! Something wen\'t wrong.');
                }
            } else {
                return Redirect::back()->with('error', 'Please enter valid code.');
            }
        } else {
            return Redirect::back()->with('error', 'Please enter valid code.');
        }


    }

    public function addplan(Request $request) {
        $posts = $request->all();

        try {
            $user = Auth::user();
            $first_name = $user->first_name;
            $last_name = $user->last_name;
            $email = $user->email;

            // Get Payment Config
            $apikey = config('services.hitpay.key');
            $isStg = config('services.hitpay.environment');
            $customerfullname = $first_name .' '. $last_name;
            $selectedplan = $posts['plan'];
            $planInfo = $this->helper->getPlanInformation($selectedplan);
            $payment = new PaymentHelper($apikey, $isStg);
            $response = $payment->recurringRequestCreate(array(
                'plan_id'    =>  $planInfo['id'],
                'customer_email'  =>  $email,
                'customer_name'  =>  $customerfullname,
                'start_date'  =>  date('Y-m-d', strtotime("+1 day")),
                'redirect_url'  =>  url("payment-success"),
                'reference'  =>  time()
            ));

            if(!empty($response['status']) && $response['status'] == 'scheduled') {
                $this->createPayment([
                    'user_id' => $user->id,
                    'reference' => $response['id'],
                    'business_recurring_plans_id' => $response['business_recurring_plans_id'],
                    'plan' => $selectedplan,
                    'cycle' => $response['cycle'],
                    'currency' => $response['currency'],
                    'price' => $response['price'],
                    'status' => $response['status'],
                    'payment_methods' => $response['payment_methods'],
                    'payment_url' => $response['url'],
                ]);

                // Send Email
                $details = array(
                    'subject' => 'Payment Confirmation Details!',
                    'message' => 'Welcome '. $customerfullname .',',
                    'extra_msg' => 'Please see details below:',
                    'plan' => $planInfo['label'],
                    'amount' => number_format($planInfo['amount']),
                    'paymentlink' => 'Please pay to continue use your account '. $response['url'] .' or disregard if already paid.',
                    'thank_msg' => 'Thank you!',
                    'template' => 'payment'
                );

                Mail::to($user)->send(new DigitalMail($details));

                return Redirect::away($response['url']);
            } else {
                $errormsg = 'Please try again! Something wen\'t wrong.';
                if(!empty($response['errors'])) {
                    $errormsg = $response['message'];
                }
                return Redirect::back()->with('error', $errormsg);
            }
        }
        catch (Exception $e) {
            return Redirect::back()->with('error', 'Please try again! Something wen\'t wrong.');
        }
    }

    public function createPayment(array $data) {
        return Payments::create([
            'user_id' => $data['user_id'],
            'reference' => $data['reference'],
            'business_recurring_plans_id' => $data['business_recurring_plans_id'],
            'plan' => $data['plan'],
            'cycle' => $data['cycle'],
            'currency' => $data['currency'],
            'price' => $data['price'],
            'status' => $data['status'],
            'payment_methods' => json_encode($data['payment_methods']),
            'payment_url' => $data['payment_url'],
        ]);
    }

}
