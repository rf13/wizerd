<?php

namespace app\modules\api;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }
}
