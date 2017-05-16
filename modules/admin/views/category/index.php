<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\admin\components\grid\ActionColumn;
use app\modules\admin\components\grid\LinkColumn;
use app\modules\admin\components\grid\SetColumn;
use app\models\CategorySearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
    <p><?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => LinkColumn::className(),
                'attribute' => 'ind_id',
                'value'=> function ($data) {
                    return $data->industry->title;
                },

            ],
            'title',
            [
                'class' => SetColumn::className(),
                'filter' => CategorySearch::getStatusesArray(),
                'attribute' => 'display',
                'name' => 'statusName',
                'cssCLasses' => [
                    CategorySearch::STATUS_ACTIVE => 'success',
                    CategorySearch::STATUS_INACTIVE => 'default'
                ],
            ],
            ['class' => ActionColumn::className()],
        ],
    ]); ?>
</div>
