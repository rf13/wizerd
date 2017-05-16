<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

class AjaxController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'public' => ['post'],
                    //'create' => ['get', 'post'],
                    //'*' => ['get'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionPublic()
    {
        $model = '';
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            return $this->renderAjax('_form', [
                'model' => $model,
                'data'  => $data
            ]);
        }
        return false;
    }
}