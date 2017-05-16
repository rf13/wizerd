<?php
namespace app\modules\api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\rest\Controller;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function actionIndex()
    {
        return ['a' => 1];
    }
}
