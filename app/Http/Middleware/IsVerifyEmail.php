<?php
  
namespace App\Http\Middleware;
  
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payments;
  
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
        if (!Auth::user()->is_email_verified) {
            $email = Auth::user()->email;
            auth()->logout();
            return redirect()->route('user.verify', ['email' => $email])
                    ->with('message', 'You need to confirm your account. We have sent you an activation code, please check your email.');
        }

        // Check if user didi not pay
        $paymentinfo = Payments::where('user_id', Auth::user()->id)->first();
        if(empty($paymentinfo)) {
            return redirect()->route('user.plan')
                    ->with('message', 'You need to confirm your account. We have sent you an activation code, please check your email.');
        }
   
        return $next($request);
    }
}