<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Lib\PaymentHelper;
use App\Models\Payments;
use Redirect;

class PaymentsController extends Controller
{
    public function payment(Request $request) {
        $payments = $request->all();
        if(!empty($payments['reference']) && $payments['status'] == 'active') {
            Payments::where('reference', $payments['reference'])->update(array('type' => $payments['type'], 'status' => $payments['status']));
        }

        return redirect()->route('dashboard');
    }
}
