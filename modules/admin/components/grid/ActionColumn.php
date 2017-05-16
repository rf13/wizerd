<?php
namespace app\modules\admin\components\grid;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $contentOptions = [
        'class' => 'action-column',
    ];
}