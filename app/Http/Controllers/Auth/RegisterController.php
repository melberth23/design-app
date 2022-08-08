<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
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
use App\Rules\IsValidPassword;
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

    /**
    * Save user
    * @param array $data
    * @return Error and Success message
    */
    public function register(Request $request) {
        $posts = $request->all();
        $this->validator($posts)->validate();

        try {
            event(new Registered($user = $this->create($posts)));

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();

            // Assign Role To User
            $user->assignRole($user->role_id);

            $token = Str::random(64);
            $code = sprintf("%04d", mt_rand(1, 9999));
  
            UserVerify::create([
                'user_id' => $user->id, 
                'token' => $token,
                'code' => $code
            ]);

            $toname = $user->first_name;
      
            // Send Email
            $details = array(
                'toname' => $toname,
                'subject' => 'Account Email Verification',
                'token' => $token,
                'fromemail' => 'hello@designsowl.com',
                'fromname' => 'DesignsOwl',
                'code' => $code,
                'template' => 'emailverification',
            );
            Mail::to($user)->send(new DigitalMail($details));

            return redirect()->route('user.verify', array('token' => $token))->with('success', "Please check your email to verify and enter code");
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
            'first_name' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
            'last_name' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', new isValidPassword()],
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
}
