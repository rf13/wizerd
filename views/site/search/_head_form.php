<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$url_zip = Url::toRoute(['site/get-biz-by-zipcode']);


?>
<div class="form-inline header-form n_search_input searchpage-inputs">
    <?php
    $form = ActiveForm::begin([
        'action' => Url::to('/'),
        'id' => 'search-form',
        'method' => 'GET',
        'fieldConfig' => ['template' => "{input}"],
    ]);
    ?>

    <?= $form->field($model, 'search')->input('text', [
        'size' => 65,
        'class' => 'form-control ',
    ])->label(false); ?>

    <?= $form->field($model, 'zip')->input('text', [
        'placeholder' => $model->zip,
        'class' => 'form-control',
        'id' => 'search-zip_code',
        'size' => 5
    ])->label(false); ?>
	<?= Html::submitButton('Search', ['class' => 'btn btn-info wid_80 n_search ', 'name' => 'search-button']) ?>
    <?php /*?><?= Html::submitButton(Html::img('@web/images/icon_search.png', ["class" => "n_icon_search"]), ['class' => 'btn n_btn_search', 'name' => 'search-button']); ?><?php */?>        
    <?php
    $form::end();
    ?>
</div>
