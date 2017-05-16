<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\admin\components\grid\ActionColumn;
use app\modules\admin\components\grid\SetColumn;
use app\models\IndustrySearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IndustrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Industries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="industry-index">
    <p><?= Html::a('Create Industry', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            [
                'class' => SetColumn::className(),
                'filter' => IndustrySearch::getTemplateArray(),
                'attribute' => 'price',
                'name' => 'template',
                'cssCLasses' => [
                    IndustrySearch::TEMPLATE_MULTIPLE => 'success',
                    IndustrySearch::TEMPLATE_ONE => 'default'
                ],
            ],
            [
                'class' => SetColumn::className(),
                'filter' => IndustrySearch::getTimeArray(),
                'attribute' => 'time',
                'name' => 'show',
                'cssCLasses' => [
                    IndustrySearch::TIME_USE => 'success',
                    IndustrySearch::TIME_NOT => 'default'
                ],
            ],
            [
                'class' => SetColumn::className(),
                'filter' => IndustrySearch::getStatusesArray(),
                'attribute' => 'display',
                'name' => 'statusName',
                'cssCLasses' => [
                    IndustrySearch::STATUS_ACTIVE => 'success',
                    IndustrySearch::STATUS_INACTIVE => 'default'
                ],
            ],
            ['class' => ActionColumn::className()],
        ],
    ]); ?>
</div>
