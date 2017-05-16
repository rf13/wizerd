<?php
/* @var $this yii\web\View */
/* @var $model app\models\CustomCategory */
/* @var $menu array|null */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$script = <<< JS
var max_fields = 5;
var current_ct = 1;
var field_name = $('#customcategory-category').parent();
$('#add_custom_cat').click(function(e) {
    e.preventDefault();
    if(current_ct < max_fields) {
        var old_field = field_name;
        if (current_ct != 1) {
            old_field = '#custom_' + current_ct;
        }
        current_ct++;
        $(old_field).after(
            '<div class="form-group field-customcategory-fields" id="custom_' + current_ct + '">' +
                '<label class="control-label" for="customcategory-fields">Category</label>' +
                '<input type="text" id="customcategory-fields" class="form-control" name="CustomCategory[fields][]">' +
                '<p class="help-block help-block-error"></p>' +
            '</div>'
        );
    }
});
JS;
$this->registerJs($script);
?>
<div class="menu-custom-service-add">

    <?php
    $form = ActiveForm::begin(['id' => 'custom-category-form']);
    //$options = ['prompt' => '-Choose a Menu-'];
   
    $model->menu_id=Yii::$app->session->get('current-menu');
    ?>

    <label class="control-label" for="customcategory-menu_id">Section</label>
    <?php

    echo $form->field($model, 'menu_id')->label(false)->widget(\kartik\select2\Select2::classname(), [
        'hideSearch'=>true,
        'data' => ArrayHelper::map($menu, 'id', 'title'),
        'options' => [],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);


    ?>
    <label class="control-label" for="customcategory-category">Category</label>
        <?php // echo $form->field($model, 'menu_id')->dropDownList(ArrayHelper::map($menu, 'id', 'title')); ?>

        <?= $form->field($model, 'category')->label(false)->input('text',['placeholder'=>'Enter category name']); ?>
    <div class="form-group test_div">
    <?= Html::a('+ Add category', null, ['id' => 'add_custom_cat']); ?>
    </div>
    <div class="form-group">
<?= Html::submitButton('Save', ['class' => 'btn btn-block btn-warning']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>