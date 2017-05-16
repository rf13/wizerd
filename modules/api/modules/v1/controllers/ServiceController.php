<?php
namespace app\modules\api\modules\v1\controllers;

use \Yii;
use app\forms\SearchForm;
use yii\rest\Controller;

class ServiceController extends Controller
{
    public function actionSearch($zip, $search)
    {
        $model = new SearchForm();
        $model->load(Yii::$app->getRequest()
            ->get(), '');
        if ($model->validate()) {
            return $model->makeSearch();
        }

        return $model;
    }
}
