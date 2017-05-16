<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\admin\components\grid\ActionColumn;
use app\modules\admin\components\grid\SetColumn;
use app\models\TemplateSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Create Template', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'count',
            [
                'class' => SetColumn::className(),
                'filter' => TemplateSearch::getDescArray(),
                'attribute' => 'desc_type',
                'name' => 'descType',
                'cssCLasses' => [
                    TemplateSearch::TYPE_ALONG => 'success',
                    TemplateSearch::TYPE_APART => 'primary'
                ],
            ],
            ['class' => ActionColumn::className()],
        ],
    ]); ?>
</div>