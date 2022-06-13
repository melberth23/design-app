<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lib\PaymentHelper;
use App\Models\Payments;
use App\Models\NewAttempt;
use App\Mail\DigitalMail;
use App\Lib\SystemHelper;
use App\Models\User;
use Redirect;

class PaymentsController extends Controller
{
    public function payment(Request $request) {
        $payments = $request->all();
        if(!empty($payments['reference']) && $payments['status'] == 'active') {
            // Update payment by reference
            $datetoday = date('Y-m-d');
            $current_date = strtotime($datetoday);
            $next_recurring_date = date("Y-m-d", strtotime("+1 month", $current_date));
            Payments::where('reference', $payments['reference'])->update(array('type' => $payments['type'], 'recurring_date' => $next_recurring_date, 'status' => $payments['status']));

            // Get payments
            $rowpayment = Payments::where('reference', $payments['reference'])->first();
            $helper = new SystemHelper();
            $planInfo = $helper->getPlanInformation($rowpayment->plan);

            // Get User Information
            $user = User::where('id', $rowpayment->user_id)->first();
            $customerfullname = $user->first_name .' '. $user->last_name;

            // Update user status to active
            DB::table('users')
                ->where('id', $user->id)
                ->update(['status' => 1]);

            // Send confirmation email
            $details = array(
                'subject' => 'Account fully setup!',
                'message' => 'Greetings '. $customerfullname .' and welcome!',
                'extra_msg' => 'Please refer your plan information below:',
                'plan' => $planInfo['label'],
                'amount' => number_format($planInfo['amount']),
                'paymentlink' => '',
                'thank_msg' => 'Please login using your login information to proceed. Thank you!',
                'template' => 'payment'
            );
            Mail::to($user->email)->send(new DigitalMail($details));
        }

        return redirect()->route('dashboard');
    }

    public function changepayment(Request $request)
    {
        $payments = $request->all();
        if(!empty($payments['reference']) && $payments['status'] == 'active') {
            $rowpayment = NewAttempt::where('reference', $payments['reference'])->first();
            $user = User::whereId($rowpayment->user_id)->first();

            // Cancel old subscription
            // Get Payment Config
            $apikey = config('services.hitpay.key');
            $isStg = config('services.hitpay.environment');
            $paymentApi = new PaymentHelper($apikey, $isStg);
            $responseApi = $paymentApi->recurringDeleteAccount($user->payments->reference);
            if(!empty($responseApi['status']) && $responseApi['status'] == 'canceled') {
                $customerfullname = $user->first_name .' '. $user->last_name;

                $datetoday = date('Y-m-d', strtotime($user->payments->recurring_date));
                // $current_date = strtotime($datetoday);
                // $next_recurring_date = date("Y-m-d", strtotime("+1 month", $current_date));

                // Cancel old payment
                Payments::whereId($user->payments->id)->update(['status' => 'cancelled']);

                $helper = new SystemHelper();
                $planInfo = $helper->getPlanInformation($rowpayment->plan);

                // Save new payment
                $payment = Payments::create([
                    'user_id' => $user->id,
                    'reference' => $rowpayment->reference,
                    'business_recurring_plans_id' => $rowpayment->business_recurring_plans_id,
                    'plan' => $rowpayment->plan,
                    'cycle' => $rowpayment->cycle,
                    'currency' => $rowpayment->currency,
                    'price' => $rowpayment->price,
                    'status' => $payments['status'],
                    'payment_methods' => $rowpayment->payment_methods,
                    'payment_url' => $rowpayment->payment_url,
                    'type' => $payments['type']
                ]);
                Payments::whereId($payment->id)->update(['recurring_date' => $datetoday]);

                // Send confirmation email
                $details = array(
                    'subject' => 'New card added!',
                    'message' => 'Greetings '. $customerfullname,
                    'extra_msg' => 'Please refer your plan information below:',
                    'plan' => $planInfo['label'],
                    'amount' => number_format($planInfo['amount']),
                    'paymentlink' => '',
                    'thank_msg' => 'Please login using your login information to check your new payment method setup. Thank you!',
                    'template' => 'payment'
                );
                Mail::to($user->email)->send(new DigitalMail($details));

                #Commit Transaction
                DB::commit();

                $request->session()->flash('success', 'Card information updated successfully!');
            }
        }

        return redirect()->route('profile.paymentmethods');
    }
}
