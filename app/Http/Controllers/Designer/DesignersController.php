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
    public function index()
    {
        $userid = Auth::id();

        $requests = Requests::where('status', '!=', 1)->orderBy('created_at', 'DESC')->paginate(10);
        $queue = Requests::where('status',2)->count();
        $progress = Requests::where('designer_id', $userid)->where('status', 3)->count();
        $review = Requests::where('designer_id', $userid)->where('status', 4)->count();
        $completed = Requests::where('designer_id', $userid)->where('status', 0)->count();

        return view('designer.index', ['requests' => $requests, 'queue' => $queue, 'progress' => $progress, 'review' => $review, 'completed' => $completed]);
    }
    
    /**
     * Queue requests 
     * @param Nill
     * @return Array $requests
     */
    public function queue()
    {
        $userid = Auth::id();

        $requests = Requests::where('status', 2)->orderBy('updated_at', 'DESC')->paginate(10);
        $allrequests = Requests::where('status', '!=', 1)->orderBy('user_id', 'ASC')->count();
        $progress = Requests::where('designer_id', $userid)->where('status', 3)->count();
        $review = Requests::where('designer_id', $userid)->where('status', 4)->count();
        $completed = Requests::where('designer_id', $userid)->where('status', 0)->count();

        return view('designer.queue', ['requests' => $requests, 'all' => $allrequests, 'progress' => $progress, 'review' => $review, 'completed' => $completed]);
    }

    /**
     * Progress requests 
     * @param Nill
     * @return Array $requests
     */
    public function progress()
    {
        $userid = Auth::id();

        $requests = Requests::where('designer_id', $userid)->where('status', 3)->orderBy('updated_at', 'DESC')->paginate(10);
        $allrequests = Requests::where('status', '!=', 1)->orderBy('user_id', 'ASC')->count();
        $queue = Requests::where('status',2)->count();
        $review = Requests::where('designer_id', $userid)->where('status', 4)->count();
        $completed = Requests::where('designer_id', $userid)->where('status', 0)->count();
        return view('designer.progress', ['requests' => $requests, 'all' => $allrequests, 'queue' => $queue, 'review' => $review, 'completed' => $completed]);
    }

    /**
     * Review requests 
     * @param Nill
     * @return Array $requests
     */
    public function review()
    {
        $userid = Auth::id();

        $requests = Requests::where('designer_id', $userid)->where('status', 4)->orderBy('updated_at', 'DESC')->paginate(10);
        $allrequests = Requests::where('status', '!=', 1)->orderBy('user_id', 'ASC')->count();
        $queue = Requests::where('status',2)->count();
        $progress = Requests::where('designer_id', $userid)->where('status', 3)->count();
        $completed = Requests::where('designer_id', $userid)->where('status', 0)->count();

        return view('designer.review', ['requests' => $requests, 'all' => $allrequests, 'queue' => $queue, 'progress' => $progress, 'completed' => $completed]);
    }

    /**
     * Delivered requests 
     * @param Nill
     * @return Array $requests
     */
    public function delivered()
    {
        $userid = Auth::id();

        $requests = Requests::where('designer_id', $userid)->where('status', 0)->orderBy('updated_at', 'DESC')->paginate(10);
        $allrequests = Requests::where('status', '!=', 1)->orderBy('user_id', 'ASC')->count();
        $queue = Requests::where('status',2)->count();
        $progress = Requests::where('designer_id', $userid)->where('status', 3)->count();
        $review = Requests::where('designer_id', $userid)->where('status', 4)->count();

        return view('designer.delivered', ['requests' => $requests, 'all' => $allrequests, 'queue' => $queue, 'progress' => $progress, 'review' => $review]);
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

                $commentspath = public_path('storage/comments') .'/'. $userid;
                if(!File::isDirectory($commentspath)){
                    // Create Path
                    File::makeDirectory($commentspath, 0777, true, true);
                }

                $commentfiles = $request->file('attachments');
                foreach($commentfiles as $commentfile) {
                    $filename = $commentfile->getClientOriginalName();
                    $extension = $commentfile->getClientOriginalExtension();
                    $randomfilename = $this->helper->generateRandomString(15);
                    $attachmentpath = $randomfilename .'.'. $extension;
                    $commentfile->move($commentspath, $attachmentpath);

                    $assets = CommentsAssets::create([
                        'filename' => $attachmentpath,
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
            return redirect()->route('designer.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            $request = Requests::whereId($request_id)->first();

            $data = ['status' => $status];
            if($status == 3) {
                $data['designer_id'] = $userid;
            }

            // Update Status
            Requests::whereId($request_id)->update($data);

            // Get User Information
            $user = User::where('id', $request->user_id)->first();
            $customerfullname = $user->first_name .' '. $user->last_name;

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
            return redirect()->route('designer.index')->with('success','Requests Status Updated Successfully!');
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
