<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payments;
use App\Mail\DigitalMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Lib\SystemHelper;

class PaymentsController extends Controller
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
     * List of payments 
     * @param Nill
     * @return Array $payments
     */
    public function index()
    {
        $payments = Payments::paginate(10);
        return view('admin.payments.index', ['payments' => $payments]);
    }

    /**
     * List pending of payments 
     * @param Nill
     * @return Array $payments
     */
    public function pending()
    {
        $payments = Payments::where('status', 'scheduled')->paginate(10);
        return view('admin.payments.pending', ['payments' => $payments]);
    }

    /**
     * List completed of payments 
     * @param Nill
     * @return Array $payments
     */
    public function completed()
    {
        $payments = Payments::where('status', 'active')->paginate(10);
        return view('admin.payments.completed', ['payments' => $payments]);
    }
}