<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Requests;
use App\Models\Payments;
use App\Models\Brand;
use App\Models\RequestAssets;
use App\Models\Admin\RequestTypes;
use App\Models\Comments;
use App\Models\CommentsAssets;
use App\Models\CommentNotification;
use App\Models\Reviews;
use App\Models\Dimensions;
use App\Models\StatusNotifications;
use App\Models\FileNotifications;
use App\Models\TempFile;
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

class RequestsController extends Controller
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
     * List requests 
     * @param Nill
     * @return Array $requests
     */
    public function index()
    {
        $userid = Auth::id();

        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            $brands = Brand::where('user_id', $userid)->count();
            $requests = Requests::where('user_id', $userid)->orderBy('created_at', 'DESC')->paginate(10);
            $queue = Requests::where('user_id', $userid)->where('status', 2)->count();
            $progress = Requests::where('user_id', $userid)->where('status', 3)->count();
            $review = Requests::where('user_id', $userid)->where('status', 4)->count();
            $completed = Requests::where('user_id', $userid)->where('status', 0)->count();

            return view('requests.index', ['brands' => $brands, 'requests' => $requests, 'queue' => $queue, 'progress' => $progress, 'review' => $review, 'completed' => $completed]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Queue requests 
     * @param Nill
     * @return Array $requests
     */
    public function queue()
    {
        $userid = Auth::id();

        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            $brands = Brand::where('user_id', $userid)->count();
            $all = Requests::where('user_id', $userid)->count();
            $requests = Requests::where('user_id', $userid)->where('status', 2)->orderByRaw('-priority DESC')->get();
            $progress = Requests::where('user_id', $userid)->where('status', 3)->count();
            $review = Requests::where('user_id', $userid)->where('status', 4)->count();
            $completed = Requests::where('user_id', $userid)->where('status', 0)->count();

            return view('requests.queue', ['brands' => $brands, 'all' => $all, 'requests' => $requests, 'progress' => $progress, 'review' => $review, 'completed' => $completed]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Progress requests 
     * @param Nill
     * @return Array $requests
     */
    public function progress()
    {
        $userid = Auth::id();

        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            $brands = Brand::where('user_id', $userid)->count();
            $all = Requests::where('user_id', $userid)->count();
            $requests = Requests::where('user_id', $userid)->where('status', 3)->orderBy('created_at', 'DESC')->paginate(10);
            $queue = Requests::where('user_id', $userid)->where('status', 2)->count();
            $review = Requests::where('user_id', $userid)->where('status', 4)->count();
            $completed = Requests::where('user_id', $userid)->where('status', 0)->count();

            return view('requests.progress', ['brands' => $brands, 'all' => $all, 'requests' => $requests, 'queue' => $queue, 'review' => $review, 'completed' => $completed]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Review requests 
     * @param Nill
     * @return Array $requests
     */
    public function review()
    {
        $userid = Auth::id();

        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            $brands = Brand::where('user_id', $userid)->count();
            $all = Requests::where('user_id', $userid)->count();
            $requests = Requests::where('user_id', $userid)->where('status', 4)->orderBy('created_at', 'DESC')->paginate(10);
            $queue = Requests::where('user_id', $userid)->where('status', 2)->count();
            $progress = Requests::where('user_id', $userid)->where('status', 3)->count();
            $completed = Requests::where('user_id', $userid)->where('status', 0)->count();

            return view('requests.review', ['brands' => $brands, 'all' => $all, 'requests' => $requests, 'queue' => $queue, 'progress' => $progress, 'completed' => $completed]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Delivered requests 
     * @param Nill
     * @return Array $requests
     */
    public function delivered()
    {
        $userid = Auth::id();

        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            $brands = Brand::where('user_id', $userid)->count();
            $all = Requests::where('user_id', $userid)->count();
            $requests = Requests::where('user_id', $userid)->where('status', 0)->orderBy('created_at', 'DESC')->paginate(10);
            $queue = Requests::where('user_id', $userid)->where('status', 2)->count();
            $progress = Requests::where('user_id', $userid)->where('status', 3)->count();
            $review = Requests::where('user_id', $userid)->where('status', 4)->count();

            return view('requests.delivered', ['brands' => $brands, 'all' => $all, 'requests' => $requests, 'queue' => $queue, 'progress' => $progress, 'review' => $review]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Draft requests 
     * @param Nill
     * @return Array $requests
     */
    public function draft()
    {
        $userid = Auth::id();

        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            $brands = Brand::where('user_id', $userid)->count();
            $requests = Requests::where('user_id', $userid)->where('status', 1)->orderBy('created_at', 'DESC')->paginate(10);
            return view('requests.draft', ['brands' => $brands, 'requests' => $requests]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * View single request
     * @param request
     * @return Single request
     */
    public function view(Requests $requests)
    {
        $userid = Auth::id();

        $designtype = RequestTypes::whereId($requests->design_type)->first();
        $brand = Brand::whereId($requests->brand_id)->first();

        // Get images
        $medias = RequestAssets::where('request_id', $requests->id)->where('type', 'media')->get();

        // get next and previous
        $previous = Requests::where('user_id', $userid)->where('id', '<', $requests->id)->max('id');
        $next = Requests::where('user_id', $userid)->where('id', '>', $requests->id)->min('id');

        $notifications = $this->getNotifications($requests->id, $userid);
        $filenotifications = $this->getFilesNotifications($requests->id, $userid);

        // Update all file notifications to read
        $this->updateStatusNotifications($requests->id, $userid);

        // Get url by role
        $backurl = route('request.index');
        if(auth()->user()->hasRole('Admin')) {
            $backurl = route('subscribers.view', ['subscriber' => $requests->user_id]);
        }
        if(auth()->user()->hasRole('Designer')) {
            $backurl = route('designer.customers', ['status' => 'all']);
        }

        // All designers
        $designers = User::where('role_id', 3)->get();

        return view('requests.view')->with([
            'backurl'  => $backurl,
            'requests'  => $requests,
            'previous' => $previous,
            'next' => $next,
            'brand' => $brand,
            'designtype' => $designtype,
            'medias' => $medias,
            'notifications'  => $notifications,
            'filenotifications'  => $filenotifications,
            'designers'  => $designers
        ]);
    }

    /**
     * Create Requests 
     * @param Nill
     * @return Array $requests
     */
    public function create()
    {
        $userid = Auth::id();
        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            $designtypes = RequestTypes::where('status', 1)->get();

            return view('requests.add', ['designtypes' => $designtypes]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Create Requests 
     * @param Nill
     * @return Array $requests
     */
    public function request_type($type)
    {
        $userid = Auth::id();
        // Get payment link if not yet paid
        if(auth()->user()->payments->status == 'active') {
            $brands = Brand::where('user_id', $userid)->get();
            $file_types = $this->helper->request_file_types();
            $designtype = RequestTypes::whereId($type)->first();
            $dimensions = Dimensions::where('request_type_id', $type)->where('status', 1)->get();

            $dimension_options = [];
            foreach($dimensions as $dimension) {
                if(!empty($dimension->parent_type)) {
                    if(!empty($dimension_options[$dimension->parent_type])) {
                        array_push($dimension_options[$dimension->parent_type], $dimension);
                    } else {
                        $dimension_options[$dimension->parent_type][] = $dimension;
                    }
                } else {
                    $dimension_options[] = $dimension;
                }
            }

            return view('requests.requesttype', ['brands' => $brands, 'designtype' => $designtype, 'types' => $file_types, 'dimensions' => $dimension_options]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Store Requests
     * @param Request $request
     * @return View requests
     */
    public function store(Request $request)
    {   
        $status = 2;
        if(!empty($request->save_draft)) {
            $status = 1;
        }

        $userid = $request->user()->id;

        // Validations
        $request->validate([
            'title'    => 'required',
            'design_type'     => 'required',
            'dimensions'     => 'required',
            'description'     => 'required',
            'reference_link'    => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'brand_id'     => 'required',
            'media.*' => 'required|mimes:jpg,png'
        ]);

        DB::beginTransaction();
        try {

            $file_type = !empty($request->file_type)?implode(',', $request->file_type):'';
            $adobe_type = !empty($request->adobe_type)?implode(',', $request->adobe_type):'';

            // Store Data
            $requests = Requests::create([
                'title'    => $request->title,
                'design_type'   => $request->design_type,
                'dimensions'    => $request->dimensions,
                'custom_dimension'  => $request->custom_dimension,
                'description'   => $request->description,
                'format'        => $file_type,
                'adobe_format'  => $adobe_type,
                'include_text'  => $request->include_text,
                'included_text_description'  => $request->included_text_description,
                'reference_link'  => $request->reference_link,
                'dimensions_additional_info'    => $request->dimensions_additional_info,
                'brand_id'      => $request->brand_id,
                'user_id'       => $userid,
                'status'        => $status,
                'priority'      => NULL
            ]);

            // Check upload medias
            $tempmedias = TempFile::where('module', 'media')->where('code', $request->tempfile_code)->get();
            if(!empty($tempmedias)) {
                foreach($tempmedias as $tempmedia) {
                    $assets = RequestAssets::create([
                        'filename' => $tempmedia->file,
                        'request_id' => $requests->id,
                        'type' => 'media'
                    ]);

                    // remove tempfile
                    TempFile::whereId($tempmedia->id)->delete();
                }
            }

            // Send admin notification
            $admins = User::where('role_id', 1)->get();
            if(!empty($admins)) {
                foreach($admins as $admin) {
                    // Send email for notification
                    $details = array(
                        'subject' => 'Request notification',
                        'fromemail' => 'hello@designsowl.com',
                        'fromname' => 'DesignsOwl',
                        'heading' => 'Hi there,',
                        'message' => 'New request '. $request->title .' added.',
                        'sub_message' => 'Please login using your login information to check. Thank you!',
                        'template' => 'status'
                    );
                    Mail::to($admin->email)->send(new DigitalMail($details));
                }
            }

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('request.index')->with('success','Requests Created Successfully.');

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
        $allowed = $this->helper->userActionRules($userid, 'request', $status);
        if($allowed['allowed']) {
            // Validation
            $validate = Validator::make([
                'request_id'   => $request_id,
                'status'    => $status
            ], [
                'request_id'   =>  'required|exists:requests,id',
                'status'    =>  'required|in:1,2,0',
            ]);

            // If Validations Fails
            if($validate->fails()){
                return redirect()->route('request.index')->with('error', $validate->errors()->first());
            }

            try {
                DB::beginTransaction();

                // get current status of the request
                $requestrow = Requests::whereId($request_id)->first();

                $checkstatus = $this->helper->checkLockedStatus($requestrow->status);
                if(!$checkstatus) {
                    // Update Status
                    Requests::whereId($request_id)->update(['status' => $status]);

                    if($status == 4) {
                        // Get User Information
                        $user = User::where('id', $requestrow->user_id)->first();
                        $customerfullname = $user->first_name .' '. $user->last_name;

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

                    // Commit And Redirect on index with Success Message
                    DB::commit();
                    return redirect()->route('request.index')->with('success','Requests Status Updated Successfully!');
                } else {
                    $statustext = $this->helper->statusLabel($requestrow->status);
                    return redirect()->back()->with('error', $requestrow->title .' is in '. $statustext .' status and you are not allowed to change it.');
                }
            } catch (\Throwable $th) {

                // Rollback & Return Error Message
                DB::rollBack();
                return redirect()->back()->with('error', $th->getMessage());
            }
        } else {
            DB::rollBack();
            return redirect()->back()->with('limiterror', 'Account limit: Your are not allowed to add more than '. $allowed['allowedrequest'] .' requests.');
        }
    }

    /**
     * Leave review and mark complete the request
     * @param Review information
     * @return Redirect
     * */
    public function leavereview(Request $request)
    {
        $userid = Auth::id();

        // Leave reviews but optional
        Reviews::create([
            'user_id' => $userid,
            'rate_designer' => $request->rate_designer,
            'com_designer' => $request->com_designer,
            'experience_to_designer' => $request->experience_to_designer,
            'work_again_option' => $request->work_again_option,
            'rate_platform' => $request->rate_platform,
            'experience_platform' => $request->experience_platform,
            'suggestion' => $request->suggestion,
            'recommend_option' => $request->recommend_option
        ]);

        $this->updateStatus($request->id, 0);

        return redirect()->route('request.index')->with('success','Requests Status Updated Successfully!');
    }

    /**
     * Edit Requests
     * @param Integer $requests
     * @return Collection $requests
     */
    public function edit(Requests $requests)
    {
        $userid = $requests->user_id;

        $designtypes = RequestTypes::get();
        $brands = Brand::where('user_id', $userid)->get();
        $dimensions = Dimensions::where('request_type_id', $requests->design_type)->get();

        // Get images
        $medias = RequestAssets::where('request_id', $requests->id)->where('type', 'media')->get();

        return view('requests.edit')->with([
            'requests'  => $requests,
            'brands' => $brands,
            'designtypes' => $designtypes,
            'medias' => $medias,
            'dimensions' => $dimensions
        ]);
    }

    /**
     * Edit Requests
     * @param Integer $requests
     * @return Collection $requests
     */
    public function comment(Requests $requests)
    {
        $userid = Auth::id();

        // get next and previous
        $previous = Requests::where('user_id', $userid)->where('id', '<', $requests->id)->max('id');
        $next = Requests::where('user_id', $userid)->where('id', '>', $requests->id)->min('id');

        // Get notifications
        $notifications = $this->getNotifications($requests->id, $userid);
        $filenotifications = $this->getFilesNotifications($requests->id, $userid);

        // Update all notifications to read
        $this->updateCommentNotifications($requests->id, $userid);

        $comments = Comments::where('request_id', $requests->id)->latest()->get();

        // Get url by role
        $backurl = route('request.index');
        if(auth()->user()->hasRole('Admin')) {
            $backurl = route('subscribers.view', ['subscriber' => $requests->user_id]);
        }
        if(auth()->user()->hasRole('Designer')) {
            $backurl = route('designer.customers', ['status' => 'all']);
        }

        // All designers
        $designers = User::where('role_id', 3)->get();

        return view('requests.comment')->with([
            'requests'  => $requests,
            'backurl'  => $backurl,
            'previous' => $previous,
            'next' => $next,
            'comments'  => $comments,
            'notifications' => $notifications,
            'filenotifications' => $filenotifications,
            'designers' => $designers
        ]);
    }

    /**
     * Edit Requests
     * @param Integer $requests
     * @return Collection $requests
     */
    public function files(Requests $requests)
    {
        $userid = Auth::id();

        // get next and previous
        $previous = Requests::where('user_id', $userid)->where('id', '<', $requests->id)->max('id');
        $next = Requests::where('user_id', $userid)->where('id', '>', $requests->id)->min('id');

        $media_ext = array('jpg', 'png', 'svg', 'jpeg', 'gif');
        $adobe_ext = array('psd', 'ai', 'indd', 'pdf');
        $review = Comments::where('request_id', $requests->id)->where('comment_type', 'review')->first();

        $reviewid = 0;
        if($review) {
            $reviewid = $review->id;
        }
        $media = CommentsAssets::where('comments_id', $reviewid)->whereIn('file_type', $media_ext)->get();
        $dobe = CommentsAssets::where('comments_id', $reviewid)->whereIn('file_type', $adobe_ext)->get();

        // Get all manually uploaded files
        $manuals = Comments::where('request_id', $requests->id)->where('comment_type', 'manual')->get();
        $manual_media = [];
        $manual_dobe = [];
        if(!empty($manuals)) {
            $manualids = [];
            foreach($manuals as $manual) {
                $manualids[] = $manual->id;
            }
            $manual_media = CommentsAssets::whereIn('comments_id', $manualids)->whereIn('file_type', $media_ext)->get();
            $manual_dobe = CommentsAssets::whereIn('comments_id', $manualids)->whereIn('file_type', $adobe_ext)->get();
        }

        // Get notifications
        $notifications = $this->getNotifications($requests->id, $userid);
        $filenotifications = $this->getFilesNotifications($requests->id, $userid);

        // Update all file notifications to read
        $this->updateFilesNotifications($requests->id, $userid);

        // Get url by role
        $backurl = route('request.index');
        if(auth()->user()->hasRole('Admin')) {
            $backurl = route('subscribers.view', ['subscriber' => $requests->user_id]);
        }
        if(auth()->user()->hasRole('Designer')) {
            $backurl = route('designer.customers', ['status' => 'all']);
        }

        // All designers
        $designers = User::where('role_id', 3)->get();

        return view('requests.files')->with([
            'requests'  => $requests,
            'notifications'  => $notifications,
            'filenotifications'  => $filenotifications,
            'backurl' => $backurl,
            'previous' => $previous,
            'next' => $next,
            'medias'  => $media,
            'adobes'  => $dobe,
            'manualmedias'  => $manual_media,
            'manualadobes'  => $manual_dobe,
            'designers'  => $designers
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

            $notificationAdminEmail = false;
            $notificationUserEmail = false;
            $notificationDesignerEmail = false;
            // For admin
            if(auth()->user()->hasRole('Admin')) {
                $notificationUserEmail = true;
                $notificationDesignerEmail = true;
            }

            // For Owner
            if(auth()->user()->hasRole('User')) {
                $notificationAdminEmail = true;
                $notificationDesignerEmail = true;
            }

            // For Designer
            if(auth()->user()->hasRole('Designer')) {
                $notificationAdminEmail = true;
                $notificationUserEmail = true;
            }

            // Save notification for designer
            if($notificationDesignerEmail && !empty($requests->designer_id)) {
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
                    'template' => 'status'
                );
                Mail::to($designer_info->email)->send(new DigitalMail($details));
            }


            // Save notification for owner
            if($notificationUserEmail && !empty($requests->user_id)) {
                $owner_info = User::whereId($requests->user_id)->first();
                CommentNotification::create([
                    'comment_id'       => $comment->id,
                    'user_id'       => $requests->user_id,
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

            // Save notification for admin
            $admins = User::where('role_id', 1)->get();
            if($notificationAdminEmail && !empty($admins)) {
                foreach($admins as $admin) {
                    CommentNotification::create([
                        'comment_id'       => $comment->id,
                        'user_id'       => $admin->id,
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
                        'template' => 'status'
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
     * Update Requests
     * @param Request $request, Requests $requests
     * @return View requests
     */
    public function update(Request $request, Requests $requests)
    {

        $userid = $request->user()->id;

        // Validations
        $request->validate([
            'title'    => 'required',
            'design_type'     => 'required',
            'dimensions'     => 'required',
            'description'     => 'required',
            'reference_link'    => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'brand_id'     => 'required',
            'media.*' => 'mimes:jpg,png'
        ]);
        
        DB::beginTransaction();
        try {


            $file_type = !empty($request->file_type)?implode(',', $request->file_type):'';
            $adobe_type = !empty($request->adobe_type)?implode(',', $request->adobe_type):'';

            // Store Data
            $requests_updated = Requests::whereId($requests->id)->update([
                'title'    => $request->title,
                'design_type'   => $request->design_type,
                'dimensions'    => $request->dimensions,
                'custom_dimension'  => $request->custom_dimension,
                'description'   => $request->description,
                'format'        => $file_type,
                'adobe_format'  => $adobe_type,
                'include_text'  => $request->include_text,
                'included_text_description'  => $request->included_text_description,
                'reference_link'  => $request->reference_link,
                'dimensions_additional_info'    => $request->dimensions_additional_info,
                'brand_id'      => $request->brand_id,
            ]);

            // Check upload medias
            $tempmedias = TempFile::where('module', 'media')->where('code', $request->tempfile_code)->get();
            if(!empty($tempmedias)) {
                foreach($tempmedias as $tempmedia) {
                    $assets = RequestAssets::create([
                        'filename' => $tempmedia->file,
                        'request_id' => $requests->id,
                        'type' => 'media'
                    ]);

                    // remove tempfile
                    TempFile::whereId($tempmedia->id)->delete();
                }
            }

            // Commit And Redirected To Listing
            DB::commit();
            $redirecturl = redirect()->route('request.index')->with('success','Requests Updated Successfully.');
            if(auth()->user()->hasRole('Admin')) {
                $redirecturl = redirect()->route('subscribers.view', ['subscriber' => $requests->user_id])->with('success','Requests Updated Successfully.');
            }
            return $redirecturl;

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
    public function delete(Request $request)
    {

        $data = $request->all();

        DB::beginTransaction();
        try {
            // Delete Requests
            Requests::whereId($data['id'])->delete();

            DB::commit();
            return redirect()->route('request.index')->with('success', 'Requests Deleted Successfully!.');

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

    public function searchRequests(Request $request) {
        $permitted = false;
        if(auth()->user()->hasRole('User')) {
            $userid = Auth::id();
            // Get payment link if not yet paid
            if(auth()->user()->payments->status == 'active') {
                $permitted = true;
            }
        } else {
            $permitted = true;
        }

        if($permitted) {
            $keyword = $request->keyword;

            $statuses = [0,1,2,3,4];
            if(auth()->user()->hasRole('Designer')) {
                $statuses = [0,2,3,4];
            }

            $requests = Requests::whereIn('status', $statuses);
            if(auth()->user()->hasRole('User')) {
                $requests->where('user_id', $userid);
            }
            if( !empty($keyword) ) {
                $requests->where('title', 'like', '%'. $keyword .'%');
            }

            $data = $requests->paginate(10);

            return view('requests.search', ['requests' => $data, 'keyword' => $keyword]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function getNotifications($request_id, $userid)
    {
        $notifications = CommentNotification::whereHas('comment', function($query) use($request_id) {
            $query->where('request_id', $request_id);  
        })->where('user_id', $userid)->where('read', 0)->get();  

        return $notifications;
    }

    public function getFilesNotifications($request_id, $userid)
    {
        $notifications = FileNotifications::where('request_id', $request_id)->where('user_id', $userid)->where('read', 0)->get();

        return $notifications;
    }

    public function updateCommentNotifications($request_id, $userid)
    {
        CommentNotification::whereHas('comment', function($query) use($request_id) {
            $query->where('request_id', $request_id);  
        })->where('user_id', $userid)->where('read', 0)->update(['read' => 1]);
    }

    public function updateStatusNotifications($request_id, $userid)
    {
        StatusNotifications::where('request_id', $request_id)->where('user_id', $userid)->where('read', 0)->update(['read' => 1]);
    }

    public function updateFilesNotifications($request_id, $userid)
    {
        FileNotifications::where('request_id', $request_id)->where('user_id', $userid)->where('read', 0)->update(['read' => 1]);
    }

    public function getDimensions(Request $request)
    {
        $dimensions = Dimensions::where('request_type_id', $request->design_type)->get();

        $options = '';
        foreach($dimensions as $dimension) {
            $options .= '<option value="'. $dimension->label .'">'. $dimension->label .'</option>';
        }
        $options .= '<option value="custom">Custom</option>';

        return response()->json(array('dimensions'=> $options), 200);
    }

    public function fileupload(Request $request)
    {
        $userid = $request->user()->id;
        $requests = Requests::whereId($request->id)->first();

        // Store Data
        $comment = Comments::create([
            'comments'       => $request->user()->first_name .' added files. Please check.',
            'user_id'       => $userid,
            'request_id'    => $request->id,
            'comment_type'  => 'manual'
        ]);

        $tempmedias = TempFile::where('module', 'comment_media')->where('code', $request->tempfile_code)->get();
        if(!empty($tempmedias)) {
            foreach($tempmedias as $tempmedia) {
                $assets = CommentsAssets::create([
                    'filename' => $tempmedia->file,
                    'comments_id' => $comment->id,
                    'type' => 'manual',
                    'file_type' => $tempmedia->file_type
                ]);

                // remove tempfile
                TempFile::whereId($tempmedia->id)->delete();
            }
        }

        $tempdocuments = TempFile::where('module', 'comment_document')->where('code', $request->tempfile_code)->get();
        if(!empty($tempdocuments)) {
            foreach($tempdocuments as $tempdocument) {
                $assets = CommentsAssets::create([
                    'filename' => $tempdocument->file,
                    'comments_id' => $comment->id,
                    'type' => 'manual',
                    'file_type' => $tempdocument->file_type
                ]);

                // remove tempfile
                TempFile::whereId($tempdocument->id)->delete();
            }
        }

        // Save Notification to User
        FileNotifications::create([
            'request_id' => $request->id,
            'user_id' => $requests->user_id
        ]);

        return redirect()->back()->with('success', 'Uploaded new files');
    }

    public function sort(Request $request)
    {
        $sortkeys = $request->sortkeys;
        foreach($sortkeys as $sort => $id) {
            $id = intval($id);
            Requests::where('id', $id)->update(['priority' => $sort]);
        }

        return response()->json(array('sortkeys'=> $sortkeys), 200);
    }
}
