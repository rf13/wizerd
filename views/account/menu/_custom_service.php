<?php
/* @var $this yii\web\View */
/* @var $model app\models\CustomService */
/* @var $menu array */
/* @var $category boolean|string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$script = <<< JS
var max_fields = 5;
var current_ct = 1;
var field_name = $('#customservice-title').parent();
$('#add_custom_srv').click(function(e) {
    e.preventDefault();
    if(current_ct < max_fields) {
        var old_field = field_name;
        if (current_ct != 1) {
            old_field = '#custom_srv_' + current_ct;
        }
        current_ct++;
        $(old_field).after(
            '<div class="form-group field-customservice-fields" id="custom_srv_' + current_ct + '">' +
                '<label class="control-label" for="customservice-fields">Service</label>' +
                '<input type="text" id="customservice-fields" class="form-control" name="CustomService[fields][]">' +
                '<p class="help-block help-block-error"></p>' +
            '</div>'
        );
    }
});
JS;
$this->registerJs($script);
?>

<div class="menu-custom-service-add">
    <?php $form = ActiveForm::begin(['id' => 'custom-service-form']); ?>
    <?php
    $model->menu_id = Yii::$app->session->get('current-menu');
    if ($category == false):
        $model->menu_id = Yii::$app->session->get('current-menu');
        ?>

        <?php

        echo $form->field($model, 'menu_id')->widget(\kartik\select2\Select2::classname(), [
            'hideSearch' => true,
            'data' => ArrayHelper::map($menu, 'id', 'title'),
            'options' => [
                'onchange' => '
                    $.post("' . Url::toRoute(['account/get-category']) . '", {menu: $(this).val()},
                    function(data) {
                    if(data){
                        $("#is_menu_cat_drop").html( data );
                        $("#is_menu_cat_drop").removeClass("hidden");
                        $("#is_menu_cat_input").addClass("hidden");
                    }
                    else{
                        $("#is_menu_cat_drop").addClass("hidden");
                        $("#is_menu_cat_input").removeClass("hidden");
                        }
                    })',

            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);


        ?>


        <?php
        /*
        echo $form->field($model, 'menu_id')->dropDownList(
                ArrayHelper::map($menu, 'id', 'title'), [
                //'prompt'   => '-Choose a Menu-',
                'onchange' => '
                    $.post("' . Url::toRoute(['account/get-category']) . '", {menu: $(this).val()},
                    function(data) {
                    if(data){
                        $("#is_menu_cat_drop").html( data );
                        $("#is_menu_cat_drop").removeClass("hidden");
                        $("#is_menu_cat_input").addClass("hidden");
                    }
                    else{
                        $("#is_menu_cat_drop").addClass("hidden");
                        $("#is_menu_cat_input").removeClass("hidden");
                        }
                    })'
                ]
        );
        */

        if (count($category_by_menu) > 1) {
            for ($i = 0; $i < count($category_by_menu); $i++)
                if ($category_by_menu[$i]['title'] == '') $category_by_menu[$i]['title'] = 'no category';
            $drop = '';
            $inp = 'hidden';
        } else {
            $drop = 'hidden';
            $inp = '';
        }
        ?>

        <?php

        echo $form->field($model, 'cat_id')->widget(\kartik\select2\Select2::classname(), [
            'hideSearch' => true,
            'data' => ArrayHelper::map($category_by_menu, 'id', 'title'),
            'options' => [
                'class' => 'form-control ' . $drop,
                'id' => 'is_menu_cat_drop'

            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);


      // echo $form->field($model, 'cat_id',[])->dropDownList(ArrayHelper::map($category_by_menu, 'id', 'title'), [ 'class' => 'form-control ' . $drop,'id'=>'is_menu_cat_drop']);
        ?>
        <?= Html::input('text', null, 'no category', ['class' => 'form-control  ' . $inp, 'disabled' => true, 'id' => 'is_menu_cat_input']); ?>

    <?php else: ?>
        <?= $form->field($model, 'cat_id')->hiddenInput()->label(false) ?>
        <div class="form-group required">
            <label class="control-label">Section</label>
            <input type="text" class="form-control" name="menu" value="<?= $menu ?>" readonly>
        </div>
        <div class="form-group required">
            <label class="control-label">Category</label>
            <input type="text" class="form-control" name="category" value="<?= $category ?>" readonly>
        </div>
    <?php endif; ?>
    <label class="control-label" for="customservice-title">Service</label>
    <?= $form->field($model, 'title')->label(false)->input('text', ['placeholder' => 'Enter service name']); ?>
    <div class="form-group test_div">
        <?= Html::a('+ Add service', null, ['id' => 'add_custom_srv']); ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-warning']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <div>
        <p>* This service will be created on the menu, but price must be added before it can be saved.</p>
    </div>
</div>