<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
use app\models\User;

if(isset($singlePage) && $singlePage) {
    $this->title = 'Sign up';
}
//$this->params['breadcrumbs'][] = $this->title;
$script = <<< JS
$('.field-registrationform-zip_code').addClass('required');
$('#registrationform-zip_code').on('change', function (e) {
    var zip =  String(this.value),
        url = $('input[name=zip_url]').val();
    if (zip.length == 5) {
        $.get(url, {zip: zip})
            .done(function(count) {
                if (count == 0){
                    $("#zip_to_email").val(zip);
                    $("#registerModal").modal("show");
                }
                else{

                }
            }
        );
    }
});

$('#registerModal').on('hidden.bs.modal', function (e) {
   $(".field-savewaitemail-industry").removeClass('has-error');
   $(".field-savewaitemail-email").removeClass('has-error');
   $(".field-savewaitemail-industry .help-block").html("");
   $(".field-savewaitemail-email .help-block").html("");
});

function createUserTypeDropdown() {
    var options = [],
        html = '<label class="control-label" for="registrationform-first_name">I’m signing up as a</label>';
    options[0] = {name: '-Choose account type-', value: ''};
    options[1] = {name: 'Consumer', value: 'consumer'};
    //if (!window.isMobile.any()) {
        options[2] = {name: 'Business', value: 'business'};
    //}
    html += '<select id="registrationform-role" class="form-control">';
    for (i = 0; i < options.length; i++) {
        html += '<option value="' + options[i].value + '">' + options[i].name + '</option>';
    }
    html += '</select>';
    $('#for-registrationform-role').append(html);
}

createUserTypeDropdown();

$(document).on('change', '#registrationform-role', function() {
    var role = $(this).val();
    $('#for_business').hide();
    $('#for_consumer').hide();
    $('#for_all').hide();
    if (role) {
        $('#for_' + role).show();
        $('#for_all').show();
    }
    $('input[name="RegistrationForm[role]"]').val(role);
});

$(document).ready(function(){
switch (window.location.pathname) {
    case '/sign-up':
        $('#main_nav_bar').addClass('tech')
    case '/sign-up':
    case '/sign-up':
        $('#main_nav_bar').addClass('tech')
}
});

JS;
$this->registerJs($script);

?>
<div class="user-register">

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-error">
            <p>
                sorry, there was a problem during:
                <?php echo Yii::$app->session->getFlash('error'); ?>
            </p>
        </div>
    <?php    endif; ?>

    <?php if (Yii::$app->session->hasFlash('warning')): ?>
        <div class="alert alert-warning">
            <p>
                <?php echo Yii::$app->session->getFlash('warning'); ?>
            </p>
        </div>
        <?php   endif; ?>
<!---->
<!--    <p>Welcome message...</p>-->
<!--    <hr>-->
<!--    <br>-->
    <?php if(isset($singlePage) && $singlePage) { ?>
        <div class="container-fluid">        
        <div class="container">
        <div class="col-md-12 about_us_text">
        <h1 class="text-center heading-margins"><?= Html::encode($this->title) ?></h1>
        <?= Html::hiddenInput('zip_url', Url::toRoute('/user/check-zip')) ?>
        </div>	<!--./about_us_text-->
        </div>	<!--container-->                    
        </div>
    <?php } ?>

    <?php if (Yii::$app->session->hasFlash('info')): ?>
        <div class="alert alert-success">
            <?php echo Yii::$app->session->getFlash('info'); ?><br/>
        </div>
    <?php else: ?>
            <div class="col-md-3 signup-form-wrapper">
                <div id="for-registrationform-role" class="form-group"></div>
                <?php
                $form = ActiveForm::begin([
                    'id' => 'registration-form2',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]);
                ?>
                <?= $form->field($model, 'role')->hiddenInput()->label(false) ?>
                <div id="for_business" style="display: none;">
                    <?= $form->field($model, 'zip_code') ?>
                    <div class="form-group check_zip_code" style="display: none;">
                        <?php
                        $message = 'Sorry, but we have not launched in your city yet. ';
                        $message .= 'Please leave your email address and what type of business you operate. ';
                        $message .= 'We will notify you when Wizerd is live in your city. Thanks.';


                        echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $message]);
                        ?>
                    </div>
                    <?=
                    $form->field($model, 'main_cat')->dropdownList(
                        ArrayHelper::map(app\models\Industry::getIndustryTitles(), 'id', 'title'), [
                        'prompt' => '-Choose a Category-',
                    ]);
                    ?>
                </div>

                <div id="for_consumer" style="display: none;">
                    <?= $form->field($model, 'first_name') ?>
                    <?= $form->field($model, 'last_name') ?>
                </div>
                <div id="for_all" style="display: none;">
                    <?= $form->field($model, 'email')->input('email') ?>
                    <?= $form->field($model, 'confirm_email')->input('email') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'confirm_password')->passwordInput() ?>
                    <?= Html::submitButton('Sign up', ['class' => 'btn btn-info']) ?>
                    <?php ActiveForm::end(); ?>

                    <p class="login_cr_email">
                        <?= Html::a('Already registered? Sign in here.', Url::toRoute('/user/login')) ?>
                    </p>
                </div>
            </div>

    <?php endif; ?>
</div>

<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal_head_message"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">Cancel</span></button>
            </div>
            <div class="modal-body">
                <div class="modal-body-text">
                    <p>Sorry, but we have not launched in your city yet.</p>

                    <p>Please leave your email address and what type of business you operate.</p>

                    <p>We will notify you when Wizerd is live in your city. Thanks.</p>

                </div>
                <?php
                $emailForm = ActiveForm::begin([
                    'id' => 'save_wait_email',
                    'action' => Url::to(['site/save-wait-email'])]);
                echo $emailForm->field($waitEmail, 'email')->input('email');
                echo $emailForm->field($waitEmail, 'industry')->input('text');
                echo Html::hiddenInput('SaveWaitEmail[zip]', null, ['id' => 'zip_to_email']);
                echo Html::submitButton('Submit', ['class' => 'btn btn-info', 'name' => 'submit-button']);
                $emailForm::end();
                ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
