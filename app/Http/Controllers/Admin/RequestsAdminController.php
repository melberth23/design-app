<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Requests;
use App\Models\Payments;
use App\Models\Brand;
use App\Models\RequestAssets;
use App\Models\Admin\RequestTypes;
use App\Models\Comments;
use App\Models\CommentsAssets;
use App\Models\CommentNotification;
use App\Mail\DigitalMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Lib\SystemHelper;
use File;

class RequestsAdminController extends Controller
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
     * List requests 
     * @param Nill
     * @return Array $requests
     */
    public function index($customer_id)
    {
        $customer = User::whereId($customer_id)->first();
        $brands = Brand::count();
        $requests = Requests::where('user_id', $customer_id)->orderBy('created_at', 'DESC')->paginate(10);
        $queue = Requests::where('user_id', $customer_id)->where('status', 2)->count();
        $progress = Requests::where('user_id', $customer_id)->where('status', 3)->count();
        $review = Requests::where('user_id', $customer_id)->where('status', 4)->count();
        $completed = Requests::where('user_id', $customer_id)->where('status', 0)->count();

        return view('admin.requests.index', ['customer' => $customer, 'brands' => $brands, 'requests' => $requests, 'queue' => $queue, 'progress' => $progress, 'review' => $review, 'completed' => $completed]);
    }

    /**
     * Queue requests 
     * @param Nill
     * @return Array $requests
     */
    public function queue($customer_id)
    {
        $customer = User::whereId($customer_id)->first();
        $brands = Brand::count();
        $all = Requests::where('user_id', $customer_id)->count();
        $requests = Requests::where('user_id', $customer_id)->where('status', 2)->orderByRaw('-priority DESC')->paginate(10);
        $progress = Requests::where('user_id', $customer_id)->where('status', 3)->count();
        $review = Requests::where('user_id', $customer_id)->where('status', 4)->count();
        $completed = Requests::where('user_id', $customer_id)->where('status', 0)->count();

        return view('admin.requests.queue', ['customer' => $customer, 'brands' => $brands, 'all' => $all, 'requests' => $requests, 'progress' => $progress, 'review' => $review, 'completed' => $completed]);
    }

    /**
     * Progress requests 
     * @param Nill
     * @return Array $requests
     */
    public function progress($customer_id)
    {
        $customer = User::whereId($customer_id)->first();
        $brands = Brand::count();
        $all = Requests::where('user_id', $customer_id)->count();
        $requests = Requests::where('user_id', $customer_id)->where('status', 3)->orderBy('created_at', 'DESC')->paginate(10);
        $queue = Requests::where('user_id', $customer_id)->where('status', 2)->count();
        $review = Requests::where('user_id', $customer_id)->where('status', 4)->count();
        $completed = Requests::where('user_id', $customer_id)->where('status', 0)->count();
        
        return view('admin.requests.progress', ['customer' => $customer, 'brands' => $brands, 'all' => $all, 'requests' => $requests, 'queue' => $queue, 'review' => $review, 'completed' => $completed]);
    }

    /**
     * Review requests 
     * @param Nill
     * @return Array $requests
     */
    public function review($customer_id)
    {
        $customer = User::whereId($customer_id)->first();
        $brands = Brand::count();
        $all = Requests::where('user_id', $customer_id)->count();
        $requests = Requests::where('user_id', $customer_id)->where('status', 4)->orderBy('created_at', 'DESC')->paginate(10);
        $queue = Requests::where('user_id', $customer_id)->where('status', 2)->count();
        $progress = Requests::where('user_id', $customer_id)->where('status', 3)->count();
        $completed = Requests::where('user_id', $customer_id)->where('status', 0)->count();

        return view('admin.requests.review', ['customer' => $customer, 'brands' => $brands, 'all' => $all, 'requests' => $requests, 'queue' => $queue, 'progress' => $progress, 'completed' => $completed]);
    }

    /**
     * Delivered requests 
     * @param Nill
     * @return Array $requests
     */
    public function delivered($customer_id)
    {
        $customer = User::whereId($customer_id)->first();
        $brands = Brand::count();
        $all = Requests::where('user_id', $customer_id)->count();
        $requests = Requests::where('user_id', $customer_id)->where('status', 0)->orderBy('created_at', 'DESC')->paginate(10);
        $queue = Requests::where('user_id', $customer_id)->where('status', 2)->count();
        $progress = Requests::where('user_id', $customer_id)->where('status', 3)->count();
        $review = Requests::where('user_id', $customer_id)->where('status', 4)->count();

        return view('admin.requests.delivered', ['customer' => $customer, 'brands' => $brands, 'all' => $all, 'requests' => $requests, 'queue' => $queue, 'progress' => $progress, 'review' => $review]);
    }

    /**
     * View single request
     * @param request
     * @return Single request
     */
    public function view(Requests $requests)
    {
        $designtype = RequestTypes::whereId($requests->design_type)->first();
        $brand = Brand::whereId($requests->brand_id)->first();

        // Get images
        $medias = RequestAssets::where('request_id', $requests->id)->where('type', 'media')->get();

        return view('admin.requests.view')->with([
            'requests'  => $requests,
            'brand' => $brand,
            'designtype' => $designtype,
            'medias' => $medias
        ]);
    }

    /**
     * Update Status Of Requests
     * @param Integer $status
     * @return List Page With Success
     */
    public function updateStatus($request_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'request_id'   => $request_id,
            'status'    => $status
        ], [
            'request_id'   =>  'required|exists:requests,id',
            'status'    =>  'required|in:0,1,2,3,4',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('adminrequest.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // get current status of the request
            $requestrow = Requests::whereId($request_id)->first();

            $user = User::where('id', $requestrow->user_id)->first();
            $customerfullname = $user->first_name .' '. $user->last_name;

            $allowed = $this->helper->userActionRules($requestrow->user_id, 'request', $status);
            if($allowed['allowed']) {
            
                // Update Status
                Requests::whereId($request_id)->update(['status' => $status]);

                if($status == 1 || $status == 4) {
                    // Get User Information
                    $user = User::where('id', $requestrow->user_id)->first();
                    $customerfullname = $user->first_name .' '. $user->last_name;

                    // Send email
                    $details = array(
                        'subject' => 'Request status changed to '. $this->helper->statusLabel($status),
                        'fromemail' => 'hello@designsowl.com',
                        'fromname' => 'DesignsOwl',
                        'heading' => 'Hi '. $customerfullname,
                        'message' => 'Your request '. $requestrow->title .' status changed to '. $this->helper->statusLabel($status),
                        'sub_message' => 'Please login using your login information to check. Thank you!',
                        'template' => 'status'
                    );
                    Mail::to($user->email)->send(new DigitalMail($details));
                }

                // Commit And Redirect on index with Success Message
                DB::commit();
                return redirect()->route('subscribers.view', ['subscriber' => $requestrow->user_id])->with('success','Requests Status Updated Successfully!');
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
    
    /**
     * Edit Requests
     * @param Integer $requests
     * @return Collection $requests
     */
    public function comment(Requests $requests)
    {
        $comments = Comments::where('request_id', $requests->id)->get();
        return view('admin.requests.comment')->with([
            'requests'  => $requests,
            'comments'  => $comments
        ]);
    }

    public function addComment(Request $request) {
        $userid = $request->user()->id;

        // Validations
        $request->validate([
            'comment'    => 'required',
            'media.*' => 'required|mimes:jpg,png'
        ]);

        DB::beginTransaction();
        try {
            // Get Request Data
            $requests = Requests::whereId($request->id)->first();

            // Store Data
            $comment = Comments::create([
                'comments'       => $request->comment,
                'user_id'       => $userid,
                'request_id'    => $request->id
            ]);

            // Check upload attachments
            if($request->hasFile('attachments')) {
                $allowedMediasExtension = ['jpg','png'];
                $commentfiles = $request->file('attachments');
                foreach($commentfiles as $commentfile) {
                    $extension = $commentfile->getClientOriginalExtension();
                    $check = in_array($extension, $allowedMediasExtension);

                    if($check) { 
                        $randomfilename = $this->helper->generateRandomString(15);
                        $filename = $randomfilename .'.'. $extension;
                        $s3path = 'comments/'. $userid .'/'. $filename;
                        $path = Storage::disk('s3')->put($s3path, fopen($commentfile, 'r+'), 'public');
                        $imgpath = Storage::disk('s3')->url($s3path);

                        $assets = CommentsAssets::create([
                            'filename' => $s3path,
                            'comments_id' => $comment->id,
                            'type' => 'comment'
                        ]);
                    }
                }
            }

            // Save notification for owner
            if(!empty($requests->user_id)) {
                $owner_info = User::whereId($requests->user_id)->first();
                CommentNotification::create([
                    'comment_id'       => $comment->id,
                    'user_id'       => $owner_info->id,
                    'title'    => route('request.view', ['requests' => $requests->id])
                ]);

                // Send email for notification
                $details = array(
                    'subject' => 'Request notification',
                    'fromemail' => 'hello@designsowl.com',
                    'fromname' => 'DesignsOwl',
                    'heading' => 'Hi there,',
                    'message' => 'You have new notification.',
                    'sub_message' => 'Please login using your login information to check. Thank you!',
                    'template' => 'notification'
                );
                Mail::to($owner_info->email)->send(new DigitalMail($details));
            }

            // Save notification for designer
            if(!empty($requests->designer_id)) {
                $designer_info = User::whereId($requests->designer_id)->first();
                CommentNotification::create([
                    'comment_id'       => $comment->id,
                    'user_id'       => $designer_info->id,
                    'title'    => route('request.view', ['requests' => $requests->id])
                ]);

                // Send email for notification
                $details = array(
                    'subject' => 'Request notification',
                    'fromemail' => 'hello@designsowl.com',
                    'fromname' => 'DesignsOwl',
                    'heading' => 'Hi there,',
                    'message' => 'You have new notification.',
                    'sub_message' => 'Please login using your login information to check. Thank you!',
                    'template' => 'notification'
                );
                Mail::to($designer_info->email)->send(new DigitalMail($details));
            }

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->back()->with('success','Comment Submitted Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }
    
    /**
     * Delete Requests
     * @param Requests $requests
     * @return Index requests
     */
    public function delete(Requests $requests)
    {
        DB::beginTransaction();
        try {
            // Delete Requests
            Requests::whereId($requests->id)->delete();

            DB::commit();
            return redirect()->route('adminrequest.index')->with('success', 'Requests Deleted Successfully!.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete Requests Asset
     * @param Requests $requests
     * @return Index requests
     */
    public function deleteAsset(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $asset = RequestAssets::whereId($data['asset'])->first();

            // Delete Asset
            RequestAssets::whereId($asset->id)->delete();

            DB::commit();
            return response()->json(['success' => 'Request Asset Deleted Successfully!.']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}