<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

/* @var $emp app\models\Staff */

$photo = '/web/thumb/bus' . $emp->bus_id . '/staff/' . $emp->url;

$crop_width = Yii::$app->params['staff_thmb_width'];
$crop_height = Yii::$app->params['staff_thmb_height'];

$this->registerJS('var add=0;');
?>


<div class="row staff_info">
    <div class="col-sm-1 n_arrow_stuff">
        <div class="col-xs-12">
            <?php
            if ($emp->getMinSort() != $emp->sort) {
                echo Html::a('<i class="glyphicon glyphicon-chevron-up glyphicon-default"></i>',
                    Url::toRoute(['account/staff-sort', 'id' => $emp->id, 'up' => true]), [
                        'class' => 'btn ',
                        'onclick' => '
                            $.ajax({
                                type: "POST",
                                url: $(this).attr("href"),
                                success: function(data) {
                                    if (data != false) {
                                        $.get("' . Url::toRoute(['account/staff', 'min' => true]) . '")
                                            .done(function (data) {
                                                $("#staff_manage_div").html(data);
                                            });
                                    }
                                }
                            });
                        return false;'
                    ]);
            } else {
                echo Html::a('<i class="glyphicon glyphicon-chevron-up glyphicon-disabled"></i>', null, ['class' => 'btn ']);
            }
            ?>
        </div>
        <div class="col-xs-12">
            <?php
            if ($emp->getMaxSort() != $emp->sort) {
                echo Html::a('<i class="glyphicon glyphicon-chevron-down glyphicon-default"></i>',
                    Url::toRoute(['account/staff-sort', 'id' => $emp->id, 'up' => false]), [
                        'class' => 'btn ',
                        'onclick' => '
                            $.ajax({
                                type: "POST",
                                url: $(this).attr("href"),
                                success: function(data) {
                                    if (data != false) {
                                        $.get("' . Url::toRoute(['account/staff', 'min' => true]) . '")
                                            .done(function (data) {
                                                $("#staff_manage_div").html(data);
                                            });
                                    }
                                }
                            });
                        return false;'
                    ]);
            } else {
                echo Html::a('<i class="glyphicon glyphicon-chevron-down glyphicon-disabled"></i>', null, ['class' => 'btn ']);
            }
            ?>
        </div>
    </div>
    <div class="col-sm-3 staff-avatar " id="photo_div_id_<?= $emp->id ?>">
        <?php
        if ($emp->url) {
            echo Html::img($emp->getWebPath() . "?d=" . round(rand(0, 99999)), ['class' => 'img-responsive staff-avatar-img', 'id' => 'photo_img_' . $emp->id]);
        } else {

            $plugin_options = [
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => false,
                'showClose' => false,
                'showPreview' => false,
                'browseClass' => 'btn btn-link div-addPhoto',
                'browseIcon' => '<i class=""></i> ',
                'browseLabel' => '+Add Photo',
            ];
            $form = ActiveForm::begin([
                'id' => 'staff-photo-form_' . $emp->id,
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data'
                ],

            ]);
            ?>

            <div class="staff-avatar-errors center-block"></div>
            <div class="output">
                <canvas height="<?= $crop_width ?>" width="<?= $crop_height ?>" id="canvas_<?= $emp->id ?>"
                        class="hidden"></canvas>
            </div>

            <?php
            echo $form->field($emp, 'imageFile')->widget(FileInput::classname(), [

                'options' => [
                    'accept' => 'image/*',
                    'id' => 'inp_field_photo_' . $emp->id,
                    'class' => 'inp_staff_photo',
                    'onchange' => '
                        staff_wid("' . Url::toRoute(['account/get-one-staff-edit', 'id' => $emp->id, 'file_input_id' => 'inp_field_photo_' . $emp->id]) . '",' . $emp->id . ');
                        return false;
                    '
                ],
                'pluginOptions' => $plugin_options
            ])->label(false)
            ?>

            <?php
            ActiveForm::end();
        }
        ?>


    </div>
    <div class="col-sm-6">
        <?= Html::input('text', 'name', $emp->name, [
            'class' => 'form-control n_photo_input ', 'readonly' => true, 'placeholder' => 'Enter staff name
'
        ]) ?>
        <?= Html::input('text', 'role', $emp->role, [
            'class' => 'form-control n_photo_input', 'readonly' => true, 'placeholder' => 'Enter staff title
'
        ]) ?>
        <?= Html::textarea('desc', $emp->description, [
            'rows' => '7', 'readonly' => true,
            'class' => 'form-control n_photo_textarea', 'style' => 'resize:none',
            'placeholder' => 'Enter staff member bio'
        ]) ?>
    </div>
    <div class="col-sm-2">
        <?= Html::a(' Edit',
            Url::toRoute(['account/get-one-staff-edit', 'id' => $emp->id]),
            [
                'class' => 'btn btn-link edit_staff_btn',
                'onclick' => '
                    start_staff_edit(this,'.$emp->id.');
                    return false;
                '
            ]);
        ?>

    </div>
</div>
