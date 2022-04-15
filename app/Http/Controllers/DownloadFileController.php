<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Requests;
use App\Models\BrandAssets;
use App\Models\RequestAssets;
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

        return Response::download($filepath);
    }

    public function downloadRequestFile(RequestAssets $asset){
        $request = Requests::whereId($asset->request_id)->first();
        $directory = $this->helper->media_directories($asset->type);
        $filepath = public_path('storage/'. $directory .'/'. $request->user_id .'/'. $asset->filename);

        return Response::download($filepath);
    }
}