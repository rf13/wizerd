<?php
/* @var $this yii\web\View */
/* @var $model app\models\Photo */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

$plugin_options = [
    'showCaption' => false,
    'showRemove' => false,
    'showUpload' => false,
    'browseClass' => 'btn btn-primary btn-block',
    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
    'browseLabel' =>  'Select image'
];
?>
<div class="photo-add">
    <?php $form = ActiveForm::begin([
        'id' => 'photo-form',
        'options' => [
            'enctype' => 'multipart/form-data',
           // 'multiple' => true,
        ],
    ]);
    ?>
    <?php
    if (!isset($main))
    echo $form->field($model, 'main')->checkbox(); ?>
    <?= $form->field($model, 'imageFile')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => $plugin_options
    ])->label(false) ?>
    <?= $form->field($model, 'title') ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-warning']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>