<?php
/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $industries array */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div class="menu-add">
    
    <?php $form = ActiveForm::begin([
        'id' => 'menu-form',
    ]);
    ?>
    <?= $form->field($model, 'title')->input(
        'text', [
        'placeholder'=>'Enter section name',
    ]);
    ?>
    <div class="form-group no-margin-bottom">
        <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-warning']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    
</div>