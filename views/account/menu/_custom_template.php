<?php
/* @var $this yii\web\View */
/* @var $model app\models\CustomCategory */
/* @var $menu array|null */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
?>
<div class="menu-template-select">
    <?php
     // print_r($model);
      //echo "=====menu_id" . $menu_id . "=====service_id" . $service_id;
      //print_r($attr);
    //  echo "=====additional=====" ;
     // print_r($additional);
    ?>
    <?php
    //$form = ActiveForm::begin(['id' => 'custom-template-form']);
    ?>
    <div class="row" style='margin-bottom: 15px;'>
        <div class="col-xs-3">
            <div class="row">
                <div class="col-xs-6"> 
                </div>
                <div class="col-xs-6"> 
                    <p>Service</p>
                </div>
            </div>
        </div>
        <div class="col-xs-9">
            <div class="row">

                <div class="col-xs-2">
                    <p>Price</p>
                </div>
                <div class="col-xs-2">
                    <?= $additional['title'] ?>
                </div>
                <div class="col-xs-8">
                    <p>Description</p>
                </div>
            </div>   
        </div>
    </div>
    <div class="form-group">
        <?php
        foreach ($model as $template) {
            ?>

            <div class="row" style='margin-bottom: 15px;'>
                <div class="col-xs-3"> 
                    <div class="row">
                        <div class="col-xs-6">        
                            <?php // echo Html::submitButton('Select', ['class' => 'btn  btn-info',]) ?>
                      
                            <?php
                            echo Html::a('Select', Url::to(['account/select-price-template','service_id'=>$service_id,'template_id'=>$template['t_id']]), [
                                    'class' => 'btn  btn-info',
                                    'id' => 'select_template_'.$template['t_id'] 
                    ])
                            ?>
                        </div>
                        <div class="col-xs-6"> 
                            <?= Html::input('text', null, null, ['class' => 'form-control', 'placeholder' => 'service item','disabled' => true]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-9"> 

                    <div class="row">

                        <div class="col-xs-2">
                            <?php
                            for ($i = 0; $i < $template['t_count']; $i++) {
                                echo Html::input('text', null, null, ['class' => 'form-control', 'placeholder' => '*$','disabled' => true]);
                            }
                            ?>
                        </div>
                        <div class="col-xs-2">
                            <?php
                            if ($template['t_disp_add_atr'] == 1) {

                                for ($i = 0; $i < $template['t_count']; $i++) {
                                    if ($template['display_type'] == 0) {
                                        echo Html::input('text', null, $attr[$i]['value'], ['class' => 'form-control','disabled' => true]);
                                    } else {
                                        
                                        echo Html::dropDownList('1', null, ArrayHelper::map($attr, 'value_id','value'),['class'=>'form-control','prompt'=>'time','disabled' => true]);
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div class="col-xs-8">
                            <?php
                            if ($template['t_desc_type'] == 0) {
                                for ($i = 0; $i < $template['t_count']; $i++) {
                                    echo Html::input('text', null, null, ['class' => 'form-control', 'placeholder' => 'Unique service description','disabled' => true]);
                                }
                            } else {
                                echo Html::textarea('text', null, ['class' => 'form-control', 'rows' => $template['t_count'], 'placeholder' => 'Same service description for regular and upgrade (for the same service)','disabled' => true]);
                            }
                            ?>

                        </div>
                    </div>


                </div>


            </div> 
    <?php
}
?>
    </div>
    <div class="form-group"> </div>

<?php //ActiveForm::end(); ?>

</div>