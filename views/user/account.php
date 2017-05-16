<?php
/* @var $this yii\web\View */
/* @var $business bool */
/* @var $username string */
/* @var $active string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;

$mobileRedirectUrl = Url::to('/business-no-access');
$script = <<< JS
if (window.isMobile.any() && '$business' == '1') {
    window.location.href = '$mobileRedirectUrl';
}

$('.n_menu_ul').wrap(function(){
        return "<div class='n_menu_div container-fluid'></div>";		
     });
JS;
$this->registerJs($script);

$script = <<< JS
$(document).ready(function(){	
switch (window.location.pathname) {
    case '/user/account':
        $('#main_nav_bar').addClass('lightorng')
    case '/user/account':
    case '/user/account':
        $('#main_nav_bar').addClass('lightorng')
}
});
JS;
$this->registerJs($script);

$this->registerJsFile(Yii::getAlias('@web/js/account.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
if (!empty($username)) {
    $this->title = $username . ' - Settings';
} else {
    $this->title = 'Settings';
}
$tabs = array(
    'menu',
    'promo-s',
    'promo-d',
    'photo',
    'staff',
    'profile',
    'setup'
);
?>
<span style="display: none;">
<?= \dosamigos\multiselect\MultiSelect::widget([
    'data' => [''],
    'name' => 'spike-for-ajax-multi-select',
    'options' => [
        'style' => 'display:none;',
    ],
]) ?>
</span>
<div class="user-account container-fluid">
    <div class="container">
        <h2><?= Html::encode($this->title) ?></h2>
    </div>

    <div class="row">

        <?php if ($business === true): ?>
            <?php

            echo Tabs::widget([
                'id' => 'user_account_tabs',

                'items' => [
                    [
                        'encode' => false,

                        //Nedogarko - glyphicon glyphicon-th-list (old)
                        'label' => '<i class="hidden-sm "></i>' . '<span class="hidden-xs">Menu</span>',
                        'headerOptions' => [
                            'data' => [
                                'url' => Url::toRoute('account/menu'),
                                'content' => 'business_menu'
                            ]
                        ],
                        'options' => [
                            'id' => 'business_menu',
                            'class' => 'container'
                        ],
                        'active' => ($active == $tabs[0])
                    ],
                    [
                        'encode' => false,

                        //Nedogarko - glyphicon glyphicon-user (old)
                        'label' => '<i class="hidden-sm "></i>' . '<span class="hidden-xs">Promotion</span>',
                        'headerOptions' => [
                            'data' => [
                                'url' => Url::toRoute('account/promo-double'),
                                'content' => 'business_promo_double'
                            ]
                        ],
                        'options' => [
                            'id' => 'business_promo_double',
                            'class' => 'container'
                        ],
                        'active' => ($active == $tabs[2])
                    ],
                    [
                        'encode' => false,
                        //Nedogarko - glyphicon glyphicon-camera (old)
                        'label' => '<i class="hidden-sm "></i>' . '<span class="hidden-xs">Photo</span>',
                        'headerOptions' => [
                            'data' => [
                                'url' => Url::toRoute('account/photo'),
                                'content' => 'business_photo'
                            ]
                        ],
                        'options' => [
                            'id' => 'business_photo',
                            'class' => 'container'
                        ],
                        'active' => ($active == $tabs[3])
                    ],
                    //                    [
                    //                        'encode' => false,
                    //
                    //                        //Nedogarko - glyphicon glyphicon-user (old)
                    //                        'label' => '<i class="hidden-sm "></i>' .
                    //                            '<span class="hidden-xs">Staff</span>',
                    //                        'headerOptions' => ['data' => [
                    //                            'url' => Url::toRoute('account/staff'),
                    //                            'content' => 'business_staff'
                    //                        ]],
                    //                        'options' => ['id' => 'business_staff', 'class' => 'container'],
                    //                        'active' => ($active == $tabs[4])
                    //                    ],
                    [
                        'encode' => false,
                        //Nedogarko - glyphicon glyphicon-user (old)
                        'label' => '<i class="hidden-sm "></i>' . '<span class="hidden-xs">Profile</span>',
                        'content' => $this->render('/account/profile', ['tab' => $active]),
                        'options' => [
                            'id' => 'business_profile',
                            'class' => 'container'
                        ],
                        'active' => ((empty($active) || !in_array($active, $tabs)) || ($active == $tabs[5]))
                    ],
                    [
                        'encode' => false,
                        //Nedogarko - glyphicon glyphicon-list-alt (old)
                        'label' => '<i class="hidden-sm "></i>' . '<span class="hidden-xs">Account setup</span>',
                        'headerOptions' => [
                            'data' => [
                                'url' => Url::toRoute('account/setup'),
                                'content' => 'business_setup'
                            ],
                            'style' => 'float:right;'
                        ],
                        'options' => [
                            'id' => 'business_setup',
                            'class' => 'container'
                        ],
                        'active' => ($active == $tabs[6])
                    ]
                ],
                'navType' => 'nav-pills container n_menu_ul'
            ]);

            ?>

        <?php else: ?>

            <?php
            echo Tabs::widget([
                'id' => 'user_account_tabs',
                'items' => [
                    [
                        'label' => 'Promotions',
                        'headerOptions' => [
                            'data' => [
                                'url' => Url::toRoute('account/promo-saved'),
                                'content' => 'promo_saved'
                            ]
                        ],
                        'options' => ['id' => 'promo_saved'],
                        'active' => ($active == $tabs[2])
                    ],
                    [
                        'label' => 'Account settings',
                        'headerOptions' => [
                            'data' => [
                                'url' => Url::toRoute('account/settings'),
                                'content' => 'profile_settings'
                            ],
                            'style' => 'float:right;'
                        ],
                        'options' => ['id' => 'profile_settings'],
                        'active' => ($active == $tabs[6])
                    ]
                ],
                'navType' => 'nav-pills container n_menu_ul'
            ]);
        endif;

        ?>
    </div>
    <div class="container">
        <?php

        Modal::begin([
            'header' => '<span id="modalHeaderTitle"></span>',
            'id' => 'formModal',
            'size' => 'modal-sm',
            'options' => array(
                'data-keyboard' => "false",
                'data-backdrop' => "static"
            ),
            'closeButton' => array(
                'label' => 'Cancel'
            )

        ]);
        ?>
        <div id='modalContent'>
            <div class="form-group">
                <div class="col-md-12 text-center">
                    <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
                </div>
            </div>
        </div>
        <?php Modal::end(); ?>
        <?php Modal::begin([
            'header' => '<span id="modalHeaderTitleLg"></span>',
            'id' => 'formModalLg',
            'size' => 'modal-lg',
            'options' => array(
                'data-keyboard' => "false",
                'data-backdrop' => "static",
            ),
            'closeButton' => array(
                'label' => 'Cancel',

            )

        ]);
        ?>
        <div id='modalContentLg'>
            <div class="form-group">
                <div class="col-md-12 text-center">
                    <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
                </div>
            </div>
        </div>
        <?php Modal::end(); ?>
    </div>
</div>
