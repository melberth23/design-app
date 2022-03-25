<?php 
namespace App\Lib;

use DB;

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
                'brand' => 0
            )
        );

        return $plans[$plan];
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
}