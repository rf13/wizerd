<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.12.15
 * Time: 9:36
 */
namespace app\components;

use app\models\Business;
use app\models\City;
use app\models\Industry;
use yii\web\UrlRule;

class MainUrlRule extends UrlRule
{
    public $connectionID = 'db';

    public function  init()
    {
        if ($this->name == null) {
            $this->name = __CLASS__;
        }
    }


    public function createUrl($manager, $route, $params)
    {

        //return parent::createUrl($manager, $route, $params); // TODO: Change the autogenerated stub
    }

    public function parseRequest($manager, $request)
    {
        // return parent::parseRequest($manager, $request); // TODO: Change the autogenerated stub
        $pathInfo = $request->getPathInfo();


        //For links  wizerd.com/city or wizerd.com/vanity-name
        if (preg_match('%^(\w+([-]?\w+)*)$%', $pathInfo, $array)) {
            if ($business=Business::searchByVanityName($array[1])) {

                //return ['site/biz301', ['business' => $business]];
                return ['site/business', ['business' => $business]];

            }
/*
            if ($city=City::searchByName($array[1]))
                return ['site/city',['city'=>$city]];
*/

    }

        //  For links wizerd.com/city/industry
        /*
        if (preg_match('%^(\w+([-]?\w+)*)/(\w+([-]?\w+)*)$%', $pathInfo, $array)) {

            if (($city=City::searchByName($array[1]))&&($ind=Industry::searchBySearchString($array[3])))
            {
                return ['site/city-industry', ['city'=>$city,'industry'=>$ind]];
            }
        }
        */

        // For links   wizerd.com/city/industry/vanity-name
        /*
        if (preg_match('%^(\w+([-]?\w+)*)/(\w+([-]?\w+)*)/(\w+([-]?\w+)*)$%', $pathInfo, $array)) {

            if (($city=City::searchByName($array[1]))&&($ind=Industry::searchBySearchString($array[3]))&&($biz=Business::searchByVanityName($array[5])))
            {
                return ['site/city-industry-business', ['city'=>$city,'industry'=>$ind,'business'=>$biz]];
            }
        }
        */






        return false;

    }

}

?>