<?php
/* @var $this yii\web\View */
/* @var $model app\models\Promo */
/* @var $menu_service array */
/* @var $popup boolean */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use dosamigos\multiselect\MultiSelect;
use kartik\date\DatePicker;
use app\models\Promo;

//\dosamigos\multiselect\MultiSelectAsset::register($this);
//$this->assetBundles['yii\bootstrap\BootstrapAsset'] = new dosamigos\multiselect\MultiSelectAsset();
?>
<?php
if (count($menu_service) > 0) {
    ?>
    <div class="promo-add">
		<div class="col-md-9">
        <?php
        $form = ActiveForm::begin([
            'id' => 'promo-group-form',
            'options' => ['class' => 'form-horizontal'],
            'layout' => 'horizontal',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-3',
                    'offset' => 'col-sm-offset-3',
                    'wrapper' => 'col-sm-6',
                    'error' => '',
                ],
            ],
        ]);
        ?>
        <?= $form->field($model, 'services', ['options' => ['class' => 'form-group disable-required']])
            ->label(true)
            ->widget(MultiSelect::className(), [
                'id' => 'menu-service-select',
                'options' => [
                    'multiple' => 'multiple',
                    'class' => 'form-control disable-require hidden'
                ],
                'data' => $menu_service,
                'clientOptions' => [
                    'includeSelectAllOption' => true,
                    'enableClickableOptGroups' => true,
                    'numberDisplayed' => 2
                ],
            ]) ?>
        <!--        --><?php //echo $form->field($model, 'services', ['options' => ['class' => 'form-group disable-required']])->dropDownList($menu_service); ?>
        <?php
        //echo $form->field($model, 'discount', ['inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">%</span></div>', ]);
        ?>
        <?php
        $discountArray = [];
        for ($i = 1; $i <= 99; $i++) {
            $discountArray[$i] = $i;
        }
        echo $form->field($model, 'discount', [
            'options' => ['class' => 'form-group disable-required'],
            'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">%</span></div>',
        ])
            ->dropdownList($discountArray)
        ?>

        <?= $form->field($model, 'round', ['options' => ['class' => 'form-group disable-required']])
            ->dropdownList([
                Promo::ROUND_UP => 'Round price up to nearest dollar',
                Promo::ROUND_DOWN => 'Round price down to nearest dollar',
                Promo::ROUND_NOT => 'Don’t round price',
            ]) ?>
        <!--     $form->field($model, 'nco', ['options'=>['class'=>'form-group disable-required']])->dropdownList([Promo::NCO_NO => 'No', Promo::NCO_YES => 'Yes']) -->
        <!--     $form->field($model, 'combine', ['options'=>['class'=>'form-group disable-required']])->dropdownList([Promo::COMBINE_NO => 'No', Promo::COMBINE_YES => 'Yes']) -->
        <?php

        ?>
        <?= $form->field($model, 'start', ['options' => ['class' => 'form-group disable-required']])
            ->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Start date...'],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'm/d/yyyy',
                    'todayHighlight' => true
                ]
            ]); ?>
        <?= $form->field($model, 'end', ['options' => ['class' => 'form-group disable-required']])
            ->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'End date...'],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'm/d/yyyy',
                    'todayHighlight' => true
                ]
            ]); ?>
        <?= $form->field($model, 'terms', ['options' => ['class' => 'form-group disable-required']])
            ->textArea([
                'rows' => '4',
                'style' => 'resize:none'
            ]) ?>
        <div class="form-group">
            <?php if ($popup): ?>
                <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-warning']) ?>
            <?php else: ?>

                    <div class=" col-sm-offset-3 col-sm-2">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-info']) ?>

                    </div>
                    <div class="col-sm-2">
                        <?= Html::a('Clear Form', Url::toRoute("account/promo-group-add"), [
                            'class' => 'btn btn-block btn-default',
                            'onClick' => '
                                 $.ajax({
                                        type: "POST",
                                        url: $(this).attr("href"),
                                        success: function(response) {
                                                 $(".add-promo").html(response);
                                        }
                                        });
                                return false;
                            '
                        ]) ?>
                    </div>

            <?php endif; ?>
        </div>
        <?php ActiveForm::end(); ?>
        </div>
        <div class="clearfix"></div>
    </div>	<!--./promo-add-->
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('.dropdown-toggle').dropdown();
                $('#w0').multiselect();
            }, 1500);
        });
    </script>

<?php } else { ?>
    <div class="row ">
        <div class="col-sm-offset-2 col-sm-9 row-nomenu">
            <div class="alert alert-info" role="alert">
                <p>Before running promotions you must build a menu.</p>
                <p>Click on the Menu tab to complete your menu.</p>
                <p>After that, come back here and create promotions.</p>
            </div>
        </div>
    </div>
<?php } ?>
