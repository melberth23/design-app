<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TempFile;
use App\Lib\SystemHelper;
use File;

class TempFilesController extends Controller
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

    public function uploadTempfiles(Request $request)
    {
        $userid = $request->user()->id;

        $tempfiles = [];

        // Logo
        if($request->hasFile('logos')) {
            $allowedLogosExtension = ['jpg','png','svg'];
            $logo = $request->file('logos');
            $extension = $logo->getClientOriginalExtension();
            $check = in_array($extension, $allowedLogosExtension);

            if($check) { 
                $randomfilename = $this->helper->generateRandomString(15);
                $filename = $randomfilename .'.'. $extension;
                $s3path = 'logos/'. $userid .'/'. $filename;
                $path = Storage::disk('s3')->put($s3path, fopen($logo, 'r+'), 'public');
                $imgpath = Storage::disk('s3')->url($s3path);

                $logotempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $s3path,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $logotempfile->reference_id,
                    'logo_id' => $logotempfile->id,
                    'path' => $imgpath,
                ];
            }
        }

        if($request->hasFile('logos_second')) {
            $allowedLogosSecondExtension = ['jpg','png','svg'];
            $logo_second = $request->file('logos_second');
            $extension = $logo_second->getClientOriginalExtension();
            $check = in_array($extension, $allowedLogosSecondExtension);

            if($check) { 
                $randomfilename = $this->helper->generateRandomString(15);
                $filename = $randomfilename .'.'. $extension;
                $s3path = 'logos/'. $userid .'/'. $filename;
                $path = Storage::disk('s3')->put($s3path, fopen($logo_second, 'r+'), 'public');
                $imgpath = Storage::disk('s3')->url($s3path);

                $logotempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $s3path,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $logotempfile->reference_id,
                    'logo_id' => $logotempfile->id,
                    'path' => $imgpath
                ];
            }
        }

        if($request->hasFile('fonts')) {
            $allowedFontsExtension = ['ttf', 'eot', 'woff'];
            $font = $request->file('fonts');
            $extension = $font->getClientOriginalExtension();
            $check = in_array($extension, $allowedFontsExtension);

            if($check) {
                $filename = $font->getClientOriginalName();
                $s3path = 'fonts/'. $userid .'/'. $filename;
                $path = Storage::disk('s3')->put($s3path, fopen($font, 'r+'), 'public');
                $filepath = Storage::disk('s3')->url($s3path);

                $fonttempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $s3path,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $fonttempfile->reference_id,
                    'font_id' => $fonttempfile->id,
                    'path' => $filepath
                ];
            }
        }

        if($request->hasFile('fonts_second')) {
            $allowedFontsSecondaryExtension = ['ttf', 'eot', 'woff'];
            $font_second = $request->file('fonts_second');
            $extension = $font_second->getClientOriginalExtension();
            $check = in_array($extension, $allowedFontsSecondaryExtension);

            if($check) {
                $filename = $font_second->getClientOriginalName();
                $s3path = 'fonts/'. $userid .'/'. $filename;
                $path = Storage::disk('s3')->put($s3path, fopen($font_second, 'r+'), 'public');
                $filepath = Storage::disk('s3')->url($s3path);

                $fonttempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $s3path,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $fonttempfile->reference_id,
                    'font_id' => $fonttempfile->id,
                    'path' => $filepath
                ];
            }
        }

        if($request->hasFile('pictures')) {
            $allowedPicturesExtension = ['jpg','png'];
            $picture = $request->file('pictures');
            $extension = $picture->getClientOriginalExtension();
            $check = in_array($extension, $allowedPicturesExtension);

            if($check) { 
                $randomfilename = $this->helper->generateRandomString(15);
                $filename = $randomfilename .'.'. $extension;
                $s3path = 'pictures/'. $userid .'/'. $filename;
                $path = Storage::disk('s3')->put($s3path, fopen($picture, 'r+'), 'public');
                $imgpath = Storage::disk('s3')->url($s3path);

                $picturetempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $s3path,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $picturetempfile->reference_id,
                    'picture_id' => $picturetempfile->id,
                    'path' => $imgpath
                ];
            }
        }

        if($request->hasFile('guidelines')) {
            $allowedGuidelinesExtension = ['doc', 'docx', 'pdf', 'png', 'jpg',];
            $guideline = $request->file('guidelines');
            $extension = $guideline->getClientOriginalExtension();
            $check = in_array($extension, $allowedGuidelinesExtension);

            if($check) {
                $randomfilename = $this->helper->generateRandomString(15);
                $filename = $randomfilename .'.'. $extension;
                $s3path = 'guidelines/'. $userid .'/'. $filename;
                $path = Storage::disk('s3')->put($s3path, fopen($guideline, 'r+'), 'public');
                $filepath = Storage::disk('s3')->url($s3path);

                $guidelinetempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $s3path,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $guidelinetempfile->reference_id,
                    'guideline_id' => $guidelinetempfile->id,
                    'path' => $filepath
                ];
            }
        }

        if($request->hasFile('templates')) {
            $allowedTemplatesExtension = ['psd', 'ai', 'indd', 'doc', 'docx', 'pdf', 'png', 'jpg'];
            $template = $request->file('templates');
            $extension = $template->getClientOriginalExtension();
            $check = in_array($extension, $allowedTemplatesExtension);

            if($check) {
                $randomfilename = $this->helper->generateRandomString(15);
                $filename = $randomfilename .'.'. $extension;
                $s3path = 'templates/'. $userid .'/'. $filename;
                $path = Storage::disk('s3')->put($s3path, fopen($template, 'r+'), 'public');
                $filepath = Storage::disk('s3')->url($s3path);


                $templatetempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $s3path,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $templatetempfile->reference_id,
                    'template_id' => $templatetempfile->id,
                    'path' => $filepath
                ];
            }
        }

        if($request->hasFile('inspirations')) {
            $allowedInspirationsExtension = ['jpg','png','gif'];
            $inspiration = $request->file('inspirations');
            $extension = $inspiration->getClientOriginalExtension();
            $check = in_array($extension, $allowedInspirationsExtension);

            if($check) {
                $randomfilename = $this->helper->generateRandomString(15);
                $filename = $randomfilename .'.'. $extension;
                $s3path = 'inspirations/'. $userid .'/'. $filename;
                $path = Storage::disk('s3')->put($s3path, fopen($inspiration, 'r+'), 'public');
                $filepath = Storage::disk('s3')->url($s3path);

                $inspirationtempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $s3path,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $inspirationtempfile->reference_id,
                    'inspiration_id' => $inspirationtempfile->id,
                    'path' => $filepath
                ];
            }
        }

        if($request->hasFile('request')) {
            $allowedMediasExtension = ['jpg','png'];
            $med = $request->file('request');
            $extension = $med->getClientOriginalExtension();
            $check = in_array($extension, $allowedMediasExtension);

            if($check) { 
                $randomfilename = $this->helper->generateRandomString(15);
                $filename = $randomfilename .'.'. $extension;
                $s3path = 'media/'. $userid .'/'. $filename;
                $path = Storage::disk('s3')->put($s3path, fopen($med, 'r+'), 'public');
                $imgpath = Storage::disk('s3')->url($s3path);

                $medtempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $s3path,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $medtempfile->reference_id,
                    'picture_id' => $medtempfile->id,
                    'path' => $imgpath
                ];
            }
        }

        if($request->hasFile('comment_media')) {
            $mediafile = $request->file('comment_media');
            $extension = $mediafile->getClientOriginalExtension();

            $randomfilename = $this->helper->generateRandomString(15);
            $filename = $randomfilename .'.'. $extension;
            $s3path = 'comments/'. $userid .'/'. $filename;
            $path = Storage::disk('s3')->put($s3path, fopen($mediafile, 'r+'), 'public');
            $filepath = Storage::disk('s3')->url($s3path);

            $comment_mediatempfile = TempFile::create([
                'reference_id' => $userid,
                'module' => $request->module,
                'code' => $request->tempfile_code,
                'file' => $s3path,
                'file_type' => $extension
            ]);

            $tempfiles = [
                'ref_id' => $comment_mediatempfile->reference_id,
                'picture_id' => $comment_mediatempfile->id,
                'path' => $filepath
            ];
        }

        if($request->hasFile('comment_document')) {
            $documentsfile = $request->file('comment_document');
            $extension = $documentsfile->getClientOriginalExtension();
            
            $randomfilename = $this->helper->generateRandomString(15);
            $filename = $randomfilename .'.'. $extension;
            $s3path = 'comments/'. $userid .'/'. $filename;
            $path = Storage::disk('s3')->put($s3path, fopen($documentsfile, 'r+'), 'public');
            $filepath = Storage::disk('s3')->url($s3path);

            $comment_adobetempfile = TempFile::create([
                'reference_id' => $userid,
                'module' => $request->module,
                'code' => $request->tempfile_code,
                'file' => $s3path,
                'file_type' => $extension
            ]);

            $tempfiles = [
                'ref_id' => $comment_adobetempfile->reference_id,
                'adobe_id' => $comment_adobetempfile->id,
                'path' => $filepath
            ];
        }

        return response()->json(array('file'=> $tempfiles), 200);
    }

    public function deleteTempfiles(Request $request)
    {
        $tempfile = TempFile::whereId($request->fid)->first();
        $directory = $this->helper->media_directories($tempfile->module);
        $filepath = public_path('storage/'. $directory .'/'. $tempfile->reference_id .'/'. $tempfile->file);

        // Delete file
        if(file_exists($filepath)) {
            File::delete($filepath);
        }
        if(Storage::disk('s3')->exists($tempfile->file)) {
            Storage::disk('s3')->delete($tempfile->file);
        }
        TempFile::whereId($tempfile->id)->delete();

        return response()->json(array('fid'=> $tempfile->id), 200);
    }
}
