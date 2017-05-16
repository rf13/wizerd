<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Faq */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="faq-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'question')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'answer')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'type')->dropdownList($model::getStatusesArray()) ?>
    <?= $form->field($model, 'status')->dropdownList($model::getTypesArray()) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>