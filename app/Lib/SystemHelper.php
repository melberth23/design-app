<?php 
namespace App\Lib;


use DB;
use App\Models\Payments;
use App\Models\Requests;
use App\Models\Brand;
use App\Models\BrandAssets;

class SystemHelper {

    /**
    * @param string plan
    * @return array Plan information
    */
    public function getPlanRules($plan='basic') {
        return $this->getPlanInformation($plan);
    }

    /**
    * @param string plan
    * @return array Plan information
    */
    public function getPlanInformation($plan='basic')
    {
        $plans = array(
            'basic' => array(
                'label' => 'Basic',
                'id' => '95d369cc-7fbd-4608-b53f-562605eab522',
                'amount' => 449,
                'request' => 1,
                'backlog' => false,
                'brand' => 1
            ),
            'premium' => array(
                'label' => 'Premium',
                'id' => '95d369fd-9228-4f36-bd7f-d03c6805027a',
                'amount' => 1145,
                'request' => 2,
                'backlog' => false,
                'brand' => 2
            ),
            'royal' => array(
                'label' => 'Royal',
                'id' => '95d36a21-671d-48f9-909f-002d022c6b59',
                'amount' => 2395,
                'request' => 2,
                'backlog' => true,
                'brand' => 9999
            )
        );

        return $plans[$plan];
    }

    /**
    * @param int userid
    * @return array allowed information
    */
    public function userActionRules($userid, $type='request', $status=1) {
        $statusRules = array(2,3);
        $numberofitems = array();
        if($type == 'brand') {
            $numberofitems = Brand::where('user_id', $userid)->get();
        } elseif($type == 'request') {
            if($status == 2) {
                $numberofitems = Requests::whereIn('status', $statusRules)->where('user_id', $userid)->get();
            }
        }
        $numberofitems = count($numberofitems);
        $payments = Payments::where('user_id', $userid)->first();
        $planrule = $this->getPlanRules($payments->plan);
        $allowedrule = $planrule[$type];

        $allowed = false;
        if($allowedrule > 0) {
            if($allowedrule > $numberofitems) {
                $allowed = true;
            }
        } else {
            $allowed = true;
        }

        return array(
            'allowed' => $allowed,
            'allowedrequest' => $planrule['request'],
            'allowedbrand' => $planrule['brand']
        );
    }

    /**
    * @param int length of generated string
    * @return string Random string
    */
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
    * @param int userid
    * @return bool true | false
    */
    public function checkIfAccountPaid($userid) {
        $isPaid = Payments::where('user_id', $userid)->IsPaid();
        if($isPaid)
        {
            return true;
        }
        return false;
    }

    /**
    * @param int status
    * @return string status label
    */
    public function checkLockedStatus($status) {
        $statuses = array(
            0 => 'Completed',
            3 => 'Progress'
        );
        return !empty($statuses[$status])?true:false;
    }

    /**
    * @param int status
    * @return string status label
    */
    public function statusLabel($status) {
        $statuses = array(
            0 => 'Completed',
            1 => 'Draft',
            2 => 'Submitted',
            3 => 'Progress',
            4 => 'For Review'
        );

        return $statuses[$status];
    }

    /**
     * @param string file type
     * @return array directories
     */
    public function media_directories($type) {

        $type = ($type=='review')?'comment':$type;

        $directories = array(
            'logo' => 'logos',
            'logo_second' => 'logos',
            'picture' => 'pictures',
            'font' => 'fonts',
            'font_second' => 'fonts',
            'inspiration' => 'inspirations',
            'template' => 'templates',
            'guideline' => 'guidelines',
            'media' => 'media',
            'comment' => 'comments',
        );

        return $directories[$type];
    }

    /**
     * @return all file types 
     */
    public function request_file_types() {
        $file_types = array(
            'jpg' => '.jpg',
            'png' => '.png',
            'gif' => '.gif',
            'pdf' => '.pdf',
            'any' => '.any'
        );
        $adobe_types = array(
            'psd' => 'psd',
            'ai' => 'ai',
            'indd' => 'indd'
        );

        return [
            'files' => $file_types,
            'adobe' => $adobe_types
        ];
    }

    public function get_brand_logo($brand) {
        $logos = BrandAssets::where('brand_id', $brand->id)->where('type', 'logo')->first();

        $string = '<h2>'. substr($brand->name, 0, 1) .'.</h2>';
        if ($logos->count() > 0) {
            $string = '<img src="'. url('storage/logos') .'/'.$brand->user_id .'/'. $logos->filename .'" class="main-logo" >';
        }

        return $string;
    }

    public function get_brand_assets($brand, $type) {
        $assets = BrandAssets::where('brand_id', $brand->id)->where('type', $type)->get();

        $string = '';
        if($type == 'color' && $assets->count() > 0) {
            foreach($assets as $asset) {
                $string .= '<div class="mx-1 color" style="background-color: '. $asset->filename .';border-radius: 50px; width: 40px; height: 40px;"></div>';
            }
        }

        return $string;
    }
}