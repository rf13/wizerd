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


JS;
$this->registerJs($script);

?>
<div class="hidden" id="flag"></div>
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
            one_photo_edit_crop_save(' . $model->id . ');
        '
    ]) ?>
</div>



