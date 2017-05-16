<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\forms\ProfileOperationForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\time\TimePicker;
use kartik\switchinput\SwitchInput;


$this->title = 'Hours of Operation';
$hide = 'hidden';
$hasOperations = $model->hasOperations();
$edit=($hasOperations)? 0: 1;
?>
<div class="user-account-profile-operation gray-content">
    <div class="to_hours">
        <?php
        echo $this->renderAjax('_hours_base', [
                'model' => $model,
                'edit'=>$edit,
        ]);
        ?>
    </div>
    <div class="row">
        <div class="col-sm-3 col-sm-offset-1">
            <?php
            if ($model->hasOperations()) 
                $hide = '' ;

            echo Html::a('Edit', Url::toRoute(["account/get-operation", 'edit' => 1]), [
                    'id' => "edit_operation",
                    'class' => 'btn col-sm-2 ' . $hide,
                    'onClick' => '
                        operation_hours_edit_btn(this);
                        return false;
                    '
            ]);
            ?>
        </div>
        <div class="col-sm-3 ">  
        </div>
    </div>
</div>