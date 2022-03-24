<?php 
namespace App\Lib;

use DB;

class SystemHelper {

    public function getPlanRules($plan='basic') {
        $plans = array(
            'basic' => array(
                'request' => 1,
                'backlog' => false,
                'brand' => 1
            ),
            'premium' => array(
                'request' => 2,
                'backlog' => false,
                'brand' => 2
            ),
            'royal' => array(
                'request' => 2,
                'backlog' => true,
                'brand' => 0
            )
        );

        return $plans[$plan];
    }
}