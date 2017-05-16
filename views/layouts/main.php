<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\alert\AlertBlock;
use yii\widgets\ActiveForm;
use app\models\User;

$action = Yii::$app->controller->action->id;
$view = Yii::$app->getView();
$content_white = '';

if ((isset($_GET['SearchForm']) && $action == "index") || $action == 'business-page' || $action == 'business') {
    $content_white = 'content-white';
}
$navExtraClass = '';
if ($action == 'index') {
    if (isset($_GET['SearchForm'])) {
        $navExtraClass = 'lightorng ';
    } else {
        $navExtraClass = 'stylenone ';
    }
}

$n_menu_main = '';
if ($content_white != 'content-white' && $action != 'business-page' && $action != 'business') {
    $n_menu_main = 'n_menu_main';
}

AppAsset::register($this);

$script = <<< JS
$(document).ready(function () {
$('.dropdown-toggle').dropdown();
});
JS;
$this->registerJs($script);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<script type="text/javascript">
    window.isMobile = {
        Android: function() { return navigator.userAgent.match(/Android/i); },
        BlackBerry: function() { return navigator.userAgent.match(/BlackBerry/i); },
        iOS: function() { return navigator.userAgent.match(/iPhone|iPad|iPod/i); },
        Opera: function() { return navigator.userAgent.match(/Opera Mini/i); },
        Windows: function() { return navigator.userAgent.match(/IEMobile/i); },
        any: function() { return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows()); }
    };
</script>
<?php $this->beginBody() ?>

