<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div class="field-add">
    
    
    <?php $form = ActiveForm::begin([
        'id' => 'field-add-form',
    ]);
    ?>
    <label class="control-label" for="field-title">Title</label>
    <?= $form->field($model, 'title')->label(false)->input(
        'text', [
        'placeholder'=>'Field name',
        'required' => false
    ]);
    ?>
    <?= $form->field($model, 'cat_id')->hiddenInput()->label(false) ?>
    <br/>
    <div class="form-group add-field-btn">
        <?= Html::submitButton('Save', ['class' => 'n_save btn btn-block btn-info']) ?>
        <?=Html::a('Cancel', null, [
                    'class' => 'btn n_save btn-block btn-default',
                    'id' => 'cancel-field-' . $model->cat_id,
                    'onclick' => ' 
                        $("#new_field_' . $model->cat_id . '").html("");
                        $("#detail_add_link_'. $model->cat_id .'").show();
                        return false;
                    '
            ]);?>
    </div>
    <?php ActiveForm::end(); ?>
    
</div>