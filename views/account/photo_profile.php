<?php
/* @var $this yii\web\View */
/* @var $photos null|array */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;

$link = Url::to('/account/photo-add-new');

$crop_width = Yii::$app->params['photo_profile_width'];
$crop_height = Yii::$app->params['photo_profile_height'];

$this->registerJsFile('/js/cropper.js');
$this->registerCss('/css/cropper.css');
$script1 = <<< JS

$('#new_profile_photo_wid').on('fileloaded', function(event, file, previewId, index, reader) {
    var that = $("#new_photo_form"),
    formData = new FormData(that.get(0)); // создаем новый экземпляр объекта и передаем ему нашу форму (*)
    $.ajax({
        url: that.attr('action'),
        type: that.attr('method'),
        contentType: false, // важно - убираем форматирование данных по умолчанию
        processData: false, // важно - убираем преобразование строк по умолчанию
        data: formData,
        // dataType: 'json',
        success: function(response){
            if(response){
                $("#photo_manage").html(response);
            }
        }
    });
});

    var cropStr='';
    var previews = $('.preview');
    var obj=$("#original_image");

function makeCroper(ob){
    obj=$("#"+ob);
    var clone = obj.clone();
    obj.cropper({
        aspectRatio: $crop_width / $crop_height,
        viewMode:1,
        build: function (e) {
            clone.css({
                display: 'block',
                width: '100%',
                minWidth: 0,
                minHeight: 0,
                maxWidth: 'none',
                maxHeight: 'none'
            });
            previews.css({
                width: '100%',
                overflow: 'hidden'
            }).html(clone);
        },

        crop: function (e) {
            var imageData = $(this).cropper('getImageData');
            var previewAspectRatio = e.width / e.height;
            clone.css({  transform: 'rotate('+e.rotate+'deg)'});

            crop=[e.x,e.y,e.width,e.height,e.rotate];
            cropStr=crop.toString();
            previews.each(function () {
                var preview = $(this);
                var previewWidth = preview.width();
                var previewHeight = previewWidth / previewAspectRatio;
                var imageScaledRatio = e.width / previewWidth;

                if ((e.rotate==0)||(e.rotate==180)){
                    // 0 180
                    mLeft= -e.x / imageScaledRatio;
                    mTop= -e.y / imageScaledRatio;
                }
                if ((e.rotate==90)||(e.rotate==270)){
                    // 90 270
                    mLeft=  -(imageData.naturalWidth / imageScaledRatio-imageData.naturalHeight / imageScaledRatio)/2 -e.x / imageScaledRatio;
                    mTop= (imageData.naturalWidth / imageScaledRatio-imageData.naturalHeight / imageScaledRatio)/2 -e.y/ imageScaledRatio;
                }

                preview.height(previewHeight).find('img').css({
                    width: imageData.naturalWidth / imageScaledRatio,
                    height: imageData.naturalHeight / imageScaledRatio,
                    marginLeft: mLeft,
                    marginTop: mTop
                });
            });
        }
     });
}


$('#cancelProfilePhotoModal').on('hidden.bs.modal', function (e) {
    $("body").removeClass("modal-open");
})
$("#original_image").attr("width",$("#for_image").width());
JS;
$this->registerJs($script1);

if ((isset($photo)) && (!$photo->croped)) {
    $this->registerJs("makeCroper('original_image');");
}

$this->title = 'Profile Photo';
?>


<?php
$plugin_options = [
    'showCaption' => false,
    'showRemove' => false,
    'showUpload' => false,
    'showClose' => false,
    'showPreview' => false,
    'browseClass' => 'btn btn-link hidden',
    'browseIcon' => '<i class=""></i> ',
    'browseLabel' => '+Add photo'
];


?>

