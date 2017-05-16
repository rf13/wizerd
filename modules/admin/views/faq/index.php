<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\admin\components\grid\ActionColumn;
use app\modules\admin\components\grid\SetColumn;
use app\models\FaqSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'FAQ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-index">
    <p><?= Html::a('Create Faq', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'question',
            'answer',
            [
                'class' => SetColumn::className(),
                'filter' => FaqSearch::getTypesArray(),
                'attribute' => 'type',
                'name' => 'typeName',
                'cssCLasses' => [
                    FaqSearch::TYPE_CONSUMER => 'success',
                    FaqSearch::TYPE_BUSINESS => 'primary'
                ],
            ],
            [
                'class' => SetColumn::className(),
                'filter' => FaqSearch::getStatusesArray(),
                'attribute' => 'status',
                'name' => 'statusName',
                'cssCLasses' => [
                    FaqSearch::STATUS_ACTIVE => 'success',
                    FaqSearch::STATUS_INACTIVE => 'default'
                ],
            ],
            ['class' => ActionColumn::className()],
        ],
    ]); ?>
</div>