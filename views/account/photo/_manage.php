<?php
/* @var $this yii\web\View */
/* @var $photos null|array */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;

$link = Url::to('/account/photo-add-new');

if (count($unsaved) > 0) {

    $this->registerJsFile('/js/cropper.js');
    $this->registerCss('/css/cropper.css');
    $this->registerJs('$("#addPhotoModal").modal("show")');
}

$script = <<< JS
var nohideModalByCancel=true;
var nohideModalByCancel_crop=true;
var f_edit_modal_close=false;

$('#profConfirmModal').on('hidden.bs.modal', function (e) {
    $("body").addClass("modal-open");
});
$('#cancelPhotoModal_crop').on('hidden.bs.modal', function (e) {
    $("body").addClass("modal-open");
});
$('#cancelPhotoModal').on('hidden.bs.modal', function (e) {
if (nohideModalByCancel)
        $("body").addClass("modal-open");
})
$('#delPhotoModal').on('hidden.bs.modal', function (e) {
    c=$(".unsaved_photo[del_val=0]")
    if (c.length!=0){
        $("body").addClass("modal-open");
    }
})
$('#new_photo_wid').on('fileloaded', function(event, file, previewId, index, reader) {
    $('#submit_photo').click();
});
$('#delPhotoModal_edit').on('hidden.bs.modal', function (e) {
    if(!f_edit_modal_close){
        $("body").addClass("modal-open");
    }
    else{
      f_edit_modal_close=false;
    }
});
//==========
$('#cancelPhotoModal_edit_crop').on('hidden.bs.modal', function (e) {
    $("body").addClass("modal-open");
});






JS;

$this->registerJs($script);

$this->title = 'Photo';
?>

<?php
$plugin_options = [
    'showCaption' => false,
    'showRemove' => false,
    'showUpload' => false,
    'showClose' => false,
    'showPreview' => false,
    'maxFileCount' => 20,
    'browseClass' => 'btn btn-link hidden',
    'browseIcon' => '<i class=""></i> ',
    'browseLabel' => '+Add photo'
];

?>

