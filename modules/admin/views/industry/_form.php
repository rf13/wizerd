<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Industry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="industry-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'disclaimer')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'price')->dropdownList($model::getTemplateArray())?>
    <?= $form->field($model, 'time')->dropdownList($model::getTimeArray())?>
    <?= $form->field($model, 'srv_title')->dropdownList($model::getTitleShowArray())?>
    <?= $form->field($model, 'srv_desc')->dropdownList($model::getDescReqArray())?>
    <?= $form->field($model, 'display')->dropdownList($model::getStatusesArray())?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
