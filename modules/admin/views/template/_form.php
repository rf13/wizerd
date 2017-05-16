<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Template */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="template-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'count')->textInput() ?>
    <?= $form->field($model, 'desc_type')->dropDownList($model::getDescArray()) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>