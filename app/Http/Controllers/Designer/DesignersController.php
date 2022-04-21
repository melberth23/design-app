<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\Payments;
use App\Models\Brand;
use App\Models\RequestAssets;
use App\Models\Comments;
use App\Models\CommentsAssets;
use App\Models\Admin\RequestTypes;
use Illuminate\Http\Request;
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

        $this->helper = new SystemHelper();
    }

    /**
     * List requests 
     * @param Nill
     * @return Array $requests
     */
    public function index()
    {
        $requests = Requests::where('status', '!=', 1)->orderBy('user_id', 'ASC')->paginate(10);
        return view('designer.index', ['requests' => $requests]);
    }
    
    /**
     * Queue requests 
     * @param Nill
     * @return Array $requests
     */
    public function queue()
    {
        $userid = Auth::id();

        $requests = Requests::where('designer_id', $userid)->whereIn('status', array(2, 3))->paginate(10);
        return view('designer.queue', ['requests' => $requests]);
    }

    /**
     * Review requests 
     * @param Nill
     * @return Array $requests
     */
    public function review()
    {
        $userid = Auth::id();

        $requests = Requests::where('designer_id', $userid)->where('status', 4)->paginate(10);
        return view('designer.review', ['requests' => $requests]);
    }

    /**
     * Delivered requests 
     * @param Nill
     * @return Array $requests
     */
    public function delivered()
    {
        $userid = Auth::id();

        $requests = Requests::where('designer_id', $userid)->where('status', 0)->paginate(10);
        return view('designer.delivered', ['requests' => $requests]);
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

            $data = ['status' => $status];
            if($status == 3) {
                $data['designer_id'] = $userid;
            }

            // Update Status
            Requests::whereId($request_id)->update($data);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('designer.index')->with('success','Requests Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
