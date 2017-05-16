<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\forms\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
$this->title = 'Contact us';
$script = <<< JS
$(document).ready(function(){	
switch (window.location.pathname) {
    case '/contact':
        $('#main_nav_bar').addClass('tech')
    case '/contact':
    case '/contact':
        $('#main_nav_bar').addClass('tech')
}
});
JS;
$this->registerJs($script);
?>
<div class="site-contact">
    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
        <div class="alert alert-success">
            Thank you for contacting us. We really value your input and will respond shortly.
        </div>
    <?php else: ?>

        <div class="container">       
             <div class="col-md-12">	
             <h1 class="text-center heading-margins"><?= Html::encode($this->title) ?></h1>
             </div>	<!--contact-title-->                                       	
                <div class="col-md-12 contact_comment clearfix">                                                
                <p>Hello there.</p>
                <p>Send us a message and say hi.</p>
                <p>We love hearing from our customers.</p>
                </div>                
                <div class="col-md-5 contact-form-wrapper">                                
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <?= $form->field($model, 'name')->input('text',['placeholder'=>'Name'])->label(false); ?>
                <?= $form->field($model, 'email')->input('text',['placeholder'=>'Email'])->label(false); ?>
                <?= $form->field($model, 'subject')->input('text',['placeholder'=>'Subject'])->label(false); ?>
                <?= $form->field($model, 'body')->textArea(['rows' => 6, 'placeholder'=>'Message'])->label(false); ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-4" style="text-align: center;">{image}<a href="#" onclick="$(\'#contactform-verifycode-image\').trigger(\'click\'); return false;">Refresh captcha</a></div><div class="col-lg-8">{input}</div></div>',
                'options'=>[
                'placeholder' => 'Enter security code',
                'class' => "col-lg-12 form-control"
                ],
                
                ])->label(false); ?>
                <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-info', 'name' => 'contact-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
                </div>   
        </div>
    <?php endif; ?>
</div>