<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

$crop_width = Yii::$app->params['staff_thmb_width'];
$crop_height = Yii::$app->params['staff_thmb_height'];

$this->registerJsFile('/js/cropper.js');
$this->registerCss('/css/cropper.css');
$defaultFileInputId='edit_staff_photo';

if(!$file_input_id) $file_input_id=$defaultFileInputId;

$script3 = <<< JS


var input_id='$file_input_id';

var nophoto=1;
var canvasObj=$("#edit_staff_canvas");
var canvas=$("#edit_staff_canvas").get(0);
var ctx = canvas.getContext('2d');

var o_e_canvasObj=$("#o_edit_staff_canvas");
var o_e_canvas=$("#o_edit_staff_canvas").get(0);
var o_e_ctx = o_e_canvas.getContext('2d');

var obj=$("#edit_staff_canvas");

var crop=[];
var delta;
var saved_img;
var saved_crop;
var saved_ctx;
var saved_draw_params;
var saved_delta;
var saved_file;
var f;

$('#staff-edit-form').submit(function() {
    $("#"+input_id).addClass("hidden").appendTo($("#staff-edit-form"));
     if (nophoto==2){
        $("[name='Staff[imageFile]']").attr("name","imageFile");
        $("#"+input_id).attr("name","imageFile");
        f.attr("name","Staff[imageFile]").addClass("hidden").appendTo($("#staff-edit-form"));
         $("#nophoto_flag").val(nophoto);
     }
     return true; // return false to cancel form action
});

$('#'+input_id).change(function(e) {
    $("#btn_modal_close_edit").addClass("hidden");
    $("#btn_crop_close_edit").removeClass("hidden");
    $(".editStaffModal_body").addClass("hidden");
    $(".editStaffModal_crop").removeClass("hidden");
    $("#for_canvas_edit").removeClass("hidden");
    handleFiles(this.files,o_e_canvas,canvas,"#for_canvas_edit");
} );


$('#staff_edit_crop_btn').click(function(e) {

    //$("#btn_modal_close_edit").addClass("hidden");
    //$("#btn_crop_close_edit").removeClass("hidden");
    $(".staff_edit_title").addClass("hidden");
    $(".staff_edit_title_crop").removeClass("hidden");

    $(".editStaffModal_body").addClass("hidden");
    $(".editStaffModal_crop").removeClass("hidden");
    $("#for_canvas_edit").removeClass("hidden");
if (!saved_img){
    img=document.getElementById("img_edit");
    }
    else img=saved_img;
    addCropper(o_e_canvas,canvas,"#for_canvas_edit");
});


JS;
$this->registerJs($script3);


$scriptStartPhotoChange=<<< JS
$('#editStaffModal').on('shown.bs.modal', function (e) {
    $('#'+input_id).change();
})
$('#editStaffModal').on('hidden.bs.modal', function (e) {
    $("body").removeClass("modal-open");
    $('.editStaffModal_body').html("");

})
JS;
if($file_input_id!=$defaultFileInputId){
    $this->registerJS($scriptStartPhotoChange);
}



$plugin_options = [
    'showCaption' => false,
    'showRemove' => false,
    'showUpload' => false,
    'showClose' => false,
    'showPreview' => false,
    'browseClass' => 'btn btn-link inp_add_change_photo hidden',
    'browseIcon' => '',
    'browseLabel' => '+Add photo'
];
    if($model->url !== '')
        $plugin_options['browseClass']='btn btn-link inp_add_change_photo hidden';
    else $plugin_options['browseClass']='btn btn-link inp_add_change_photo';


?>
<?php $form = ActiveForm::begin([
    'id' => 'staff-edit-form',
    'action' => Url::to('/account/staff-new-add'),
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'options' => [
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ],
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper} {input} {error} {endWrapper} \n",
        'horizontalCssClasses' => [
            'offset' => '',
            'wrapper' => 'col-sm-12',
            'error' => ''
        ],
    ],
]);
?>


    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="thumbnail gray-content">
                <div class="photo_preview">
                    <div id="o_for_canvas" style="" class="hidden">
                        <canvas id="o_edit_staff_canvas" class="n_new_crop"></canvas>
                    </div>

                    <div class="col-sm-12 staff-edit-avatar" style="">
                        <div class="staff-avatar-errors center-block"></div>
                        <div class="">
                            <?php
                            if ($model->url !== '') {

                                echo Html::img($model->getBigPath().'?d='.floor(rand(1,99999)),

                                    [
                                        'id' => 'img_edit',
                                        'class' => 'img-responsive',
                                        'alt' => $model->name
                                    ]);
                            }
                            ?>
                        </div>
                        <?php  echo $form->field($model, 'imageFile')->widget(FileInput::classname(), [

                            'options' => [
                                'class'=>'',
                                'accept' => 'image/*',
                                'id' => 'edit_staff_photo',
                            ],
                            'pluginOptions' => $plugin_options
                        ])->label(false);

                        ?>
                    </div>
                </div>
                <div class="caption">

                    <div class="row cat-nav-row ">
                        <div class="col-sm-12">


                            <?= $form->field($model, 'name')->input('text', [
                                'class' => 'form-control n_photo_input',
                                'placeholder' => 'Enter staff name',

                            ])->label(false) ?>
                        </div>
                        <div class="col-sm-12">

                            <?= $form->field($model, 'role')->input('text', [
                                'class' => 'form-control n_photo_input',
                                'placeholder' => 'Enter staff title
'
                            ])->label(false) ?>
                        </div>
                        <div class="col-sm-12">

                            <?= $form->field($model, 'description')->textarea([
                                'rows' => '4',
                                'class' => 'form-control n_photo_textarea',
                                'style' => 'resize:none',
                                'placeholder' => 'Enter staff member bio
'
                            ])->label(false) ?>


                        </div>
                    </div>
                    <div class="row cat-nav-row">

                            <div class="col-sm-12 base-btn-vert">
                                <?= Html::button( 'Crop photo '.Html::img('@web/images/crop.png'), [
                                    'id' => 'staff_edit_crop_btn',
                                    'class' => 'btn-link',
                                ]) ?>
                            </div>
                            <div class="col-sm-12 base-btn-vert">
                                <?= Html::button('Change Photo',[
                                    'id'=>'change_staff_link',
                                    'class' => 'btn-link ',
                                    'onclick' => '
                                        $("#"+input_id).click();
                                    '
                                ]); ?>
                            </div>

                            <div class="col-sm-12 base-btn-vert">
                                <?= Html::a('Delete', Url::toRoute(['account/staff-delete', 'id' => $model->id]), [
                                    'class' => 'btn-link delete-link  photo_btns_' . $model->id,
                                    'onclick' => '
                                        one_staff_delete('.$model->id.');
                                        return false;
                                    '
                                ]); ?>
                            </div>

                            <div class="col-sm-12">
                                <?= Html::submitButton('Save', ['class' => 'btn btn-sm btn-info col-xs-12']) ?>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?= Html::hiddenInput('crop_params', null, ['id' => 'crop_params']) ?>
<?= Html::hiddenInput('nophoto', null,['id'=>'nophoto_flag']) ?>
<?= Html::hiddenInput('id', $model->id) ?>
<?php ActiveForm::end(); ?>




