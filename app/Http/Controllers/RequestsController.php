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
        $paymentinfo = Payments::where('user_id', $userid)->first();
        if($paymentinfo->status == 'active') {
            $requests = Requests::where('user_id', $userid)->paginate(10);
            return view('requests.index', ['requests' => $requests]);
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
        $paymentinfo = Payments::where('user_id', $userid)->first();
        if($paymentinfo->status == 'active') {
            $requests = Requests::where('user_id', $userid)->whereIn('status', array(2, 3))->paginate(10);
            return view('requests.queue', ['requests' => $requests]);
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
        $paymentinfo = Payments::where('user_id', $userid)->first();
        if($paymentinfo->status == 'active') {
            $requests = Requests::where('user_id', $userid)->where('status', 4)->paginate(10);
            return view('requests.review', ['requests' => $requests]);
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
        $paymentinfo = Payments::where('user_id', $userid)->first();
        if($paymentinfo->status == 'active') {
            $requests = Requests::where('user_id', $userid)->where('status', 0)->paginate(10);
            return view('requests.delivered', ['requests' => $requests]);
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
        $paymentinfo = Payments::where('user_id', $userid)->first();
        if($paymentinfo->status == 'active') {
            $requests = Requests::where('user_id', $userid)->where('status', 1)->paginate(10);
            return view('requests.draft', ['requests' => $requests]);
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

        return view('requests.view')->with([
            'requests'  => $requests,
            'brand' => $brand,
            'designtype' => $designtype,
            'medias' => $medias
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
        $paymentinfo = Payments::where('user_id', $userid)->first();
        if($paymentinfo->status == 'active') {
            $designtypes = RequestTypes::get();
            $brands = Brand::where('user_id', $userid)->get();
            $file_types = $this->helper->request_file_types();

            return view('requests.add', ['brands' => $brands, 'designtypes' => $designtypes, 'types' => $file_types]);
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
        $userid = $request->user()->id;

        // Validations
        $request->validate([
            'title'    => 'required',
            'design_type'     => 'required',
            'dimensions'     => 'required',
            'description'     => 'required',
            'brand_id'     => 'required',
            'media.*' => 'required|mimes:jpg,png'
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $requests = Requests::create([
                'title'    => $request->title,
                'design_type'    => $request->design_type,
                'dimensions'     => $request->dimensions,
                'description'     => $request->description,
                'format'        => $request->format,
                'dimensions_additional_info' => $request->dimensions_additional_info,
                'brand_id'        => $request->brand_id,
                'user_id'        => $userid,
                'priority'        => $request->priority
            ]);

            // Check upload medias
            if($request->hasFile('media')) {

                $requestmediapath = public_path('storage/media') .'/'. $userid;
                if(!File::isDirectory($requestmediapath)){
                    // Create Path
                    File::makeDirectory($requestmediapath, 0777, true, true);
                }

                $allowedMediasExtension = ['jpg','png'];
                $medias = $request->file('media');
                foreach($medias as $med) {
                    $filename = $med->getClientOriginalName();
                    $extension = $med->getClientOriginalExtension();
                    $check = in_array($extension, $allowedMediasExtension);

                    if($check) { 
                        $randomfilename = $this->helper->generateRandomString(15);
                        $mediapath = $randomfilename .'.'. $extension;
                        $med->move($requestmediapath, $mediapath);

                        $assets = RequestAssets::create([
                            'filename' => $mediapath,
                            'request_id' => $requests->id,
                            'type' => 'media'
                        ]);
                    }
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
                            'subject' => 'Request status changed',
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
            return redirect()->back()->with('error', 'Account limit: Your are not allowed to add more than '. $allowed['allowedrequest'] .' requests.');
        }
    }

    /**
     * Edit Requests
     * @param Integer $requests
     * @return Collection $requests
     */
    public function edit(Requests $requests)
    {
        $userid = Auth::id();

        $designtypes = RequestTypes::get();
        $brands = Brand::where('user_id', $userid)->get();

        // Get images
        $medias = RequestAssets::where('request_id', $requests->id)->where('type', 'media')->get();

        return view('requests.edit')->with([
            'requests'  => $requests,
            'brands' => $brands,
            'designtypes' => $designtypes,
            'medias' => $medias
        ]);
    }

    /**
     * Edit Requests
     * @param Integer $requests
     * @return Collection $requests
     */
    public function comment(Requests $requests)
    {
        $comments = Comments::where('request_id', $requests->id)->get();
        return view('requests.comment')->with([
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

                $allowedMediasExtension = ['jpg','png'];
                $commentfiles = $request->file('attachments');
                foreach($commentfiles as $commentfile) {
                    $filename = $commentfile->getClientOriginalName();
                    $extension = $commentfile->getClientOriginalExtension();
                    $check = in_array($extension, $allowedMediasExtension);

                    if($check) { 
                        $randomfilename = $this->helper->generateRandomString(15);
                        $attachmentpath = $randomfilename .'.'. $extension;
                        $commentfile->move($commentspath, $attachmentpath);

                        $assets = CommentsAssets::create([
                            'filename' => $attachmentpath,
                            'comments_id' => $comment->id,
                            'type' => 'comment'
                        ]);
                    }
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
            'brand_id'     => 'required',
            'media.*' => 'mimes:jpg,png'
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $requests_updated = Requests::whereId($requests->id)->update([
                'title'    => $request->title,
                'design_type'    => $request->design_type,
                'dimensions'     => $request->dimensions,
                'description'     => $request->description,
                'format'        => $request->format,
                'dimensions_additional_info' => $request->dimensions_additional_info,
                'brand_id'        => $request->brand_id,
                'priority'        => $request->priority
            ]);

            // Check upload medias
            if($request->hasFile('media')) {

                $requestmediapath = public_path('storage/media') .'/'. $userid;
                if(!File::isDirectory($requestmediapath)){
                    // Create Path
                    File::makeDirectory($requestmediapath, 0777, true, true);
                }

                $allowedMediasExtension = ['jpg','png'];
                $medias = $request->file('media');
                foreach($medias as $med) {
                    $filename = $med->getClientOriginalName();
                    $extension = $med->getClientOriginalExtension();
                    $check = in_array($extension, $allowedMediasExtension);

                    if($check) { 
                        $randomfilename = $this->helper->generateRandomString(15);
                        $mediapath = $randomfilename .'.'. $extension;
                        $med->move($requestmediapath, $mediapath);

                        $assets = RequestAssets::create([
                            'filename' => $mediapath,
                            'request_id' => $requests->id,
                            'type' => 'media'
                        ]);
                    }
                }
            }

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('request.index')->with('success','Requests Updated Successfully.');

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
            $paymentinfo = Payments::where('user_id', $userid)->first();
            if($paymentinfo->status == 'active') {
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
}
