<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            $requestlogospath = public_path('storage/logos') .'/'. $userid;
            if(!File::isDirectory($requestlogospath)){
                // Create Path
                File::makeDirectory($requestlogospath, 0777, true, true);
            }

            $allowedLogosExtension = ['jpg','png','svg'];
            $logo = $request->file('logos');
            $filename = $logo->getClientOriginalName();
            $extension = $logo->getClientOriginalExtension();
            $check = in_array($extension, $allowedLogosExtension);

            if($check) { 
                $randomfilename = $this->helper->generateRandomString(15);
                $logopath = $randomfilename .'.'. $extension;
                $logo->move($requestlogospath, $logopath);

                $logotempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $logopath,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $logotempfile->reference_id,
                    'logo_id' => $logotempfile->id,
                    'path' => $logotempfile->file
                ];
            }
        }

        if($request->hasFile('logos_second')) {
            $requestlogosSecondpath = public_path('storage/logos') .'/'. $userid;
            if(!File::isDirectory($requestlogosSecondpath)){
                // Create Path
                File::makeDirectory($requestlogosSecondpath, 0777, true, true);
            }

            $allowedLogosSecondExtension = ['jpg','png','svg'];
            $logo_second = $request->file('logos_second');
            $filename = $logo_second->getClientOriginalName();
            $extension = $logo_second->getClientOriginalExtension();
            $check = in_array($extension, $allowedLogosSecondExtension);

            if($check) { 
                $randomfilename = $this->helper->generateRandomString(15);
                $logo_secondpath = $randomfilename .'.'. $extension;
                $logo_second->move($requestlogosSecondpath, $logo_secondpath);

                $logotempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $logo_secondpath,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $logotempfile->reference_id,
                    'logo_id' => $logotempfile->id,
                    'path' => $logotempfile->file
                ];
            }
        }

        if($request->hasFile('fonts')) {
            $requestfontspath = public_path('storage/fonts') .'/'. $userid;
            if(!File::isDirectory($requestfontspath)){
                // Create Path
                File::makeDirectory($requestfontspath, 0777, true, true);
            }

            $allowedFontsExtension = ['ttf', 'eot', 'woff'];
            $font = $request->file('fonts');
            $filename = $font->getClientOriginalName();
            $extension = $font->getClientOriginalExtension();
            $check = in_array($extension, $allowedFontsExtension);

            if($check) {
                $fontpath = $filename;
                $font->move($requestfontspath, $fontpath);

                $fonttempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $fontpath,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $fonttempfile->reference_id,
                    'font_id' => $fonttempfile->id,
                    'path' => $fonttempfile->file
                ];
            }
        }

        if($request->hasFile('fonts_second')) {
            $requestfontsSecondarypath = public_path('storage/fonts') .'/'. $userid;
            if(!File::isDirectory($requestfontsSecondarypath)){
                // Create Path
                File::makeDirectory($requestfontsSecondarypath, 0777, true, true);
            }

            $allowedFontsSecondaryExtension = ['ttf', 'eot', 'woff'];
            $font_second = $request->file('fonts_second');
            $filename = $font_second->getClientOriginalName();
            $extension = $font_second->getClientOriginalExtension();
            $check = in_array($extension, $allowedFontsSecondaryExtension);

            if($check) {
                $fontpath = $filename;
                $font_second->move($requestfontsSecondarypath, $fontpath);

                $fonttempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $fontpath,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $fonttempfile->reference_id,
                    'font_id' => $fonttempfile->id,
                    'path' => $fonttempfile->file
                ];
            }
        }

        if($request->hasFile('pictures')) {
            $requestpicturespath = public_path('storage/pictures') .'/'. $userid;
            if(!File::isDirectory($requestpicturespath)){
                // Create Path
                File::makeDirectory($requestpicturespath, 0777, true, true);
            }

            $allowedPicturesExtension = ['jpg','png'];
            $picture = $request->file('pictures');
            $filename = $picture->getClientOriginalName();
            $extension = $picture->getClientOriginalExtension();
            $check = in_array($extension, $allowedPicturesExtension);

            if($check) { 
                $randomfilename = $this->helper->generateRandomString(15);
                $picturepath = $randomfilename .'.'. $extension;
                $picture->move($requestpicturespath, $picturepath);

                $picturetempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $picturepath,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $picturetempfile->reference_id,
                    'picture_id' => $picturetempfile->id,
                    'path' => $picturetempfile->file
                ];
            }
        }

        if($request->hasFile('guidelines')) {
            $requestguidelinespath = public_path('storage/guidelines') .'/'. $userid;
            if(!File::isDirectory($requestguidelinespath)){
                // Create Path
                File::makeDirectory($requestguidelinespath, 0777, true, true);
            }

            $allowedGuidelinesExtension = ['doc', 'docx', 'pdf', 'png', 'jpg',];
            $guideline = $request->file('guidelines');
            $filename = $guideline->getClientOriginalName();
            $extension = $guideline->getClientOriginalExtension();
            $check = in_array($extension, $allowedGuidelinesExtension);

            if($check) {
                $randomfilename = $this->helper->generateRandomString(15);
                $guidelinepath = $randomfilename .'.'. $extension;
                $guideline->move($requestguidelinespath, $guidelinepath);

                $guidelinetempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $guidelinepath,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $guidelinetempfile->reference_id,
                    'guideline_id' => $guidelinetempfile->id,
                    'path' => $guidelinetempfile->file
                ];
            }
        }

        if($request->hasFile('templates')) {
            $requesttemplatespath = public_path('storage/templates') .'/'. $userid;
            if(!File::isDirectory($requesttemplatespath)){
                // Create Path
                File::makeDirectory($requesttemplatespath, 0777, true, true);
            }

            $allowedTemplatesExtension = ['psd', 'ai', 'indd', 'doc', 'docx', 'pdf', 'png', 'jpg'];
            $template = $request->file('templates');
            $filename = $template->getClientOriginalName();
            $extension = $template->getClientOriginalExtension();
            $check = in_array($extension, $allowedTemplatesExtension);

            if($check) {
                $randomfilename = $this->helper->generateRandomString(15);
                $templatepath = $randomfilename .'.'. $extension;
                $template->move($requesttemplatespath, $templatepath);

                $templatetempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $templatepath,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $templatetempfile->reference_id,
                    'template_id' => $templatetempfile->id,
                    'path' => $templatetempfile->file
                ];
            }
        }

        if($request->hasFile('inspirations')) {
            $requestinspirationspath = public_path('storage/inspirations') .'/'. $userid;
            if(!File::isDirectory($requestinspirationspath)){
                // Create Path
                File::makeDirectory($requestinspirationspath, 0777, true, true);
            }

            $allowedInspirationsExtension = ['jpg','png','gif'];
            $inspiration = $request->file('inspirations');
            $filename = $inspiration->getClientOriginalName();
            $extension = $inspiration->getClientOriginalExtension();
            $check = in_array($extension, $allowedInspirationsExtension);

            if($check) {
                $randomfilename = $this->helper->generateRandomString(15);
                $inspirationpath = $randomfilename .'.'. $extension;
                $inspiration->move($requestinspirationspath, $inspirationpath);

                $inspirationtempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $inspirationpath,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $inspirationtempfile->reference_id,
                    'inspiration_id' => $inspirationtempfile->id,
                    'path' => $inspirationtempfile->file
                ];
            }
        }

        if($request->hasFile('request')) {
            $requestmediapath = public_path('storage/media') .'/'. $userid;
            if(!File::isDirectory($requestmediapath)){
                // Create Path
                File::makeDirectory($requestmediapath, 0777, true, true);
            }

            $allowedMediasExtension = ['jpg','png'];
            $med = $request->file('request');
            $filename = $med->getClientOriginalName();
            $extension = $med->getClientOriginalExtension();
            $check = in_array($extension, $allowedMediasExtension);

            if($check) { 
                $randomfilename = $this->helper->generateRandomString(15);
                $mediapath = $randomfilename .'.'. $extension;
                $med->move($requestmediapath, $mediapath);

                $medtempfile = TempFile::create([
                    'reference_id' => $userid,
                    'module' => $request->module,
                    'code' => $request->tempfile_code,
                    'file' => $mediapath,
                    'file_type' => $extension
                ]);

                $tempfiles = [
                    'ref_id' => $medtempfile->reference_id,
                    'picture_id' => $medtempfile->id,
                    'path' => $medtempfile->file
                ];
            }
        }

        if($request->hasFile('comment_media')) {
            $mediapath = public_path('storage/comments') .'/'. $userid;
            if(!File::isDirectory($mediapath)){
                // Create Path
                File::makeDirectory($mediapath, 0777, true, true);
            }

            $mediafile = $request->file('comment_media');
            $filename = $mediafile->getClientOriginalName();
            $extension = $mediafile->getClientOriginalExtension();
            $randomfilename = $this->helper->generateRandomString(15);
            $attachmentpath = $randomfilename .'.'. $extension;
            $mediafile->move($mediapath, $attachmentpath);

            $comment_mediatempfile = TempFile::create([
                'reference_id' => $userid,
                'module' => $request->module,
                'code' => $request->tempfile_code,
                'file' => $attachmentpath,
                'file_type' => $extension
            ]);

            $tempfiles = [
                'ref_id' => $comment_mediatempfile->reference_id,
                'picture_id' => $comment_mediatempfile->id,
                'path' => $comment_mediatempfile->file
            ];
        }

        if($request->hasFile('comment_document')) {

            $documentspath = public_path('storage/comments') .'/'. $userid;
            if(!File::isDirectory($documentspath)){
                // Create Path
                File::makeDirectory($documentspath, 0777, true, true);
            }

            $documentsfile = $request->file('comment_document');
            $filename = $documentsfile->getClientOriginalName();
            $extension = $documentsfile->getClientOriginalExtension();
            $randomfilename = $this->helper->generateRandomString(15);
            $attachmentpath = $randomfilename .'.'. $extension;
            $documentsfile->move($documentspath, $attachmentpath);

            $comment_adobetempfile = TempFile::create([
                'reference_id' => $userid,
                'module' => $request->module,
                'code' => $request->tempfile_code,
                'file' => $attachmentpath,
                'file_type' => $extension
            ]);

            $tempfiles = [
                'ref_id' => $comment_adobetempfile->reference_id,
                'adobe_id' => $comment_adobetempfile->id,
                'path' => $comment_adobetempfile->file
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
        File::delete($filepath);
        TempFile::whereId($tempfile->id)->delete();

        return response()->json(array('fid'=> $tempfile->id), 200);
    }
}
