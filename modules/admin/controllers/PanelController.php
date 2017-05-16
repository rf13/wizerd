<?php

namespace app\modules\admin\controllers;

use app\models\ZipCode;
use yii\web\Controller;

class PanelController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', ['info' => ZipCode::getPanelInfo()]);
    }
}
