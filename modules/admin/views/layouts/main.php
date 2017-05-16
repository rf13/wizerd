<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use kartik\alert\AlertBlock;
use app\modules\admin\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    $selectedMenuItem = Yii::$app->controller->id;
    NavBar::begin([
        'brandLabel' => Yii::$app->name . ' Dashboard',
        'brandUrl' => Url::to(['/admin/panel/index']),
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => [
            'class' => 'navbar-nav navbar-right',
        ],
        'encodeLabels' => false,
        'activateParents' => true,
        'items' => [
            [
                'label' => '<span class="glyphicon glyphicon-user"></span> Business',
                'items' => [
                    [
                        'label' => '<span class="glyphicon glyphicon-cog"></span> Manage',
                        'url' => Url::to('/admin/business'),
                        'active' => ($selectedMenuItem == 'business')
                    ],
                    [
                        'label' => '<span class="glyphicon glyphicon-upload"></span> Business Bulk Upload',
                        'icon' => 'upload',
                        'url' => Url::to('/admin/business-bulk-upload/index'),
                        'active' => ($selectedMenuItem == 'business-bulk-upload')
                    ],
                ]
            ],
            [
                'label' => 'Sign out',
                'url' => ['/user/logout'],
                'linkOptions' => ['data-method' => 'post']
            ]
        ],
    ]);
    NavBar::end();
    ?>
    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => [
                'label' => 'Admin',
                'url' => Url::to(['/admin/panel/index']),
            ],
            'links' => isset($this->params['breadcrumbs'])
                ? $this->params['breadcrumbs']
                : [],
        ]) ?>

        <?= AlertBlock::widget([
            'type' => AlertBlock::TYPE_GROWL,
            'useSessionFlash' => true
        ]); ?>
        <div class="row">
            <div class="col-sm-12">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="pull-left">
            <?= Html::a('Privacy', Url::toRoute('site/privacy')) ?>
            <?= Html::a('Terms', Url::toRoute('site/terms')) ?>
        </p>

        <p class="pull-right">
            <?= Html::a('About', Url::toRoute('site/about')) ?>
            <?= Html::a('Contact', Url::toRoute('site/contact')) ?>
            <?= Html::a('FAQ', Url::toRoute('site/faq')) ?>
        </p>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
