<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\forms\RecoveryForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Reset your password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id'                     => 'recovery-form',
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                ]); ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= Html::submitButton('Save', ['class' => 'btn btn-warning btn-block']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
