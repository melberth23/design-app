<?php
  
namespace App\Http\Middleware;
  
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payments;
use App\Models\UserVerify;
  
class IsVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $skiprules = [1,3];
        if(!in_array(Auth::user()->role_id, $skiprules)) {
            if (!Auth::user()->is_email_verified) {
                $id = Auth::user()->id;
                $userverify = UserVerify::where('user_id', $id)->first();
                auth()->logout();
                return redirect()->route('user.verify', ['token' => $userverify->token])
                        ->with('error', 'You need to confirm your account. We have sent you an activation code, please check your email.');
            } elseif(!Auth::user()->status) {
                auth()->logout();
                return redirect()->back()->with('error', 'Your account is deactivated. Please contact support!');
            }

            // Check if user didi not pay
            $paymentinfo = Payments::where('user_id', Auth::user()->id)->first();
            if(empty($paymentinfo)) {
                return redirect()->route('user.plan');
            }
        }
   
        return $next($request);
    }
}