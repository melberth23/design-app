<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sedResetPasswordEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
 
        $status = Password::sendResetLink(
            $request->only('email')
        );

        $message = 'An email has been sent to '. $request->email .'. You\'ll receive an instruction on how to reset a new password.';
     
        return $status === Password::RESET_LINK_SENT
                    ? back()->with('success', __($message))
                    : back()->withErrors(['email' => __($status)]);
    }
}
