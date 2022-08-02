<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Requests;
use App\Models\Comments;
use App\Models\BrandAssets;
use App\Models\RequestAssets;
use App\Models\CommentsAssets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Lib\SystemHelper;
use Response;
use File;

class DownloadFileController extends Controller
{
    public $helper;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->helper = new SystemHelper();
    }

    public function downloadBrandFile(BrandAssets $asset){
        $brand = Brand::whereId($asset->brand_id)->first();
        $directory = $this->helper->media_directories($asset->type);
        $filepath = public_path('storage/'. $directory .'/'. $brand->user_id .'/'. $asset->filename);

        if(file_exists($filepath)) {
            return Response::download($filepath);
        } else {
            return Storage::disk('s3')->download($asset->filename);
        }
    }

    public function deleteBrandFile(BrandAssets $asset){
        $brand = Brand::whereId($asset->brand_id)->first();
        $directory = $this->helper->media_directories($asset->type);
        $filepath = public_path('storage/'. $directory .'/'. $brand->user_id .'/'. $asset->filename);

        // Delete file
        if(file_exists($filepath)) {
            File::delete($filepath);
        }
        if(Storage::disk('s3')->exists($asset->filename)) {
            Storage::disk('s3')->delete($asset->filename);
        }
        BrandAssets::whereId($asset->id)->delete();

        return redirect()->back()->with('error', 'File deleted successfully!');
    }

    public function downloadRequestFile(RequestAssets $asset){
        $request = Requests::whereId($asset->request_id)->first();
        $directory = $this->helper->media_directories($asset->type);
        $filepath = public_path('storage/'. $directory .'/'. $request->user_id .'/'. $asset->filename);

        if(file_exists($filepath)) {
            return Response::download($filepath);
        } else {
            return Storage::disk('s3')->download($asset->filename);
        }
    }

    public function deleteRequestFile(RequestAssets $asset){
        $request = Requests::whereId($asset->request_id)->first();
        $directory = $this->helper->media_directories($asset->type);
        $filepath = public_path('storage/'. $directory .'/'. $request->user_id .'/'. $asset->filename);

        // Delete file
        if(file_exists($filepath)) {
            File::delete($filepath);
        }
        if(Storage::disk('s3')->exists($asset->filename)) {
            Storage::disk('s3')->delete($asset->filename);
        }
        RequestAssets::whereId($asset->id)->delete();

        return redirect()->back()->with('error', 'File deleted successfully!');
    }

    public function downloadCommentFile(CommentsAssets $asset){
        $comments = Comments::whereId($asset->comments_id)->first();
        $directory = $this->helper->media_directories($asset->type);
        $filepath = public_path('storage/'. $directory .'/'. $comments->user_id .'/'. $asset->filename);

        if(file_exists($filepath)) {
            return Response::download($filepath);
        } else {
            return Storage::disk('s3')->download($asset->filename);
        }
    }
    
    public function deleteCommentFile(CommentsAssets $asset){
        $comments = Comments::whereId($asset->comments_id)->first();
        $directory = $this->helper->media_directories($asset->type);
        $filepath = public_path('storage/'. $directory .'/'. $comments->user_id .'/'. $asset->filename);

        // Delete file
        if(file_exists($filepath)) {
            File::delete($filepath);
        }
        if(Storage::disk('s3')->exists($asset->filename)) {
            Storage::disk('s3')->delete($asset->filename);
        }
        CommentsAssets::whereId($asset->id)->delete();

        return redirect()->back()->with('error', 'File deleted successfully!');
    }
}
