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
$hide = '';
if (($edit == 0) && ($model->hasOperations())) {
    $disabled = true;
} else {
    $disabled = false;
}

?>
<div class="user-account-profile-operation gray-content">
    <?php //Yii::$app->tip->display('text 222 text 222 text 222 text 222 text');  ?>
    <?php
    $form = ActiveForm::begin([
        'action' => Url::to(['account/operation']),
        'id' => 'profile-operation-form',
        'options' => ['class' => 'form-inline'],
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{beginWrapper}\n{input}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'offset' => '',
                'wrapper' => 'timer_container',
                'error' => '',
            ],
        ],
    ]);
    ?>
    <table class="table col-md-offset-1 profile-operation-table">
        <tr>
            <td width="160">Open</td>
            <td width="160">Close</td>
            <td width="120">Closed all day</td>
        </tr>
    </table>
    <?php
    $days = $model::getDays();
    $hasOperations = $model->hasOperations();
    if ($hasOperations) {
        $hidden = 'hidden';
    } else {
        $hidden = '';
    }
    foreach ($days as $key => $day):

        ?>
        <div class="operation-group">
            <label class="col-sm-1 operation-day "><?= $day ?></label>
        </div>
        <div class="operation-group ">
            <?= $form->field($model, $day . '_open')
                ->widget(TimePicker::classname(), [
                    'pluginOptions' => [
                        'defaultTime' => '8:00 AM'
                    ],
                    'disabled' => (($model->{$day . '_active'} != 0) || ($disabled))
                        ? true
                        : false,
                    'options' => [
                        'id' => 'open_' . $key,
                        'class' => 'days day_' . $key,
                        'base_value' => $model->{$day . '_open'},
                        'dayoff' => ($model->{$day . '_active'})
                    ]
                ])
                ->label(false); ?>
            <?= $form->field($model, $day . '_close')
                ->widget(TimePicker::classname(), [
                    'pluginOptions' => ['defaultTime' => '5:00 PM'],
                    'disabled' => (($model->{$day . '_active'} != 0) || ($disabled))
                        ? true
                        : false,
                    'options' => [
                        'id' => 'close_' . $key,
                        'class' => 'days day_' . $key,
                        'base_value' => $model->{$day . '_close'},
                        'dayoff' => ($model->{$day . '_active'})
                    ]
                ])
                ->label(false); ?>
            <?= $form->field($model, $day . '_active')
                ->widget(SwitchInput::classname(), [
                    'options' => [
                        'class' => 'days_switch',
                        'base_value' => $model->{$day . '_active'},
                    ],

                    'disabled' => $disabled,
                    'pluginEvents' => [
                        'switchChange.bootstrapSwitch' => '
                                function(){operation_hours_switch_day(' . $key . ');}
                            ',
                    ]
                ])
                ->label(false); ?>
        </div>
    <?php endforeach; ?>

    <div class="row n_profile_btn">
        <div class="col-sm-offset-1 col-sm-4">
            <?php if ($disabled) {
                $hide = 'hidden';
            } else {
                $hide = '';
            } ?>
            <?= Html::submitButton('Save', ['class' => 'btn btn-info save_operation ' . $hide]) ?>
            <?php
            if ($model->hasOperations()) {
                $operations = 1;

            } else {
                $operations = 0;
                $hide = 'hidden';
            }
            echo Html::a('Cancel', Url::toRoute([
                "account/get-operation",
                'edit' => 0
            ]), [
                'class' => 'btn  btn-default  ' . $hide,
                'id' => 'cancel_edit_operation',
                'onClick' => '
                        operation_hours_cancel(this,' . $operations . ');
                        return false;
                    '
            ])
            ?>
        </div>
        <div class="col-sm-3 ">

        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
