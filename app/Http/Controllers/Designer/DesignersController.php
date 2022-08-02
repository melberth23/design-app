<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Requests;
use App\Models\Payments;
use App\Models\Brand;
use App\Models\RequestAssets;
use App\Models\Comments;
use App\Models\CommentsAssets;
use App\Models\CommentNotification;
use App\Models\StatusNotifications;
use App\Models\FileNotifications;
use App\Models\Admin\RequestTypes;
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

class DesignersController extends Controller
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
        $this->middleware('role:Designer');

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
        if(!empty($customer) && $customer->count() > 0) {
            $userid = Auth::id();

            $requests = Requests::where('status', '!=', 1)->where('user_id', $customer_id)->orderBy('updated_at', 'DESC')->paginate(10);
            $queue = Requests::where('status',2)->where('user_id', $customer_id)->count();
            $progress = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 3)->count();
            $review = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 4)->count();
            $completed = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 0)->count();

            return view('designer.index', ['customer' => $customer, 'requests' => $requests, 'queue' => $queue, 'progress' => $progress, 'review' => $review, 'completed' => $completed]);
        } else {
            return redirect()->route('dashboard')->with('error', 'You are trying to access user that is not in the system!');
        }
    }

    /**
     * Queue requests 
     * @param Nill
     * @return Array $requests
     */
    public function queue($customer_id)
    {
        $customer = User::whereId($customer_id)->first();
        if(!empty($customer) && $customer->count() > 0) {
            $userid = Auth::id();

            $requests = Requests::where('status', 2)->where('user_id', $customer_id)->orderByRaw('-priority DESC')->paginate(10);
            $allrequests = Requests::where('status', '!=', 1)->where('user_id', $customer_id)->orderBy('user_id', 'ASC')->count();
            $progress = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 3)->count();
            $review = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 4)->count();
            $completed = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 0)->count();

            return view('designer.queue', ['customer' => $customer, 'requests' => $requests, 'all' => $allrequests, 'progress' => $progress, 'review' => $review, 'completed' => $completed]);
        } else {
            return redirect()->route('dashboard')->with('error', 'You are trying to access user that is not in the system!');
        }
    }

    /**
     * Progress requests 
     * @param Nill
     * @return Array $requests
     */
    public function progress($customer_id)
    {
        $customer = User::whereId($customer_id)->first();
        if(!empty($customer) && $customer->count() > 0) {
            $userid = Auth::id();

            $requests = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 3)->orderBy('updated_at', 'DESC')->paginate(10);
            $allrequests = Requests::where('status', '!=', 1)->where('user_id', $customer_id)->orderBy('user_id', 'ASC')->count();
            $queue = Requests::where('status',2)->where('user_id', $customer_id)->count();
            $review = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 4)->count();
            $completed = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 0)->count();

            return view('designer.progress', ['customer' => $customer, 'requests' => $requests, 'all' => $allrequests, 'queue' => $queue, 'review' => $review, 'completed' => $completed]);
        } else {
            return redirect()->route('dashboard')->with('error', 'You are trying to access user that is not in the system!');
        }
    }

    /**
     * Review requests 
     * @param Nill
     * @return Array $requests
     */
    public function review($customer_id)
    {
        $customer = User::whereId($customer_id)->first();
        if(!empty($customer) && $customer->count() > 0) {
            $userid = Auth::id();

            $requests = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 4)->orderBy('updated_at', 'DESC')->paginate(10);
            $allrequests = Requests::where('status', '!=', 1)->where('user_id', $customer_id)->orderBy('user_id', 'ASC')->count();
            $queue = Requests::where('status',2)->where('user_id', $customer_id)->count();
            $progress = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 3)->count();
            $completed = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 0)->count();

            return view('designer.review', ['customer' => $customer, 'requests' => $requests, 'all' => $allrequests, 'queue' => $queue, 'progress' => $progress, 'completed' => $completed]);
        } else {
            return redirect()->route('dashboard')->with('error', 'You are trying to access user that is not in the system!');
        }
    }

    /**
     * Delivered requests 
     * @param Nill
     * @return Array $requests
     */
    public function delivered($customer_id)
    {
        $customer = User::whereId($customer_id)->first();
        if(!empty($customer) && $customer->count() > 0) {
            $userid = Auth::id();

            $requests = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 0)->orderBy('updated_at', 'DESC')->paginate(10);
            $allrequests = Requests::where('status', '!=', 1)->where('user_id', $customer_id)->orderBy('user_id', 'ASC')->count();
            $queue = Requests::where('status',2)->where('user_id', $customer_id)->count();
            $progress = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 3)->count();
            $review = Requests::where('designer_id', $userid)->where('user_id', $customer_id)->where('status', 4)->count();

            return view('designer.delivered', ['customer' => $customer, 'requests' => $requests, 'all' => $allrequests, 'queue' => $queue, 'progress' => $progress, 'review' => $review]);
        } else {
            return redirect()->route('dashboard')->with('error', 'You are trying to access user that is not in the system!');
        }
    }

    /**
     * Customers requests 
     * @param $status
     * @return Array $customers
     */
    public function customerLists($status)
    {
        $userid = Auth::id();
        $userrequest = User::leftJoin('requests', function($join) {
                        $join->on('users.id', '=', 'requests.user_id');
                    });

        if($status == 'all') {
            $users = $userrequest->select('users.id as uid','users.first_name', 'users.last_name', 'requests.status as rstatus')->where('requests.user_id', '!=', 1)->groupBy('users.id')->paginate(10);
        } else {
            $users = $userrequest->select('users.id as uid','users.first_name', 'users.last_name', 'requests.status as rstatus')->where('requests.user_id', '!=', 1)->where('requests.status', $status)->groupBy('users.id')->paginate(10);
        }

        $requests = User::leftJoin('requests', function($join) {
                        $join->on('users.id', '=', 'requests.user_id');
                    })->where('requests.user_id', '!=', 1)->groupBy('users.id')->get();
        $queue = User::leftJoin('requests', function($join) {
                        $join->on('users.id', '=', 'requests.user_id');
                    })->where('requests.status', 2)->groupBy('users.id')->get();
        $progress = User::leftJoin('requests', function($join) {
                        $join->on('users.id', '=', 'requests.user_id');
                    })->where('requests.designer_id', $userid)->where('requests.status', 3)->groupBy('users.id')->get();
        $review = User::leftJoin('requests', function($join) {
                        $join->on('users.id', '=', 'requests.user_id');
                    })->where('requests.designer_id', $userid)->where('requests.status', 4)->groupBy('users.id')->get();
        $completed = User::leftJoin('requests', function($join) {
                        $join->on('users.id', '=', 'requests.user_id');
                    })->where('requests.designer_id', $userid)->where('requests.status', 0)->groupBy('users.id')->get();

        $templatebystatus = 'index';
        $labelStatus = 'All requests';
        if($status == 0) {
            $templatebystatus = 'delivered';
            $labelStatus = 'Delivered requests';
        } elseif($status == 2) {
            $templatebystatus = 'queue';
            $labelStatus = 'Queue requests';
        } elseif($status == 3) {
            $templatebystatus = 'progress';
            $labelStatus = 'Progress requests';
        } elseif($status == 4) {
            $templatebystatus = 'review';
            $labelStatus = 'Review requests';
        }

        return view('designer.customer-'. $templatebystatus, ['users' => $users, 'status' => $labelStatus, 'requests' => $requests, 'queue' => $queue, 'progress' => $progress, 'review' => $review, 'completed' => $completed]);
    }

    public function view(Requests $requests) {
        $userid = Auth::id();

        $designtype = RequestTypes::whereId($requests->design_type)->first();
        $brand = Brand::whereId($requests->brand_id)->first();

        // Get images
        $medias = RequestAssets::where('request_id', $requests->id)->where('type', 'media')->get();

        return view('designer.view')->with([
            'requests'  => $requests,
            'brand' => $brand,
            'designtype' => $designtype,
            'medias' => $medias
        ]);
    }

    public function comment(Requests $requests) {
        $comments = Comments::where('request_id', $requests->id)->get();
        return view('designer.comment')->with([
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
                $commentfiles = $request->file('attachments');
                foreach($commentfiles as $commentfile) {
                    $extension = $commentfile->getClientOriginalExtension();
                    $randomfilename = $this->helper->generateRandomString(15);
                    $filename = $randomfilename .'.'. $extension;
                    $s3path = 'comments/'. $userid .'/'. $filename;
                    $path = Storage::disk('s3')->put($s3path, fopen($commentfile, 'r+'), 'public');
                    $imgpath = Storage::disk('s3')->url($s3path);

                    $assets = CommentsAssets::create([
                        'filename' => $s3path,
                        'comments_id' => $comment->id,
                        'type' => 'comment',
                        'file_type' => $extension
                    ]);
                }
            }

            // Save notification for owner
            if(!empty($requests->user_id)) {
                $owner_info = User::whereId($requests->user_id)->first();
                CommentNotification::create([
                    'comment_id'       => $comment->id,
                    'user_id'       => $requests->user_id,
                    'title'    => route('request.view', ['requests' => $requests->id])
                ]);

                // Send email for notification
                $details = array(
                    'subject' => 'Request notification',
                    'heading' => 'Hi there,',
                    'message' => 'You have new notification.',
                    'sub_message' => 'Please login using your login information to check. Thank you!',
                    'template' => 'notification'
                );
                Mail::to($owner_info->email)->send(new DigitalMail($details));
            }

            // Save notification for admin
            $admins = User::where('role_id', 1)->get();
            if(!empty($admins)) {
                foreach($admins as $admin) {
                    CommentNotification::create([
                        'comment_id'       => $comment->id,
                        'user_id'       => $admin->id,
                        'title'    => route('request.view', ['requests' => $requests->id])
                    ]);

                    // Send email for notification
                    $details = array(
                        'subject' => 'Request notification',
                        'heading' => 'Hi there,',
                        'message' => 'You have new notification.',
                        'sub_message' => 'Please login using your login information to check. Thank you!',
                        'template' => 'notification'
                    );
                    Mail::to($admin->email)->send(new DigitalMail($details));
                }
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
     * Update Status Of Requests
     * @param Integer $status
     * @return List Page With Success
     */
    public function updateStatus($request_id, $status)
    {
        $userid = Auth::id();
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
            // Get User Information
            $user = User::where('id', $request->user_id)->first();
            $customerfullname = $user->first_name .' '. $user->last_name;

            $allowed = $this->helper->userActionRules($request->user_id, 'request', $status);
            if($allowed['allowed']) {
                $data = ['status' => $status];
                if($status == 3) {
                    $data['designer_id'] = $userid;
                }

                // Update Status
                Requests::whereId($request_id)->update($data);

                // Save Notification to User
                StatusNotifications::create([
                    'request_id' => $request_id,
                    'user_id' => $user->id,
                    'status' => $status
                ]);

                // Send email
                $details = array(
                    'subject' => 'Request status changed',
                    'heading' => 'Hi '. $customerfullname,
                    'message' => 'Your request '. $request->title .' status changed to '. $this->helper->statusLabel($status),
                    'sub_message' => 'Please login using your login information to check. Thank you!',
                    'template' => 'status'
                );
                Mail::to($user->email)->send(new DigitalMail($details));

                // Commit And Redirect on index with Success Message
                DB::commit();
                return redirect()->back()->with('success','Requests Status Updated Successfully!');
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
     * Update Status to review
     * @param Integer $status
     * @return List Page With Success
     */
    public function addFileReview(Request $request) {
        $userid = $request->user()->id;

        // Validations
        $request->validate([
            'media.*' => 'required|mimes:jpg,png',
            'documents.*' => 'required|mimes:psd,ai,doc,docx,pdf'
        ]);

        DB::beginTransaction();
        try {

            $request_data = Requests::whereId($request->id)->first();

            // Store Data
            $comment = Comments::create([
                'comments'       => $request->user()->first_name .' add files for review.',
                'user_id'       => $userid,
                'request_id'    => $request_data->id,
                'comment_type'  => 'review'
            ]);

            // Check upload media
            if($request->hasFile('media')) {

                $mediapath = public_path('storage/comments') .'/'. $userid;
                if(!File::isDirectory($mediapath)){
                    // Create Path
                    File::makeDirectory($mediapath, 0777, true, true);
                }

                $mediafiles = $request->file('media');
                foreach($mediafiles as $mediafile) {
                    $filename = $mediafile->getClientOriginalName();
                    $extension = $mediafile->getClientOriginalExtension();
                    $randomfilename = $this->helper->generateRandomString(15);
                    $attachmentpath = $randomfilename .'.'. $extension;
                    $mediafile->move($mediapath, $attachmentpath);

                    $assets = CommentsAssets::create([
                        'filename' => $attachmentpath,
                        'comments_id' => $comment->id,
                        'type' => 'review',
                        'file_type' => $extension
                    ]);
                }
            }

            // Check upload adobe
            if($request->hasFile('documents')) {

                $documentspath = public_path('storage/comments') .'/'. $userid;
                if(!File::isDirectory($documentspath)){
                    // Create Path
                    File::makeDirectory($documentspath, 0777, true, true);
                }

                $documentsfiles = $request->file('documents');
                foreach($documentsfiles as $documentsfile) {
                    $filename = $documentsfile->getClientOriginalName();
                    $extension = $documentsfile->getClientOriginalExtension();
                    $randomfilename = $this->helper->generateRandomString(15);
                    $attachmentpath = $randomfilename .'.'. $extension;
                    $documentsfile->move($documentspath, $attachmentpath);

                    $assets = CommentsAssets::create([
                        'filename' => $attachmentpath,
                        'comments_id' => $comment->id,
                        'type' => 'review',
                        'file_type' => $extension
                    ]);
                }
            }

            // Save notification for owner
            if(!empty($request_data->user_id)) {
                $owner_info = User::whereId($request_data->user_id)->first();
                CommentNotification::create([
                    'comment_id'       => $comment->id,
                    'user_id'       => $owner_info->id,
                    'title'    => route('request.view', ['requests' => $request_data->id])
                ]);

                // Save Notification to User
                StatusNotifications::create([
                    'request_id' => $request_data->id,
                    'user_id' => $owner_info->id,
                    'status' => 4
                ]);

                // Save Notification to User
                FileNotifications::create([
                    'request_id' => $request_data->id,
                    'user_id' => $owner_info->id
                ]);

                // Send email for notification
                $details = array(
                    'subject' => 'Request notification',
                    'heading' => 'Hi there,',
                    'message' => 'You have new notification.',
                    'sub_message' => 'Please login using your login information to check. Thank you!',
                    'template' => 'notification'
                );
                Mail::to($owner_info->email)->send(new DigitalMail($details));
            }

            // Save notification for admin
            $admins = User::where('role_id', 1)->get();
            if(!empty($admins)) {
                foreach($admins as $admin) {
                    CommentNotification::create([
                        'comment_id'       => $comment->id,
                        'user_id'       => $admin->id,
                        'title'    => route('request.view', ['requests' => $request_data->id])
                    ]);

                    // Save Notification to User
                    StatusNotifications::create([
                        'request_id' => $request_data->id,
                        'user_id' => $admin->id,
                        'status' => 4
                    ]);

                    // Save Notification to User
                    FileNotifications::create([
                        'request_id' => $request_data->id,
                        'user_id' => $admin->id
                    ]);

                    // Send email for notification
                    $details = array(
                        'subject' => 'Request notification',
                        'heading' => 'Hi there,',
                        'message' => 'You have new notification.',
                        'sub_message' => 'Please login using your login information to check. Thank you!',
                        'template' => 'notification'
                    );
                    Mail::to($admin->email)->send(new DigitalMail($details));
                }
            }

            // Update Status
            $data = ['status' => 4];
            Requests::whereId($request_data->id)->update($data);

            // Get User Information
            $user = User::where('id', $request_data->user_id)->first();
            $customerfullname = $user->first_name .' '. $user->last_name;

            // Send email
            $details = array(
                'subject' => 'Request status changed',
                'heading' => 'Hi '. $customerfullname,
                'message' => 'Your request '. $request->title .' status changed to '. $this->helper->statusLabel(4),
                'sub_message' => 'Please login using your login information to check. Thank you!',
                'template' => 'status'
            );
            Mail::to($user->email)->send(new DigitalMail($details));

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->back()->with('success','Request moved to review.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }
}