<div class="photo-add">
    <?php $form = ActiveForm::begin([
        'action' => Url::to('/account/photo-profile-add-new'),
        'id' => 'new_photo_form',
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]);
    ?>
    <?= $form->field($new_model, 'imageFile')->widget(FileInput::classname(), [
        'options' => [
            'id' => 'new_profile_photo_wid',
            'accept' => 'image/*',
        ],
        'pluginOptions' => $plugin_options
    ])->label(false) ?>

    <div class="caption ">
        <?= Html::submitButton('submit', ['id' => 'submit', 'class' => 'hidden']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>


<?php
if (isset($photo)) {
    ?>
    <div class="col-xs-12 col-md-6 n_profile" id="photo_<?= $photo->id ?>">
        <div id="for_image">
            <?php
            echo Html::img($photo->getWebPathSmallImage() . '?d=' . floor(rand(1, 9999)),
                [
                    'id' => 'original_image',
                    'class' => 'img-responsive',
                    'alt' => $photo->title
                ]) ?>
        </div>
        <div class="caption">
            <div class="row cat-nav-row">
                <?php if ($photo->croped) {
                    $visibleEdit = '';
                    $visibleButtons = "hidden";
                } else {
                    $visibleEdit = "hidden";
                    $visibleButtons = "";
                }
                ?>
                <div class="btn-group col-xs-12 n_button  photo_btns_<?= $photo->id ?> <?= $visibleButtons ?>">
                    <?= Html::button('<span class="glyphicon glyphicon-arrow-left"></span>', [
                        'onclick' => 'obj.cropper("move", -10, 0);'

                    ]) ?>
                    <?= Html::button('<span class="glyphicon glyphicon-arrow-right"></span>', [
                        'onclick' => 'obj.cropper("move", 10, 0);'

                    ]) ?>
                    <?= Html::button('<span class="glyphicon glyphicon-arrow-up"></span>', [
                        'onclick' => 'obj.cropper("move", 0, -10);'

                    ]) ?>
                    <?= Html::button('<span class="glyphicon glyphicon-arrow-down"></span>', [
                        'onclick' => 'obj.cropper("move", 0, 10);'

                    ]) ?>
                    <?= Html::button('<span class="glyphicon glyphicon-zoom-in"></span>', [
                        'onclick' => 'obj.cropper("zoom", 0.1);'

                    ]) ?>
                    <?= Html::button('<span class="glyphicon glyphicon-zoom-out"></span>', [
                        'onclick' => 'obj.cropper("zoom", -0.1);'

                    ]) ?>
                    <?= Html::button('<span class="glyphicon glyphicon-repeat"></span>', [
                        'onclick' => 'obj.cropper("rotate", 90);'

                    ]) ?>
                </div>
                <div class="col-sm-6 n_edit_photo">
                    <div class="col-sm-12">
                        <?= Html::a('Edit', Url::to('/account/photo-get-profile-origin'), [
                            'class' => 'btn-link photo_btns_' . $photo->id . ' ' . $visibleEdit,
                            'onclick' => '
                                edit_profile_photo(this,'.$photo->id.');
                                return false;
                            '
                        ]); ?>
                    </div>
                    <div class="col-sm-7 base-btn-vert">
                        <?= Html::a('Save', Url::to('/account/photo-profile-save-new'), [
                            'class' => 'btn btn-info photo_btns_' . $photo->id . ' ' . $visibleButtons,
                            'onclick' => '
                                save_profile_photo(this,'.$photo->id.');
                                return false;
                            '
                        ]); ?>
                    </div>
                    <div class="col-sm-7 base-btn-vert ">
                        <?= Html::button('Cancel', [
                            'class' => 'btn btn-default  photo_btns_' . $photo->id . ' ' . $visibleButtons,
                            'onclick' => '
                                $("#cancelProfilePhotoModal").modal("show");
                                $("#btn_cancelProfilePhotoModal_confirm").attr("photo_id",'. $photo->id.');
                            '
                        ]); ?>
                    </div>
                    <div class="col-sm-12 base-btn-vert">
                        <?= Html::button('Change photo (upload)', [
                            'class' => 'btn-link  photo_btns_' . $photo->id . ' ' . $visibleButtons,
                            'onclick' => '
                                $("#new_profile_photo_wid").click();
                            '
                        ]); ?>
                    </div>
                </div>
                <?php ?>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="preview"></div>
    </div>
<?php } ?>

<?php
if (!isset($photo)) {
    ?>
    <div class="row ">
        <div class="col-sm-offset-2 col-sm-9 row-nomenu">
            <div class="alert alert-info" role="alert">
                <p>Right now you don’t have a profile photo.</p>
                <p>To get started click the “+ Add photo” button.</p>
                <p>Click the “Tip” button for helpful hints on creating your profile photo.</p>
            </div>
        </div>
    </div>
<?php
}
?>
<!----------Modal - id="cancelProfilePhotoModal"- - open when click 'Cancel' in window Profile Photo ------------->
<div class="modal fade bs-example-modal-sm" id="cancelProfilePhotoModal" tabindex="-1" role="dialog" data-backdrop="static"  data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Profile photo cancel</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Do you want to cancel? Your changes won’t be saved.</p>
                </div>
                <div class="row">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-info col-md-5",
                        'id'=>'btn_cancelProfilePhotoModal_close',
                        'onclick'=>'$("#cancelProfilePhotoModal").modal("hide");'
                    ]); ?>
                    <?= Html::a('Confirm',
                        Url::to('/account/photo-profile-cancel'),
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id'=>'btn_cancelProfilePhotoModal_confirm',
                            'photo_id'=>'',
                            'onclick'=>'cancel_profile_photo_edit(this);return false;'
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------end Modal - id="cancelPhotoModal_crop"---------------->

