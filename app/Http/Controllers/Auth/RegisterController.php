<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Payments;
use App\Mail\DigitalPaymentMail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Lib\PaymentHelper;
use App\Lib\SystemHelper;
use Redirect;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request) {
        $posts = $request->all();
        $this->validator($posts)->validate();

        try {
            // Get Payment Config
            $apikey = config('services.hitpay.key');
            $isStg = config('services.hitpay.environment');
            $customerfullname = $posts['first_name'] .' '. $posts['last_name'];
            $selectedplan = $posts['plan'];
            $helper = new SystemHelper();
            $planInfo = $helper->getPlanInformation($selectedplan);
            $payment = new PaymentHelper($apikey, $isStg);
            $response = $payment->recurringRequestCreate(array(
                'plan_id'    =>  $planInfo['id'],
                'customer_email'  =>  $posts['email'],
                'customer_name'  =>  $customerfullname,
                'start_date'  =>  date("Y-m-d"),
                'redirect_url'  =>  url("payment-success"),
                'reference'  =>  time()
            ));

            if(!empty($response['status']) && $response['status'] == 'scheduled') {
                event(new Registered($user = $this->create($posts)));

                // Delete Any Existing Role
                DB::table('model_has_roles')->where('model_id', $user->id)->delete();
                
                // Assign Role To User
                $user->assignRole($user->role_id);

                // Commit And Redirected To Listing
                DB::commit();

                Auth::login($user);

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
                    'subject' => 'Account Confirmation Details!',
                    'message' => 'Welcome '. $customerfullname .',',
                    'extra_msg' => 'Please see details below:',
                    'plan' => $planInfo['label'],
                    'amount' => number_format($planInfo['amount']),
                    'paymentlink' => 'Please pay to continue use your account '. $response['url'] .' or disregard if already paid.',
                    'thank_msg' => 'Thank you!'
                );
                Mail::to($request->user())->send(new DigitalPaymentMail($details));

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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'plan' => ['required'],
            'company' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'company' => $data['company'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'role_id' => 2,
            'status' => 0,
            'password' => Hash::make($data['password'])
        ]);
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
