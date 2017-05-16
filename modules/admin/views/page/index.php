<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\admin\components\grid\ActionColumn;
use app\modules\admin\components\grid\SetColumn;
use app\models\PageSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <p><?= Html::a('Create Page', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'slug',
            [
                'class' => SetColumn::className(),
                'filter' => PageSearch::getStatusesArray(),
                'attribute' => 'status',
                'name' => 'statusName',
                'cssCLasses' => [
                    PageSearch::STATUS_ACTIVE => 'success',
                    PageSearch::STATUS_INACTIVE => 'default'
                ],
            ],
            ['class' => ActionColumn::className()],
        ],
    ]); ?>
</div>