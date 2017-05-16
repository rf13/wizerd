<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\forms\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Log in';
$script = <<< JS
$(document).ready(function(){	
switch (window.location.pathname) {
    case '/log-in':
        $('#main_nav_bar').addClass('tech')
    case '/log-in':
    case '/log-in':
        $('#main_nav_bar').addClass('tech')
}
});
JS;
$this->registerJs($script);

//$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$('#loginform-is_mobile').val(window.isMobile.any());

JS;
$this->registerJs($script);
?>
<div class="user-login">
    <div class="container">
    <div class="row">
    <div class="col-md-12 about_us_text">
    <h1 class="text-center heading-margins"><?= Html::encode($this->title) ?></h1>
    </div>	<!--./about_us_text-->
    </div>    
    </div>	<!--container-->   
        <div class="container">
            <div class="col-md-3 login-wrapper clearfix">
                <?php $form = ActiveForm::begin([
                    'id'                     => 'login-form',
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                ]); ?>
                <?= $form->field($model, 'email', ['errorOptions' => ['encode' => false]])
                         ->input('email')->label('Email address');
                ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div>{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                ]) ?>
                <?= $form->field($model, 'is_mobile')->hiddenInput()->label(false) ?>
                <div class="form-group">
                    <?= Html::submitButton('Sign in', [
                        'class' => 'btn btn-info',
                        'name' => 'login-button'
                    ]) ?>
                </div>
                <?php ActiveForm::end(); ?>
                <p class="login_cr_email">
                    <?= Html::a('Lost password', Url::toRoute('user/recovery')) ?>
					<?= Html::a('Lost email', Url::toRoute('site/contact')) ?>
                </p>                
            </div>
        </div>
</div>
