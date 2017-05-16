<?php
/* @var $this yii\web\View */
/* @var $model app\models\Promo */
/* @var $menu_service array */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use dosamigos\multiselect\MultiSelect;
use kartik\date\DatePicker;
use app\models\Promo;
?>
<div class="promo-group-update">
    <?php $form = ActiveForm::begin([
            'action' => Url::to(['account/promo-group-update','id' => ($model->id)?$model->id:0]),
        'id' => 'promo-group-update-form_'.$model->id,
        'enableAjaxValidation'   => true,
        'enableClientValidation' => false,
    ]); ?>
    <?= $form->field($model, 'services')->widget(MultiSelect::className(), [
        'id' => 'menu-service-select_'.$model->id,
        'options' => [
            'multiple' => 'multiple',
            'class' => 'form-control hidden'
        ],
        'data' => $menu_service,
        'clientOptions' =>
            [
                'includeSelectAllOption' => true,
                'enableClickableOptGroups' => true,
                'numberDisplayed' => 1
            ],
    ]) ?>
<!--    --><?php //echo $form->field($model, 'services', ['options' => ['class' => 'form-group disable-required']])->dropDownList($menu_service); ?>
    <div class="row">
        <div class="col-sm-3">
            <?php //echo $form->field($model, 'discount', ['inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">%</span></div>', ]); ?>
             <?php
    $discountArray = [];
    for ($i = 1; $i <= 99; $i++)
        $discountArray[$i] = $i;
      echo $form->field($model, 'discount', ['inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">%</span></div>',])->dropdownList($discountArray)
    ?>
        </div>
        <div class="col-sm-9">
            <?= $form->field($model, 'round')->dropdownList(
                [
                    Promo::ROUND_UP => 'Round price up to nearest dollar',
                    Promo::ROUND_DOWN => 'Round price down to nearest dollar',
                    Promo::ROUND_NOT => 'Don’t round price',
                ]
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-sm-3">
            <?= $form->field($model, 'start')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Start date...'],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'm/d/yyyy',
                    //'startDate' => date('Y-m-d'),
                    'todayHighlight' => true
                ]
            ]);
            ?>
        </div>
        <div class="col-xs-6 col-sm-3">
            <?= $form->field($model, 'end')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'End date...'],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'm/d/yyyy',
                    //'startDate' => date('Y-m-d'),
                    'todayHighlight' => true
                ]
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'terms')->textArea(['rows' => '2', 'style' => 'resize:none']) ?>
        </div>
    </div>
    <div class="row">
                <div class=" col-sm-offset-3 col-sm-2">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-info']) ?>

                </div>
                <div class="col-sm-2">     
                    <?= Html::a('Clear Form', Url::toRoute(["account/promo-group-update",'id' => ($model->id)?$model->id:0,'clear'=>1]), [
                            'class' => 'btn btn-block btn-default',
                            'onClick'=>' 
                                 $.ajax({
                                                            type: "GET",
                                                           url: $(this).attr("href"),
                                                           success: function(response) {
                                             
                                             $("#promo_'.$model->id.'").html(response);
                                            }
                                                        });
                                                        return false;
                            
                            '
                            ]) ?>
                </div>
            </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(document).ready(function () {
        setTimeout(function() {
            $('.dropdown-toggle').dropdown();
            $('#w0').multiselect();
        }, 1500);
    });
</script>
