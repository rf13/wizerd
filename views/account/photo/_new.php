<?php
/* @var $this yii\web\View */
/* @var $model app\models\Photo */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

$script = <<< JS

//$('#new_photo_wid').on('fileimagesloaded ', function(event, file, previewId, index, reader) {
//    console.log("fileimagesloaded ");
//    console.log( $('#photo-form'));
// $('#photo-form').submit();
//    console.log("fileimagesloaded 111");
//});


JS;
$this->registerJs($script);
?>


<div class="col-xs-4 col-md-3 hidden" id="add_photo_div">
    <div class="thumbnail gray-content">
        <?php
        $plugin_options = [
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'showClose' => false,
            'maxFileCount'=>20,
            'browseClass' => 'btn btn-link',
            'browseIcon' => '<i class=""></i> ',
            'browseLabel' => '+Add photo'
        ];


        ?>
        <div class="photo-add">
            <?php $form = ActiveForm::begin([
                'action' => Url::to('/account/photo-add'),
                'id' => 'new_photo_form',
                'fieldConfig' => ['template' => "{label}\n{input}\n{hint}"],
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
            ]);
            ?>


            <?= $form->field($model, 'imageFiles')->widget(FileInput::classname(), [
                'options' => [
                    'id'=>'new_photo_wid',
                         'multiple'=>true,
                    'accept' => 'image/*',
                ],
                'pluginOptions' => $plugin_options
            ])->label(false) ?>
            <div class="caption " >
                <div class="row cat-nav-row">
                    <div class="col-sm-12">
                        <?php //echo // $form->field($model, 'title')->input('text', ['placeholder' => 'enter photo title'])->label(false) ?>
                    </div>

                    <div class="col-sm-12">
                        <?php // echo Html::input('text','photo_tag',$photo->tag_id,[])
                        ?>
                    </div>
                    <div class="col-sm-12">
                        <?php //echo //$form->field($model, 'description')->textarea(['placeholder' => 'enter photo description'])->label(false) ?>

                    </div>
                    <div class="col-sm-6">
                        <div class="col-sm-12 base-btn-vert">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-sm btn-info col-xs-12 new_photo_btns']) ?>
                        </div>
                        <div class="col-sm-12 base-btn-vert">
                            <?= Html::a('Cancel', Url::to('/account/photo-edit'), [
                                'class' => 'btn btn-sm btn-default col-xs-12 new_photo_btns',
                                'onclick' => '
                                             $.ajax({
                                            type: "GET",
                                            url: $(this).attr("href"),
                                            success: function(response) {
                                                      $("#photo_manage").html(response);

                                            }
                                        }); return false;
                                    '
                            ]); ?>
                        </div>
                        <div class="col-sm-12 base-btn-vert">
                            <?php
                            /*echo Html::a('Delete', Url::toRoute(['account/photo-delete', 'id' => $photo->id]), [
                                'class' => 'btn-link delete-link  photo_btns_' . $photo->id,

                            ]);
                            */
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-12 ">
                        <?php //echo  $form->field($model, 'main')->checkbox()->label("Profile photo"); ?>

                    </div>
                </div>


            </div>


            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>