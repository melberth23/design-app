<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Lib\SystemHelper;

class SubscribersController extends Controller
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
        $this->middleware('role:Admin');

        $this->helper = new SystemHelper();
    }

    /**
     * List of payments 
     * @param Nill
     * @return Array $payments
     */
    public function index()
    {
        $subscribers = User::select('users.id as uid', 'users.first_name', 'users.last_name', 'users.mobile_number', 'users.email', 'users.address_1', 'users.address_2', 'users.city', 'users.state', 'users.zip', 'users.country', 'payments.plan', 'payments.status as ustatus')->leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.status', 'active')->paginate(10);
        $users = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->count();
        $basic = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'basic')->count();
        $premium = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'premium')->count();
        $royal = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'royal')->count();

        return view('admin.subscribers.index', ['subscribers' => $subscribers, 'users' => $users, 'basic' => $basic, 'premium' => $premium, 'royal' => $royal]);
    }

    public function view(User $subscriber)
    {
        $subscriber = User::whereId($subscriber->id)->first();
        return view('admin.subscribers.view', ['subscriber' => $subscriber]);
    }

    public function basic()
    {
        $subscribers = User::select('users.id as uid', 'users.first_name', 'users.last_name', 'users.mobile_number', 'users.email', 'users.address_1', 'users.address_2', 'users.city', 'users.state', 'users.zip', 'users.country', 'payments.plan', 'payments.status as ustatus')->leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'basic')->paginate(10);
        $users = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->count();
        $basic = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'basic')->count();
        $premium = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'premium')->count();
        $royal = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'royal')->count();

        return view('admin.subscribers.index', ['subscribers' => $subscribers, 'users' => $users, 'basic' => $basic, 'premium' => $premium, 'royal' => $royal]);
    }

    public function premium()
    {
        $subscribers = User::select('users.id as uid', 'users.first_name', 'users.last_name', 'users.mobile_number', 'users.email', 'users.address_1', 'users.address_2', 'users.city', 'users.state', 'users.zip', 'users.country', 'payments.plan', 'payments.status as ustatus')->leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'premium')->paginate(10);
        $users = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->count();
        $basic = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'basic')->count();
        $premium = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'premium')->count();
        $royal = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'royal')->count();

        return view('admin.subscribers.index', ['subscribers' => $subscribers, 'users' => $users, 'basic' => $basic, 'premium' => $premium, 'royal' => $royal]);
    }

    public function enterprise()
    {
        $subscribers = User::select('users.id as uid', 'users.first_name', 'users.last_name', 'users.mobile_number', 'users.email', 'users.address_1', 'users.address_2', 'users.city', 'users.state', 'users.zip', 'users.country', 'payments.plan', 'payments.status as ustatus')->leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'royal')->paginate(10);
        $users = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->count();
        $basic = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'basic')->count();
        $premium = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'premium')->count();
        $royal = User::leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'royal')->count();

        return view('admin.subscribers.index', ['subscribers' => $subscribers, 'users' => $users, 'basic' => $basic, 'premium' => $premium, 'royal' => $royal]);
    }
}