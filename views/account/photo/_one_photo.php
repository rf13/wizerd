<?php
/* @var $this yii\web\View */
/* @var $model app\models\Photo */
use yii\helpers\Html;

$crop_width = Yii::$app->params['staff_thmb_width'];
$crop_height = Yii::$app->params['staff_thmb_height'];

$script = <<< JS

var crop=[];

var obj=$("#big_img_$model->id");
obj.cropper({
    viewMode:1,
    aspectRatio: 1,
    crop: function(e) {
        crop=[e.x,e.y,e.width,e.height,e.rotate];
        $("#crop_params_$model->id").val(crop.toString());
    }
})

$(".add_photo_for_modal").addClass("hidden");
$(".add_photo_for_crop").removeClass("hidden");

//$("#modal_close").addClass("hidden");
//$("#crop_close").removeClass("hidden");

function add_photo_crop_close(){
    $("#crop_params_$model->id").val('');
    //$("#crop_close").addClass("hidden");
    //$("#modal_close").removeClass("hidden");
    $(".add_photo_for_modal").removeClass("hidden");
    $(".add_photo_for_crop").addClass("hidden");

    $("#crop_params_$model->id").val();
    $(".crop_part").html();

    $(".crop_part").addClass("hidden");
    $(".photos_part").removeClass("hidden");
}

//=====================================================

JS;
$this->registerJs($script);

?>
<div class="row">
<div class="col-xs-12 ">
    <?= Html::img($model->getWebPathSmallImage(),
        ['id' => 'big_img_' . $model->id,
            'class' => 'img-responsive',
            'alt' => $model->title
        ]);


    ?>
</div>

<div class="btn-group col-xs-12 n_button">
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


<div class="col-xs-12">
    <?= Html::button("Save", [
        'class' => 'btn btn-info col-xs-12',
        'onclick' => '
            one_photo_add_crop_save(' . $model->id . ');
        '
    ]) ?>
</div>

</div>

