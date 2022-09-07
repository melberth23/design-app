<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Requests;
use App\Models\Brand;
use App\Models\User;
use App\Models\Invoices;
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
    public function index($type=false, $sort=false)
    {
        $users = User::select('users.id as uid', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"), 'users.first_name', 'users.last_name', 'users.mobile_number', 'users.email', 'users.address_1', 'users.address_2', 'users.city', 'users.state', 'users.zip', 'users.country', 'payments.plan', 'payments.status as ustatus')->leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.status', 'active');

        if(!empty($type) && $type == 'date') {
            $users->orderByRaw('users.created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $users->orderBy('full_name', $sort);
        }

        $subscribers = $users->paginate(10);

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

        // Get requests
        $requests = Requests::where('user_id', $subscriber->id)->get();
        $queue = Requests::where('user_id', $subscriber->id)->where('status', 2)->get();
        $progress = Requests::where('user_id', $subscriber->id)->where('status', 3)->get();
        $review = Requests::where('user_id', $subscriber->id)->where('status', 4)->get();
        $completed = Requests::where('user_id', $subscriber->id)->where('status', 0)->get();

        // Get brand profiles
        $brands = Brand::where('user_id', $subscriber->id)->get();

        // Get invoices
        $invoices = Invoices::where('user_id', $subscriber->id)->get();

        return view('admin.subscribers.view', ['subscriber' => $subscriber, 'requests' => $requests, 'queue' => $queue, 'progress' => $progress, 'review' => $review, 'completed' => $completed, 'brands' => $brands, 'invoices' => $invoices]);
    }

    public function account(User $subscriber)
    {
        $subscriber = User::whereId($subscriber->id)->first();
        return view('admin.subscribers.account', ['subscriber' => $subscriber]);
    }

    public function basic($type=false, $sort=false)
    {
        $users = User::select('users.id as uid', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"), 'users.first_name', 'users.last_name', 'users.mobile_number', 'users.email', 'users.address_1', 'users.address_2', 'users.city', 'users.state', 'users.zip', 'users.country', 'payments.plan', 'payments.status as ustatus')->leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'basic');

        if(!empty($type) && $type == 'date') {
            $users->orderByRaw('users.created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $users->orderBy('full_name', $sort);
        }

        $subscribers = $users->paginate(10);

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

    public function premium($type=false, $sort=false)
    {
        $users = User::select('users.id as uid', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"), 'users.first_name', 'users.last_name', 'users.mobile_number', 'users.email', 'users.address_1', 'users.address_2', 'users.city', 'users.state', 'users.zip', 'users.country', 'payments.plan', 'payments.status as ustatus')->leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'premium');
        
        if(!empty($type) && $type == 'date') {
            $users->orderByRaw('users.created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $users->orderBy('full_name', $sort);
        }

        $subscribers = $users->paginate(10);

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

    public function enterprise($type=false, $sort=false)
    {
        $users = User::select('users.id as uid', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"), 'users.first_name', 'users.last_name', 'users.mobile_number', 'users.email', 'users.address_1', 'users.address_2', 'users.city', 'users.state', 'users.zip', 'users.country', 'payments.plan', 'payments.status as ustatus')->leftJoin('payments', function($join) {
                             $join->on('users.id', '=', 'payments.user_id');
                         })->where('users.role_id', 2)->where('users.status', 1)->where('payments.status', 'active')->where('payments.plan', 'royal');

        if(!empty($type) && $type == 'date') {
            $users->orderByRaw('users.created_at '. $sort);
        }
        if(!empty($type) && $type == 'name') {
            $users->orderBy('full_name', $sort);
        }

        $subscribers = $users->paginate(10);

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

    public function assignDesigner($request_id, $status, $designerid)
    {
        // Validation
        $validate = Validator::make([
            'request_id'   => $request_id,
            'status'    => $status
        ], [
            'request_id'   =>  'required|exists:requests,id',
            'status'    =>  'required|in:3,4',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->back()->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            $request = Requests::whereId($request_id)->first();
            $designer = User::where('id', $designerid)->first();

            // Get User Information
            $user = User::where('id', $request->user_id)->first();
            $customerfullname = $user->first_name .' '. $user->last_name;

            $allowed = $this->helper->userActionRules($request->user_id, 'request', $status);
            $passReq = false;
            if($allowed['allowed']) {
                $passReq = true;

                // Save Notification to User
                StatusNotifications::create([
                    'request_id' => $request_id,
                    'user_id' => $user->id,
                    'status' => $status
                ]);

                // Send email
                $details = array(
                    'subject' => 'Request status changed to '. $this->helper->statusLabel($status),
                    'fromemail' => 'hello@designsowl.com',
                    'fromname' => 'DesignsOwl',
                    'heading' => 'Hi '. $customerfullname,
                    'message' => 'Your request '. $request->title .' status changed to '. $this->helper->statusLabel($status),
                    'sub_message' => 'Please login using your login information to check. Thank you!',
                    'template' => 'status'
                );
                Mail::to($user->email)->send(new DigitalMail($details));
            }
            if($status == $request->status && $designerid != $request->designer_id) {
                $passReq = true;
            }


            if($passReq) {
                $data = ['status' => $status];
                if($status == 3) {
                    $data['designer_id'] = $designerid;
                }

                // Update Status
                Requests::whereId($request_id)->update($data);

                // Commit And Redirect on index with Success Message
                DB::commit();
                return redirect()->back()->with('success', 'This request assigned to '. $designer->first_name .' '. $designer->first_name);
            } else {
                // Rollback & Return Error Message
                DB::rollBack();
                return redirect()->back()->with('error', 'Account limit: '. $customerfullname .' has a limit of '. $allowed['allowedrequest'] .' requests to move in progress.');
            }
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}