<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\admin\components\grid\LinkColumn;
use app\modules\admin\components\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Services';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-index">
    <p><?= Html::a('Create Service', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => LinkColumn::className(),
                'attribute' => 'cat_id',
                'value'=> function ($data) {
                    return $data->cat->industry->title . ' -> ' . $data->cat->title;
                },

            ],
            'title',
            ['class' => ActionColumn::className()],
        ],
    ]); ?>
</div>
