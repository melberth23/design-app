<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\RequestTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Lib\SystemHelper;

class RequestTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List request types 
     * @param Nill
     * @return Array $requests
     */
    public function index()
    {
        $requesttypes = RequestTypes::paginate(10);
        return view('admin.requesttypes.index', ['requesttypes' => $requesttypes]);
    }

    /**
     * Create Request Types 
     * @param Nill
     * @return Array $requests
     */
    public function create()
    {
        return view('admin.requesttypes.add');
    }

    /**
     * Store Request Types
     * @param Request $request
     * @return View requests
     */
    public function store(Request $request)
    {
        // Validations
        $request->validate([
            'name'    => 'required',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $requests = RequestTypes::create([
                'name'    => $request->name,
                'description'     => $request->description,
                'status'        => $request->status,
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('requesttypes.index')->with('success','Request Type Created Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Update Status Of Request Types
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
            'request_id'   =>  'required|exists:request_types,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('requesttypes.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            RequestTypes::whereId($request_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('requesttypes.index')->with('success','Requests Status Updated Successfully!');
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
    public function edit(RequestTypes $requesttype)
    {
        return view('admin.requesttypes.edit')->with([
            'requesttypes'  => $requesttype
        ]);
    }

    /**
     * Update Request Types
     * @param Request $request, Requests $requests
     * @return View requests
     */
    public function update(Request $request, RequestTypes $requesttype)
    {
        // Validations
        $request->validate([
            'name'    => 'required',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $requests_updated = RequestTypes::whereId($requesttype->id)->update([
                'name'    => $request->name,
                'description'     => $request->description,
                'status'        => $request->status
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('requesttypes.index')->with('success','Request Type Updated Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete Request Types
     * @param Requests $requests
     * @return Index requests
     */
    public function delete(RequestTypes $requesttype)
    {
        DB::beginTransaction();
        try {
            // Delete Requests
            RequestTypes::whereId($requesttype->id)->delete();

            DB::commit();
            return redirect()->route('requesttypes.index')->with('success', 'Request Type Deleted Successfully!.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
