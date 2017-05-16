<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJsFile('/js/cropper.js');
$this->registerCss('/css/cropper.css');


$script = <<< JS

JS;

$this->registerJs($script);

?>
<div class="row">
    <div class="col-xs-12 col-md-12" id="photo_<?= $photo->id ?>">
        <div class="thumbnail gray-content">
            <div class="n_img photo_preview_<?= $photo->id ?>">
                <canvas id="canvas_<?= $photo->id ?>" class="hidden canvas"></canvas>

                <?= Html::img($photo->getWebPath(), [
                    'id' => 'img_' . $photo->id,
                    'class' => 'img-responsive n_img_photo_preview',
                    'alt' => $photo->title
                ])
                ?>
            </div>
            <div class="caption">
                <div class="row cat-nav-row">
                    <div class="col-sm-12">
                        <?= Html::input('text', 'photo_title-' . $photo->id, $photo->title, [
                            'class' => 'form-control n_photo_input photo_params  photo_param_' . $photo->id,
                            'id' => 'photo_title_' . $photo->id,
                            'placeholder' => 'Enter title'
                        ])
                        ?>
                    </div>

                    <div class="col-sm-12">
                        <?= Html::textarea('photo_description-' . $photo->id, $photo->description, [
                            'class' => 'form-control n_photo_textarea photo_params photo_param_' . $photo->id,
                            'id' => 'photo_description_' . $photo->id,
                            'placeholder' => 'Enter description',
                            'rows' => 4
                        ]);
                        ?>
                        <?= Html::hiddenInput('crop_params-' . $photo->id, null, [
                            'class' => 'photo_params photo_param_' . $photo->id,
                            'id' => 'crop_params_' . $photo->id
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 div_buttons">
                        <div class="col-sm-12 base-btn-vert">
                            <?= Html::a( 'Crop photo '.Html::img('@web/images/crop.png'), Url::to(['/account/photo-get-for-crop', 'id' => $photo->id]), [
                                'class' => 'btn-link',
                                'onclick' => 'crop_edit_photo_start(this); return false;'
                            ]) ?>
                        </div>

                        <div class="col-sm-12 base-btn-vert">
                            <?= Html::button('Make profile photo', [
                                'class' => 'btn-link',
                                'onclick' => 'start_modal_mark_as_profile_photo(' . $photo->id . ');'
                            ]) ?>
                        </div>

                        <div class="col-sm-12 base-btn-vert">
                            <?= Html::button('Delete', [
                                'class' => 'btn-link delete-link  photo_btns_' . $photo->id,
                                'onclick' => 'start_modal_delete_photo(' . $photo->id . ');'
                            ]); ?>
                        </div>

                        <div class="col-sm-12 btn-save-div">
                            <?= Html::a('Save', Url::to('/account/photo-save-group'), [
                                'class' => 'btn btn-info col-xs-12',
                                'id' => 'save_photo_group',
                                'onclick' => 'save_edit_photo(this);return false;'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
