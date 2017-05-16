<?php
namespace app\modules\api\modules\v1\controllers;

use app\models\Business;
use yii\rest\Controller;

class BusinessController extends Controller
{
    public function actionView($id)
    {
        $result = Business::find()
            ->alias('business')
            ->joinWith('menus.categories.services.tiers')
            ->where(['business.id' => $id])
            ->one();

        return $result;
    }
}
