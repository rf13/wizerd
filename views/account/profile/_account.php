<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\forms\ProfileSettingsForm */
/* @var $consumer bool */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

$script = <<< JS

id='vanitynameform-vanity_name';
$('#'+id).on('keyup', function (e) {

   val=$('#'+id).val().toLowerCase();
   rep=val.replace( /[^a-z0-9\s\-]/g,'').replace(/[\s]/g,'-').replace(/[\-]+/g,'-');
   $('#'+id).val(rep);

}).on('change', function (e) {
   val=$('#'+id).val().toLowerCase();
   rep=val.replace( /[^a-z0-9\s\-]/g,'').replace(/[\s]/g,'-');
   $('#'+id).val(rep);
})
;
JS;
$this->registerJs($script);

$this->title = 'Account Settings';
?>
<div class="user-account-profile-settings gray-content">
    <?php // Yii::$app->tip->display('text 111 text 111 text 111 text 111 text'); ?>
    <?php $form = ActiveForm::begin([
        'id' => 'profile-settings-form',
        'options' => ['class' => 'form-horizontal'],
        'layout' => 'horizontal',
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-3',
                'wrapper' => 'col-sm-8 account-input-div',
                'error' => '',
            ],
        ],
    ]); ?>
    <div class="row">
        <div class="col-sm-9">
            <?php if ($consumer): ?>
                <div class="row">
                    <div class="col-sm-11">
                        <?= $form->field($model, 'first_name')
                            ->input('text', [
                                'readonly' => true,
                                'base_value' => $model->first_name
                            ])
                            ->label('First Name') ?>
                    </div>
                    <div class="col-sm-1">
                        <?php
                        echo Html::a('Edit', null, [
                            'class' => 'names btn-link ',
                            'id' => 'edit_names',
                            'onclick' => '
                            $(".names").toggleClass("hidden");
                            $("#profilesettingsform-first_name").prop("readonly",false);
                            $("#profilesettingsform-last_name").prop("readonly",false);
                        '
                        ]);
                        ?>
                        <?= Html::submitButton('Save', ['class' => 'names btn btn-info hidden']) ?>
                        <?php
                        echo Html::a('Cancel', Url::to([
                            '/user/account',
                            'active' => 'settings'
                        ]), [
                            'class' => 'names btn btn-sm btn-default hidden',
                            'id' => 'cancel_names',
                            'onclick' => '
                                $.ajax({
                                    type: "POST",
                                    url: $(this).attr("href"),
                                });
                        '
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-11">
                        <?= $form->field($model, 'last_name')
                            ->input('text', [
                                'readonly' => true,
                                'base_value' => $model->last_name
                            ])
                            ->label('Last Name') ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-sm-11">
                    <?= $form->field($model, 'current_email')
                        ->input('email', [
                            'readonly' => true,
                            'base_value' => $model->current_email
                        ])
                        ->label('Email') ?>
                    <?= $form->field($model, 'confirm_email')
                        ->input('email', ['class' => 'emails form-control hidden '])
                        ->label('Confirm email', ['class' => 'emails control-label col-sm-3 hidden']) ?>
                </div>
                <div class="col-sm-1">
                    <?php
                    echo Html::a('Edit', null, [
                        'class' => 'emails btn-link ',
                        'id' => 'edit_emails',
                        'onclick' => '
                                    $(".emails").toggleClass("hidden");
                                    $("#profilesettingsform-current_email").prop("readonly",false);

                        '
                    ]);
                    ?>
                    <?= Html::submitButton('Save', ['class' => ' emails btn  btn-info hidden']) ?>
                    <?php
                    echo Html::a('Cancel', Url::to([
                        '/user/account',
                        'active' => 'settings'
                    ]), [
                        'class' => 'emails btn btn-sm  btn-default hidden',
                        'id' => 'cancel_emails',
                        'onclick' => '
                                $.ajax({
                                    type: "POST",
                                    url: $(this).attr("href"),
                                });
                        '
                    ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-11">
                    <?= $form->field($model, 'password')
                        ->passwordInput([
                            'placeholder' => '********',
                            'readonly' => true
                        ])
                        ->label('Password', ['id' => 'password_label']) ?>
                    <?= $form->field($model, 'new_password')
                        ->passwordInput(['class' => 'passwords form-control hidden'])
                        ->label('New password', ['class' => 'passwords control-label col-sm-3 hidden']) ?>
                    <?= $form->field($model, 'confirm_password')
                        ->passwordInput(['class' => 'passwords form-control hidden'])
                        ->label('Confirm password', ['class' => 'passwords control-label col-sm-3 hidden']) ?>
                </div>
                <div class="col-sm-1">
                    <?php
                    echo Html::a('Edit', null, [
                        'class' => 'passwords btn-link ',
                        'id' => 'edit_password',
                        'onclick' => '
                                    $(".passwords").toggleClass("hidden");
                                    $("#password_label").html("Current password");
                                    $("#profilesettingsform-password").prop("readonly",false).prop("placeholder","");
                        '
                    ]);
                    ?>
                    <?= Html::submitButton('Save', ['class' => 'passwords btn btn-info hidden']) ?>
                    <?php
                    echo Html::a('Cancel', Url::to([
                        '/user/account',
                        'active' => 'settings'
                    ]), [
                        'class' => 'passwords btn  btn-default hidden',
                        'id' => 'cancel_password',
                        'onclick' => '
                            $.ajax({
                                type: "POST",
                                url: $(this).attr("href"),
                            });
                        '
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?php $formVanity = ActiveForm::begin([
        'action' => Url::to('/account/settings-vanity'),
        'validationUrl' => Url::to('/account/settings-vanity'),
        'id' => 'vanity-settings-form',
        'fieldConfig' => [
            'template' => "<div class=\"col-sm-3 n_profile_account_label\">{label}</div><div class=\" col-sm-8 n_profile_account_input input-group\"><span class=\"input-group-addon\">http://wizerd.com/</span>{input}</div>{error}",
        ],
    ]); ?>

    <?php
    $disabled = false;
    if ($vanity->vanity_changed > 0) {
        $disabled = true;
    }

    ?>
    <?php if (!$model->is_consumer) { ?>
        <div class="row">
            <div class="col-sm-9 ">
                <div class="row">
                    <div class="col-sm-11">
                        <?= $formVanity->field($vanity, 'vanity_name', [
                            'enableAjaxValidation' => true,
                            'wrapperOptions' => []
                        ])
                            ->input('text', [
                                'class' => 'form-control',
                                'disabled' => $disabled,
                                'placeholder' => 'business-name-example'
                            ])
                            ->label('Wizerd URL') ?>
                    </div>
                    <div class="col-sm-1">
                        <?php
                        if ($vanity->vanity_changed < 2) {

                            if ($vanity->vanity_changed != 0) {
                                echo Html::a('Edit', null, [
                                    'class' => 'vanity_btn btn-link ',
                                    'id' => 'edit_vanity',
                                    'onclick' => '
                                 if (confirm("WARNING! You can only change your Wizerd URL once. After that the URL becomes permanent.") ) {
                                    $(".vanity_btn").toggleClass("hidden");
                                    $("#vanitynameform-vanity_name").prop("disabled",false);
                                 }
                             '
                                ]);
                            }
                            ?>
                            <?php
                            if ($vanity->vanity_changed != 0) {
                                $save_hidden = 'hidden';
                            } else {
                                $save_hidden = '';
                            }
                            echo Html::submitButton('Save',
                                ['class' => 'btn vanity_btn btn-sm btn-info ' . $save_hidden]);

                            ?>
                            <?= Html::a('Cancel', Url::to([
                                '/user/account',
                                'active' => 'settings'
                            ]), [
                                'class' => 'vanity_btn btn btn-sm  btn-default hidden',
                                'id' => 'cancel_vanity',
                                'onclick' => '
                                    $.ajax({
                                       type: "POST",
                                      url: $(this).attr("href"),
                                    });
                            '
                            ]);
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 n_profile_account_text">
                        <p>
                            Choose your personalized Wizerd site URL. It should resemble your business name. You can
                            give consumers this link to promote your business. After the URL is set, it can only be
                            changed once.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>
