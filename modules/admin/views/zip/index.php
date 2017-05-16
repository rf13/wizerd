<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\search\ZipSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Zip Codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zip-code-index">
    <p><?= Html::a('Create Zip Code', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'zip',
            'city_id',
            'active',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