<div class="photo-add">
    <?php $form = ActiveForm::begin([
        'action' => Url::to('/account/photo-add-new'),
        'id' => 'new_photo_form',
        'fieldConfig' => ['template' => "{label}\n{input}\n{hint}"],
        'options' => [
            'enctype' => 'multipart/form-data',

        ],
    ]);
    ?>

    <?= $form->field($new_model, 'imageFiles[]')->widget(FileInput::classname(), [
        'options' => [
            'id' => 'new_photo_wid',
            'multiple' => true,
            'accept' => 'image/*',
        ],
        'pluginOptions' => $plugin_options
    ])->label(false) ?>

    <div class="caption ">
        <?= Html::submitButton('submit', ['id' => 'submit_photo', 'class' => 'hidden']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<!--   -----------div with photo----------  -->
<?php if ($photos):

    foreach ($photos as $photo):
        if ($photo->saved === 1) {
            /* @var $photo app\models\Photo */
            $class = 'btn btn-primary btn-block';
            if ($photo->main == 1) $class .= ' disabled';
            ?>
            <div class="col-xs-12 col-md-3 div_with_photo" id="photo_<?= $photo->id ?>" del_val="0">

                <div class="thumbnail gray-content n_div_photo">
                    <div class="n_img">
                        <?php
                        echo Html::img($photo->getWebPath().'?d='.round(rand(1,900000)),
                            [
                                'class' => 'img-responsive',
                                'alt' => $photo->title
                            ])
                        ?>
                    </div>
                    <div class="caption">
                        <div class="row cat-nav-row">
                            <div class="col-sm-12">
                                <?= Html::input('text', 'photo_title', $photo->title, [
                                    'class' => 'form-control n_photo_input photo_param_' . $photo->id,
                                    'id' => 'photo_title_' . $photo->id,
                                    'disabled' => true,
                                    'placeholder' => 'Enter title'
                                ]) ?>
                            </div>
                            <div class="col-sm-12">
                                <?php // echo Html::input('text','photo_tag',$photo->tag_id,[])
                                ?>
                            </div>
                            <div class="col-sm-12">
                                <?= Html::textarea('photo_description', $photo->description, [
                                    'class' => 'form-control n_photo_textarea photo_param_' . $photo->id,
                                    'id' => 'photo_description_' . $photo->id,
                                    'disabled' => true,
                                    'placeholder' => 'Enter description',
                                    'rows' => 3

                                ]) ?>
                            </div>
                        </div>
                        <div class="row cat-nav-row">
                            <div class="col-sm-6">
                                <div class="col-sm-12 n_edit_photo">
                                    <?= Html::a('Edit', Url::to('/account/get-one-photo-edit'), [
                                        'class' => 'btn-link photo_btns_' . $photo->id,
                                        'onclick' => '
                                            start_photo_edit(this,'.$photo->id.');
                                            return false;
                                        '
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
<!--   -----------end div with photo----------  -->

    <?php endforeach; ?>
<?php else: ?>
    <?php //echo $this->renderAjax('_new', ['model' => $new_model]); ?>
    <div class="row ">
        <div class="col-sm-offset-2 col-sm-9 row-nomenu">
            <div class="alert alert-info" role="alert">
                <p>Right now you don’t have any photos.</p>
                <p>To get started click the “+ Add photo” button.</p>
                <p>Click the “Tip” button for helpful hints on creating your photo album.</p>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php
    if(count($unsaved)>1) {
        $photoModalType='modal-md';
        $photoModalMdCol=6;
    }
else {
    $photoModalType = 'modal-sm';
    $photoModalMdCol=12;
}
?>
<!-------------add photo div--------------->
<div class="modal fade" id="addPhotoModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog <?=$photoModalType?>" id='addPhotoModalDialog' role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal_head_message add_photo_for_modal">Photo add</span>
                <span class="modal_head_message add_photo_for_crop hidden">Photo crop</span>

                <?= Html::button('Cancel', [
                    'class' => "close add_photo_for_modal",
                    'id'=>'modal_close',
                    'onclick'=>'
                        photo_first_modal_show_cancel("#cancelPhotoModal","#addPhotoModal");
                    '
                ]); //button of closing addPhotoModal ?>

                <?= Html::button('Cancel', [
                    'class' => "close add_photo_for_crop hidden",
                    'id'=>'crop_close',
                    'onclick'=>'
                        photo_first_modal_show_cancel("#cancelPhotoModal_crop","#addPhotoModal");
                    '
                ]); //button of closing crop window ?>

            </div>
            <div class="modal-body">

                <div class="row photos_part">
                    <?php foreach ($unsaved as $photo): //if ($photo->saved===0)
                    {
                        /* @var $photo app\models\Photo */
                        $class = 'btn btn-primary btn-block';
                        if ($photo->main == 1) $class .= ' disabled';
                        ?>

                        <div class=" col-xs-12 col-md-<?=$photoModalMdCol?> unsaved_photo" id="photo_<?= $photo->id ?>" del_val="0">
                            <div class="thumbnail gray-content">
                                <div class="n_img photo_preview_<?= $photo->id ?>" >
                                <canvas id="canvas_<?= $photo->id ?>" width="220" height="180" class="hidden canvas">
                                    </canvas>

                                    <?= Html::img($photo->getWebPath().'?d='.round(rand(1,900000)), [
                                        'id'=>'img_'.$photo->id,
                                        'class' => 'img-responsive ',
                                        'alt' => $photo->title
                                    ]) ?>
                                </div>
                                <div class="caption">

                                    <div class="row cat-nav-row">
                                        <div class="col-sm-12">
                                            <?= Html::input('text', 'photo_title-'. $photo->id, $photo->title, [
                                                'class' => 'form-control n_photo_input  photo_params photo_param_' . $photo->id,
                                                'id' => 'photo_title_' . $photo->id,
                                                'placeholder' => 'Enter title',

                                            ]); ?>
                                        </div>

                                        <div class="col-sm-12">
                                            <?php // echo Html::input('text','photo_tag',$photo->tag_id,[])?>
                                        </div>

                                        <div class="col-sm-12">
                                            <?= Html::textarea('photo_description-'. $photo->id, $photo->description, [
                                                'class' => 'form-control n_photo_textarea photo_params photo_param_' . $photo->id,
                                                'id' => 'photo_description_' . $photo->id,
                                                'placeholder' => 'Enter description',
                                                'rows' => 4

                                            ]); ?>
                                        </div>
                                    </div>
                                    <?= Html::hiddenInput('crop_params-' . $photo->id, null, [
                                        'class'=>'photo_params photo_param_'. $photo->id,
                                        'id' => 'crop_params_' . $photo->id]); ?>
                                    <div class="row cat-nav-row">
                                        <div class="col-sm-12">
                                            <div class="col-sm-12 base-btn-vert">
                                                <?= Html::a('Crop photo '.Html::img('@web/images/crop.png'), Url::to(['/account/photo-get-for-crop', 'id' => $photo->id]), [
                                                    'class' => 'btn-link',
                                                    'onclick' => '
                                                        photo_crop_add_photo(this);
                                                        return false;
                                                    '
                                                ]); ?>
                                            </div>
                                            <!-- delete button of modal window id="addPhotoModal"-->
                                            <div class="col-sm-12 base-btn-vert">
                                                <?= Html::a('Delete', Url::toRoute(['account/photo-delete', 'id' => $photo->id]), [
                                                    'class' => 'btn-link delete-link photo_btns_' . $photo->id,
                                                    'onclick' => '
                                                        photo_delete_add_photo('.$photo->id.');
                                                        return false;
                                                    '
                                                ]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    endforeach; ?>

                    <div class="col-xs-12 base-btn-vert-save">
                        <?= Html::a('Save',Url::to('/account/photo-save-group'), [
                            'class' => 'btn btn-info col-xs-12',
                            'id' => 'save_photo_group',
                            'onclick' => '
                                group_photo_save(this);
                                return false;
                            '
                        ]) ?>

                    </div>
                </div>
                <div class="row crop_part hidden"></div>
            </div>
        </div>
    </div>
</div>
<!------------end - add photo div--------------->
<!----------Modal - id="cancelPhotoModal"- - open when click 'Cancel' in window '+Add photo'------------->
<div class="modal fade bs-example-modal-sm" id="cancelPhotoModal" tabindex="-1" role="dialog" data-backdrop="static"  data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Photo cancel</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Do you want to cancel? Your changes won’t be saved.</p>
                </div>
                <div class="row div_modal_button">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id'=>'btn_cancelPhotoModal_close',
                        'onclick'=>'
                            confirm_modal_cancel_default("#cancelPhotoModal","#addPhotoModal");
                        '
                    ]); ?>
                    <?= Html::a('Confirm',
                        Url::to(['account/photo-delete-unsaved']),
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id'=>'btn_cancelPhotoModal_confirm',
                            'del_id'=>'',
                            'onclick'=>'
                                cancel_photo_modal_confirm(this);
                                return false;
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------end Modal - id="cancelPhotoModal"---------------->
<!----------Modal - id="cancelPhotoModal_crop"- - open when click 'Cancel' in window '+Add photo'------------->
<div class="modal fade bs-example-modal-sm" id="cancelPhotoModal_crop" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"  aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Photo cancel</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Do you want to cancel? Your changes won’t be saved.</p>
                </div>
                <div class="row div_modal_button">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id'=>'btn_cancelPhotoModal_close_crop',
                        'onclick'=>'
                            confirm_modal_cancel_default("#cancelPhotoModal_crop","#addPhotoModal");
                        '
                    ]); ?>
                    <?= Html::button('Confirm',
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id'=>'btn_cancelPhotoModal_confirm_crop',
                            'del_id'=>'',
                            'onclick'=>'
                                cancel_photo_modal_crop_confirm();
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------end Modal - id="cancelPhotoModal_crop"---------------->
<!----------Modal - id="editPhotoModal"-- open when click 'edit'-------------->
<div class="modal fade " id="editPhotoModal" tabindex="-1" role="dialog" data-backdrop="static"  data-keyboard="false" aria-labelledby="myModalLabel" >
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal_head_message edit_photo_for_modal">Photo edit</span>
                <span class="modal_head_message edit_photo_for_crop hidden">Photo crop</span>
                <?= Html::button('Cancel', [
                    'class' => "close edit_photo_for_modal",
                    'id'=>'btn_modal_close_edit',
                    'onclick'=>'
                        photo_first_modal_show_cancel("#cancelPhotoModal_edit","#editPhotoModal");
                    '
                ]) ?>
                <?= Html::button('Cancel', [
                    'class' => "close edit_photo_for_crop hidden",
                    'id'=>'btn_model_close_crop',
                    'onclick'=>'
                        photo_first_modal_show_cancel("#cancelPhotoModal_edit_crop","#editPhotoModal");
                    '
                ]) ?>
            </div>
            <div class="modal-body editPhotoModal_body" >

            </div>
            <div class="row editPhotoModal_crop hidden">

            </div>
        </div>
    </div>
</div>
<!---------- end Modal - id="editPhotoModal" ---------------->
<!----------Modal - id="delPhotoModal"- - open when click 'delete' in window '+Add photo'------------->
<div class="modal fade bs-example-modal-sm" id="delPhotoModal" tabindex="-1" role="dialog" data-backdrop="static"  data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Photo delete</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Do you want to delete this photo?</p>
                </div>
                <div class="row div_modal_button">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id'=>'btn_delPhotoModal_close',
                        'onclick'=>'
                            confirm_modal_cancel_default("#delPhotoModal","#addPhotoModal");
                        '
                    ]); ?>
                    <?= Html::a('Confirm',
                        Url::toRoute(['account/photo-delete']),
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id'=>'btn_delPhotoModal_confirm',
                            'del_id'=>'',
                            'onclick'=>'
                                photo_confirm_delete_add_photo(this);
                                return false;
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------end Modal - id="delPhotoModal"---------------->


<!----------Modal - id="profConfirmModal"- open when click 'Make profile photo'--------------->
<div class="modal fade bs-example-modal-sm" id="profConfirmModal" tabindex="-1" role="dialog" data-backdrop="static"  data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Profile photo</span>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <p>Do you want this to be your profile photo? This will delete the existing profile photo.</p>
                </div>
                <div class="row div_modal_button">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id'=>'btn_model_close',
                        'onclick'=>'
                             confirm_modal_cancel_default("#profConfirmModal","#editPhotoModal");
                        '
                    ]); ?>
                    <?= Html::a('Confirm',
                        Url::to('/account/photo-make-as-profile'),
                        [
                        'class' => "btn btn-info col-md-5 col-md-offset-1",
                        'id'=>'profile_btn_model_confirm',
                            'prof_id'=>'',
                        'onclick'=>'
                            prof_confirm_modal_confirm(this);
                            return false;
                        '
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------end Modal - id="profConfirmModal"---------------->
<!----------Modal - id="delPhotoModal_edit"- - open when click 'delete' in window 'Edit'------------->
<div class="modal fade bs-example-modal-sm" id="delPhotoModal_edit" tabindex="-1" role="dialog" data-backdrop="static"  data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Photo delete</span>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <p>Do you want to delete this photo?</p>
                </div>
                <div class="row div_modal_button">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id'=>'btn_delPhotoModal_close',
                        'onclick'=>'
                            confirm_modal_cancel_default("#delPhotoModal_edit","#editPhotoModal");
                        '
                    ]); ?>
                    <?= Html::a('Confirm',
                        Url::toRoute(['account/photo-delete']),
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id'=>'btn_delPhotoModal_confirm_edit',
                            'del_id'=>'',
                            'onclick'=>'
                                photo_confirm_delete_edit_photo(this);
                                return false;
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------end Modal - id="delPhotoModal_edit"---------------->
<!----------Modal - id="cancelPhotoModal_edit"- - open when click 'Cancel' in window 'Edit'------------->
<div class="modal fade bs-example-modal-sm" id="cancelPhotoModal_edit" tabindex="-1" role="dialog" data-backdrop="static"  data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Photo cancel</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Do you want to cancel? Your changes won’t be saved.</p>
                </div>
                <div class="row div_modal_button">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id'=>'btn_cancelPhotoModal_close_cancel',
                        'onclick'=>'
                            confirm_modal_cancel_default("#cancelPhotoModal_edit","#editPhotoModal");
                        '
                    ]); ?>
                    <?= Html::button('Confirm',
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id'=>'btn_cancelPhotoModal_confirm',
                            'del_id'=>'',
                            'onclick'=>'
                                cancel_photo_modal_edit_confirm();
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------end Modal - id="cancelPhotoModal"---------------->
<!----------Modal - id="cancelPhotoModal_edit_crop"- - open when click 'Cancel' in window CROP 'Edit'------------->
<div class="modal fade bs-example-modal-sm" id="cancelPhotoModal_edit_crop" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Photo cancel</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Do you want to cancel? Your changes won’t be saved.</p>
                </div>
                <div class="row div_modal_button">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id'=>'btn_cancelPhotoModal_close_edit_crop',
                        'onclick'=>'
                            confirm_modal_cancel_default("#cancelPhotoModal_edit_crop","#editPhotoModal");
                        '
                    ]); ?>
                    <?= Html::button('Confirm',
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id'=>'btn_cancelPhotoModal_confirm_crop',
                            'del_id'=>'',
                            'onclick'=>'
                                cancel_photo_modal_edit_crop_confirm();
                                return false;
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------end Modal - id="cancelPhotoModal_crop"---------------->
