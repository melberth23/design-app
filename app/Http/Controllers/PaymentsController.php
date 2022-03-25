<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Lib\PaymentHelper;
use App\Models\Payments;
use App\Mail\DigitalPaymentMail;
use App\Lib\SystemHelper;
use App\Models\User;
use Redirect;

class PaymentsController extends Controller
{
    public function payment(Request $request) {
        $payments = $request->all();
        if(!empty($payments['reference']) && $payments['status'] == 'active') {
            // Update payment by reference
            Payments::where('reference', $payments['reference'])->update(array('type' => $payments['type'], 'status' => $payments['status']));

            // Get payments
            $rowpayment = Payments::where('reference', $payments['reference'])->first();
            $helper = new SystemHelper();
            $planInfo = $helper->getPlanInformation($rowpayment->plan);

            // Get User Information
            $user = User::where('id', $rowpayment->user_id)->first();
            $customerfullname = $user->first_name .' '. $user->last_name;

            // Send confirmation email
            $details = array(
                'message' => 'Greetings '. $customerfullname .' and welcome!',
                'extra_msg' => 'Please refer your plan information below:',
                'plan' => $planInfo['label'],
                'amount' => number_format($planInfo['amount']),
                'paymentlink' => '',
                'thank_msg' => 'Thank you!'
            );
            Mail::to($user->email)->send(new DigitalPaymentMail($details));
        }

        return redirect()->route('dashboard');
    }
}
