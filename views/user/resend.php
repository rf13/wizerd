<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Reconfirmation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-resend">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?php if (Yii::$app->session->hasFlash('info')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::$app->session->getFlash('info'); ?>
                </div>
            <?php else: ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
                    </div>
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'resend-form',
                            'enableAjaxValidation' => true,
                            'enableClientValidation' => false,
                        ]); ?>
                        <?= $form->field($model, 'email')
                            ->textInput(['autofocus' => true]) ?>
                        <?= Html::submitButton('Continue', ['class' => 'btn btn-primary btn-block']) ?><br>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
