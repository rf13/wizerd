<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Promo;

class CronController extends Controller
{

    /**
     * Deactivation of expired promo
     */
    public function actionIndex()
    {
        $count = 0;
        $promos = Promo::find()->where(['<>', 'active', Promo::STATUS_ENDED])->all();
        foreach($promos as $promo) {
            /* @var $promo \app\models\Promo */
            if (strtotime($promo->end) < strtotime(date('Y-m-d'))) {
                $count++;
                $promo->endNowGroup();
            }
        }
        if ($count > 0) $result = 'Promos were deactivated';
        else $result = 'Promo not found';
        //echo $result . "\n";
    }

}