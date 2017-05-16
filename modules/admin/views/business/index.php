<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BusinessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Business Manage';
$this->params['breadcrumbs'][] = [
    'label' => $this->title
];
//$this->params['breadcrumbs'][] = '';
?>
<div class="zip-code-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'format' => 'raw',
                'attribute' => 'Public URL',
                'value' => function ($model) {
                    return Html::a($model->makeBusinessLink(), $model->makeBusinessLink(), ['target' => '_blank']);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>
</div>