<div class="wrap <?php echo $content_white; ?>">
    <div class="row">
        <nav id="main_nav_bar" class="<?php echo $navExtraClass; ?>navbar-default  navbar common-navwrapper <?php echo $n_menu_main; ?>"
             role="navigation">
            <div class="container-fluid">
                <?php /*?><div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main_nav_bar-collapse"><span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <?php if (!isset($_GET['SearchForm']) && $action == "index") : ?>
                    <?php else : ?>
                    <?= Html::a(Html::img('@web/images/logo.png', ["class" => "img-responsive"]), Yii::$app->homeUrl, ["class" => "navbar-brand"]) ?>
                    <?php endif; ?>
                    </div><?php */ ?>
                <?php /*?><div class="container n_search_div">
                            <?php
                                if (isset ($this->params['head_form']))
                                echo $this->params['head_form'];
                            ?>
                            <div class="col-md-4 col-md-offset-4 n_progress">
                                <ul>                                    
                                    <?php
                                    if (Yii::$app->user->can('business')) {
                                        $user = Yii::$app->user->identity;
                                        $complete = $user->getBusinessAccount();
                                        $percent = 50;
                                        foreach ($complete as $key => $value) {
                                            if (!empty($value)) $percent += 10;
                                        }
                                        if ($percent < 40) {
                                            $label = 'Setup: ' . $percent . '%';
                                            $barClass = 'progress-bar-danger';
                                        } else if ($percent > 70) {
                                            $label = 'Account setup: ' . $percent . '%';
                                            $barClass = 'progress-bar-success';
                                        } else {
                                            $label = 'Account setup: ' . $percent . '% complete';
                                            $barClass = 'progress-bar-warning';
                                        }
                                        if ((!isset ($this->params['head_form']))&&($percent<100)) {
                                            echo '<li>'.Html::a( yii\bootstrap\Progress::widget([
                                                    'id' => 'bus_account_progress',
                                                    'label' => $label,
                                                    'percent' => $percent,
                                                    'barOptions' => ['class' => $barClass]
                                                ]),Url::to(['/user/account', 'active' => 'setup'])) . '</li>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div><?php */ ?>
                <div id="main_nav_bar-collapse">
                    <div class="navbar-header">
                        <a href="/"><?php echo Html::img('@web/images/logo-innerpages.png',
                                ['class' => 'img-responsive']); ?></a>
                    </div>
                    <ul id="main_menu" class="navbar-nav navbar-right nav nav nav-pills">
                        <?php /*?><li class="social" style="display: none;">
                                    <?= Html::a(Html::img('@web/images/facebook.png'), 'https://www.facebook.com/', ['target' => '_blank', 'class' => 'social']) ?>
                                </li><?php */ ?>
                        <li class="social">
                            <?php
                            if (Yii::$app->user->isGuest) {
                                echo '<li>' . Html::a('How it works', Url::toRoute('/site/instruction'), []) . '</li>';
                                echo '<li>' . Html::a('Log in', Url::toRoute('/user/login'), []) . '</li>';

                            } else {
                                if (Yii::$app->user->can('consumer')) {
                                    //echo '<li>' . Html::a('Support', Url::toRoute('/site/faq'), []) . '</li>';
                                } else {
                                    if (Yii::$app->user->can('admin')) {
                                        echo '<li>' . Html::a('Admin panel', Url::toRoute('/admin/panel/index'), [])
                                            . '</li>';

                                    } else {
                                        echo '<li>' . Html::a('Business support',
                                                Url::toRoute('/site/business-support'), []) . '</li>';
//                                        (free '.'<i class="fa fa-smile-o"></i> )
                                    }
                                }
                            }
                            ?>
                        <li>
                            <?php
                            if (Yii::$app->user->isGuest) {
                                echo Html::a('Sign up', Url::toRoute('/user/register'), [
                                    'class' => 'blue-clr',
                                ]);

                            } else {

                                if (Yii::$app->user->can('business')) {
                                    $user = Yii::$app->user->identity;
                                    $business = $user->getBusiness();
                                    ?>
                                    <div class="dropdown">
                                        <?= Html::a(Html::encode(($business->name != '')
                                            ? $business->name
                                            : 'My account'), null, [
                                            'data-toggle' => 'dropdown',
                                            'class' => 'dropdown-toggle',
                                        ]) ?>
                                        <?php
                                        echo \yii\bootstrap\Dropdown::widget([
                                            'items' => [
                                                '<div class="n_drop_arrow "></div>',
                                                [
                                                    'label' => 'Account settings',
                                                    'url' => Url::to('/user/account')
                                                ],
                                                [
                                                    'label' => 'Public Wizerd site',
                                                    'url' => ($business->hasVanityName())
                                                        ? Url::to('/' . Html::encode($business['vanity_name']))
                                                        : Url::to('/site/business-page')
                                                ],
                                                [
                                                    'label' => 'Sign out',
                                                    'url' => Url::toRoute('/user/logout'),
                                                    'linkOptions' => ['data-method' => 'post']
                                                ],
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                    <?php
                                } else {
                                    if (Yii::$app->user->can('consumer')) {
                                        $user = User::findIdentity(Yii::$app->user->id);
                                        $name = $user->getUsername();
                                        ?>
                                        <div class="dropdown">
                                            <?= Html::a(Html::encode($name), null, [
                                                'data-toggle' => 'dropdown',
                                                'class' => 'dropdown-toggle',
                                            ]) ?>
                                            <?php
                                            echo \yii\bootstrap\Dropdown::widget([
                                                'items' => [
                                                    '<div class="n_drop_arrow "></div>',
                                                    [
                                                        'label' => 'Account settings',
                                                        'url' => Url::to('/user/account')
                                                    ],
                                                    [
                                                        'label' => 'Sign out',
                                                        'url' => Url::toRoute('/user/logout'),
                                                        'linkOptions' => ['data-method' => 'post']
                                                    ],
                                                ],
                                            ]);
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container n_search_div">
            <?php
            if (isset ($this->params['head_form'])) {
                echo $this->params['head_form'];
            }
            ?>
            <div class="col-md-4 col-md-offset-4 n_progress">
                <ul>
                    <?php
                    if (Yii::$app->user->can('business')) {
                        $user = Yii::$app->user->identity;
                        $complete = $user->getBusinessAccount();
                        $percent = 50;
                        foreach ($complete as $key => $value) {
                            if (!empty($value)) {
                                $percent += 10;
                            }
                        }
                        if ($percent < 40) {
                            $label = 'Setup: ' . $percent . '%';
                            $barClass = 'progress-bar-danger';
                        } else {
                            if ($percent > 70) {
                                $label = 'Account setup: ' . $percent . '%';
                                $barClass = 'progress-bar-success';
                            } else {
                                $label = 'Account setup: ' . $percent . '% complete';
                                $barClass = 'progress-bar-warning';
                            }
                        }
                        if ((!isset ($this->params['head_form'])) && ($percent < 100)) {
                            echo '<li>' . Html::a(yii\bootstrap\Progress::widget([
                                    'id' => 'bus_account_progress',
                                    'label' => $label,
                                    'percent' => $percent,
                                    'barOptions' => ['class' => $barClass]
                                ]), Url::to([
                                    '/user/account',
                                    'active' => 'setup'
                                ])) . '</li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="row margin-top"><!--new N-->
        <?= $content ?>
    </div><!--new N-->
    <div class="clear"></div>
</div>
<footer class="footer">
    <div class="container-fluid text-center">
        <p>
            <?= Html::a('About', Url::toRoute('site/about')) ?>
            <?= Html::a('Contact', Url::toRoute('site/contact')) ?>
            <?= Html::a('FAQ', Url::toRoute('site/faq')) ?>
            <?= Html::a('How it works', Url::toRoute('/site/instruction')) ?>
            <?= Html::a('Privacy', Url::toRoute('site/privacy')) ?>
            <?= Html::a('Terms', Url::toRoute('site/terms')) ?>
        </p>

        <p class="pull-right">
            <?php
            if (Yii::$app->user->isGuest) {

            }
            ?>
        </p>
    </div>
</footer>
<?php $this->endBody() ?>
<script type="text/javascript">
    var clicky_site_ids = clicky_site_ids || [];
    clicky_site_ids.push(100960804);
    (function () {
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = '//static.getclicky.com/js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(s);
    })();
</script>
<noscript>
    <p>
        <img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100960804ns.gif"/>
    </p>
</noscript>
</body>
</html>
<?php $this->endPage() ?>
