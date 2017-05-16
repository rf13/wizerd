<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

$crop_width = Yii::$app->params['staff_thmb_width'];
$crop_height = Yii::$app->params['staff_thmb_height'];

$this->registerJsFile('/js/cropper.js');
$this->registerCss('/css/cropper.css');


$script3 = <<< JS

$('#add_staff_save_btn').click(function() {
if(file_1==0) $("#new_staff_photo_1").remove();
if(file_2==0) $("#new_staff_photo_2").remove();
    //$("[name='Staff[imageFile]']").attr("name","imageFile");
    //$("#new_staff_photo").attr("name","imageFile");
    ////if(f){
    //    f.attr("name","Staff[imageFile]").addClass("hidden").appendTo($("#staff-new-form"));
    //}
    $("#staff-new-form").submit();
   //return true; // return false to cancel form action

});

var newImage;
var canvasObj=$("#new_staff_canvas");
var canvas=$("#new_staff_canvas").get(0);
var ctx = canvas.getContext('2d');

var o_canvasObj=$("#o_new_staff_canvas");
var o_canvas=$("#o_new_staff_canvas").get(0);
var o_ctx = o_canvas.getContext('2d');

var obj=$("#new_staff_canvas");

var crop=[];
var delta;
var file_1=0;
var file_2=0;
var file_edit=1
var recrop=0;
var saved_img;
var saved_crop;
var saved_delta;
var saved_file;
var saved_ctx;
var saved_draw_params;
var f;
var file_prev;
var file_cur;

$('#new_staff_photo_1').change(function(e) {
  new_staff_photo_change(e,this);
});
$('#new_staff_photo_2').change(function(e) {
  new_staff_photo_change(e,this);
});

function new_staff_photo_change(e,input){
  //$("#btn_modal_close_add").addClass("hidden");
  //  $("#btn_crop_close_add").removeClass("hidden");
    $(".staff_add_title").addClass("hidden");
    $(".staff_add_title_crop").removeClass("hidden");

    $(".addStaffModal_body").addClass("hidden");
    $(".addStaffModal_crop").removeClass("hidden");
    $("#for_canvas").removeClass("hidden");
    handleFiles(input.files,o_canvas,canvas,"#for_canvas");
}

$('#staff_add_crop_btn').click(function(e) {
//console.log(newImage);
recrop=1;
newImage=saved_img;
    if(newImage){

        //$("#btn_modal_close_add").addClass("hidden");
        //$("#btn_crop_close_add").removeClass("hidden");
        $(".staff_add_title").addClass("hidden");
        $(".staff_add_title_crop").removeClass("hidden");

        $(".addStaffModal_body").addClass("hidden");
        $(".addStaffModal_crop").removeClass("hidden");
        $("#for_canvas_add").removeClass("hidden");

        img=newImage;
        addCropper(o_canvas,canvas,"#for_canvas");
    }
});

JS;
$this->registerJs($script3);

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
?>

<?php $form = ActiveForm::begin([
    'id' => 'staff-new-form',
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
<?= $form->field($model, 'id')->hiddenInput()->label(false); ?>
    <div class="row">
        <div class="col-xs-12 " id="new_staff">
            <div class="thumbnail gray-content">
                <?php
                /* echo
                 Html::img($photo->getWebPath().'?d='.round(rand(1,900000)),
                     //   Html::img($photo->getWebPathBigImage(),

                     [
                         'class' => 'img-responsive',
                         'alt' => $photo->title
                     ])


                <div id="for_canvas" style="min-height:250px" class="hidden">
                     <canvas  id="new_staff_canvas" class="" ></canvas>
                 </div>

                */
                ?>
                <div id="o_for_canvas" style="" class="hidden">
                    <canvas id="o_new_staff_canvas" class=""  style="min-height:150px; "></canvas>
                </div>
                <div class="col-sm-12 staff-add-avatar" style="min-height:150px;">
                    <div class="staff-avatar-errors center-block"></div>
                    <?= Html::a('+Add Photo', null, [
                        'class' => 'btn-link ',
                        'onclick' => '
                                    $("#new_staff_photo_"+file_edit).click();
                                     return false;
                                '
                    ]); ?>

                    <?php // echo Html::input('file','temp',null,['id'=>'new_staff_photo_c','class'=>"hidden"])?>
                    <?php
                    echo $form->field($model, 'imageFile')->widget(FileInput::classname(), [
                        'options' => [
                            'accept' => 'image/*',
                            'id' => 'new_staff_photo_1',

                        ],
                        'pluginOptions' => $plugin_options
                    ])->label(false);

                    echo $form->field($model, 'imageFile')->widget(FileInput::classname(), [
                        'options' => [
                            'accept' => 'image/*',
                            'id' => 'new_staff_photo_2',
                        ],
                        'pluginOptions' => $plugin_options
                    ])->label(false)
                    ?>
                </div>

                <div class="caption ">
                    <div class="row cat-nav-row">
                        <div class="col-sm-12">
                            <?= Html::hiddenInput('crop_params', null, ['id' => 'crop_params']) ?>
                            <?= $form->field($model, 'name')->input('text', [
                                'class' => 'form-control n_photo_input',
                                'placeholder' => '* Enter staff name'
                            ])->label(false) ?>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($model, 'role')->input('text', [
                                'class' => 'form-control n_photo_input',
                                'placeholder' => 'Enter staff title'
                            ])->label(false) ?>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($model, 'description')->textarea([
                                'rows' => '4',
                                'class' => 'form-control n_photo_textarea',
                                'style' => 'resize:none',
                                'placeholder' => 'Enter staff member bio'
                            ])->label(false) ?>
                        </div>
                    </div>
                    <div class="row cat-nav-row">
                        <div class="col-sm-12 base-btn-vert">
                            <?= Html::a( 'Crop photo '.Html::img('@web/images/crop.png'), null, [
                                'id' => 'staff_add_crop_btn',
                                'class' => 'btn-link',
                            ]) ?>
                        </div>
                        <div class="col-sm-12 base-btn-vert">
                            <?= Html::a('Change Photo', null, [
                                'class' => 'btn-link ',
                                'onclick' => '
                                    $("#new_staff_photo_"+file_edit).click();
                                     return false;
                                '
                            ]); ?>
                        </div>
                        <div class="col-sm-12 base-btn-vert">
                            <?= Html::button('Save', ['class' => 'btn btn-sm btn-info col-xs-12','id'=>'add_staff_save_btn']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>