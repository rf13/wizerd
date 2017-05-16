<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\search\CompositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Compositions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="composition-index">
    <p><?= Html::a('Create Composition', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'serv_id',
            'attr_id',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
