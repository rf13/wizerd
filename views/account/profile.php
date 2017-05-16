<?php
/* @var $this yii\web\View */
/* @var $tab string */

use yii\bootstrap\Tabs;
use yii\helpers\Url;
use yii\helpers\Html;

$tabs = array(
    'public',
    'operation',
    'review',
    'settings',
    'site'
);
?>
<div class="form-group col-md-12">
    <div class="btn-group pull-right">
        <?= Html::button('Tip', ['class' => 'btn btn-default btn-tip-show',
                                 'onclick' => 'for_setup_tip()'
        ]); ?>
        <?= Html::button('Hide tip', ['class' => 'btn btn-default btn-tip-hide hidden',
                                      'onclick' => 'for_setup_tip()'

        ]); ?>
    </div>
</div>
<div class="panel panel-default panel-tip col-md-12">
    <div class="panel-body">

        <p>Tip: Building your profile</p>

        <p>
            Your profile is made up of 5 sections.
            Below are details for each section.
        </p>

        <p>
            Public: This information is shown to consumers on your Wizerd site.
            No PO Boxes are allowed.
            If you’re a home based business, or act as a independent contractor, then select the check box at the beginning.
        </p>

        <p>Hours: List your hours of operations.</p>

        <p>
            Reviews: Go to your Yelp profile page.
            Copy your Yelp profile URL and paste it in the input field.
            Your Yelp stars will be displayed on your Wizerd site.
        </p>

        <p>
            Settings: Change your email and/or password.
            Choose a URL for your Wizerd site.
            This URL can only be changed once.
        </p>

        <p>
            Wizerd site: This is your Wizerd site URL.
            Copy and paste this link on your social media accounts to promote your business and get new customers!
        </p>
        <div class="pull-right">
        </div>
    </div>
</div>

<div class="business-profile">

    <?php
    echo Tabs::widget([
        'id' => 'business_profile_tabs',
        'items' => [
            [
                'encode' => false,
                'label' => '<i class="hidden-sm glyphicon glyphicon-eye-open"></i>' .
                    '<span class="hidden-xs">Public profile</span>',
                'headerOptions' => ['data' => [
                    'url' => Url::toRoute('account/public'),
                    'content' => 'profile_public'
                ]],
                'options' => ['id' => 'profile_public'],
                'active' => (empty($tab) || ($tab == $tabs[0]) || !in_array($tab, $tabs))
            ],
            [
                'encode' => false,
                'label' => '<i class="hidden-sm glyphicon glyphicon-time"></i>' .
                    '<span class="hidden-xs">Hours of operation</span>',
                'headerOptions' => ['data' => [
                    'url' => Url::toRoute('account/operation'),
                    'content' => 'profile_hours'
                ]],
                'options' => ['id' => 'profile_hours'],
                'active' => ($tab == $tabs[1])
            ],
            [
                'encode' => false,
                'label' => '<i class="hidden-sm glyphicon glyphicon-link"></i>' .
                    '<span class="hidden-xs">Reviews</span>',
                'headerOptions' => ['data' => [
                    'url' => Url::toRoute('account/review'),
                    'content' => 'profile_review'
                ]],
                'options' => ['id' => 'profile_review'],
                'active' => ($tab == $tabs[2])
            ],
            [
                'encode' => false,
                'label' => '<i class="hidden-sm glyphicon glyphicon-wrench"></i>' .
                    '<span class="hidden-xs">Account settings</span>',
                'headerOptions' => ['data' => [
                    'url' => Url::toRoute('account/settings'),
                    'content' => 'profile_settings'
                ]],
                'options' => ['id' => 'profile_settings'],
                'active' => ($tab == $tabs[3])
            ],
            [
                'encode' => false,
                'label' => '<i class="hidden-sm glyphicon glyphicon-globe"></i>' .
                    '<span class="hidden-xs">Wizerd site</span>',
                'headerOptions' => ['data' => [
                    'url' => Url::toRoute('account/site'),
                    'content' => 'profile_site'
                ]],
                'options' => ['id' => 'profile_site'],
                'active' => ($tab == $tabs[4])
            ]
        ]
    ]);
    ?>

</div>
