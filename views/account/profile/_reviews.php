<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\forms\ProfileReviewForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Profile reviews';
?>
<div class="user-account-profile-reviews gray-content">
    <?php
        if ($biz->isContractor()) {
            ?>
            <div class="col-sm-12">
                <p>
                   <i> If you don`t have any Yelp reviews, then you can add the Yelp URL for your agency and those ratings
                    will be displayed on your Wizerd site.
                       </i>
                </p>
            </div>
            <?php
        }
    ?>
    <?php

   // Yii::$app->tip->display('text 444 text 444 text 444 text 444 text');
    $form = ActiveForm::begin([
        'id' => 'profile-review-form',
        'options' => ['class' => 'form-horizontal'],
        'layout' => 'horizontal',
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'template' => "{label} {beginWrapper} {input} {error} {endWrapper}",
            'horizontalCssClasses' => [
                'offset' => '',
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-6 div-input',
                'error' => '',
            ],
        ],
    ]);
    ?>
    <?= $form->field($model, 'example')->textInput([
        'value' => 'http://www.yelp.com/biz/business-name',
        'readonly' => true
    ]) ?>
    <?php
    if ($model->yelp_url) $disabled=true;
    else $disabled=false;
    
    ?>
     
 
    <?= $form->field($model, 'yelp_url',['inputTemplate' => '<div class="input-group "><span class="input-group-addon">http://</span>{input}</div>',])
            
           ->textInput([
    
     'base_value'=>$model->yelp_url,
      'disabled'=>$disabled
             
    ]) 
             
            ?>
    <div class="form-group">
        <div class="col-sm-1 col-sm-offset-2 n_reviews_btn">
            <?php
            $hide=($disabled) ? "hidden":" ";
                    ?>
            <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-info save_yelp '.$hide]) ?>
            <?php
             $hide=(!$disabled) ? "hidden":" ";
                    echo Html::a('Edit',null, [
                            'id'=>'edit_yelp',
                            'class' => 'btn btn-block col-sm-2 n_reviews_edit '.$hide,
                            'onClick' => '
                                 $("#profilereviewform-yelp_url").prop("disabled",false);
                                 $(".save_yelp").toggleClass("hidden");
                                 $(this).toggleClass("hidden");  
                                 $("#cancel_yelp").toggleClass("hidden");
                            '
                    ])
                    ?>
        </div>
        <div class="col-sm-1 col-sm-offset-2 n_reviews_btn">
            <?php
            
                 echo   Html::a('Cancel',null, [
                            'class' => 'btn btn-block btn-default col-sm-2 hidden',
                            'id'=>'cancel_yelp',
                            'onClick' => ' 
                                
                                $("#profilereviewform-yelp_url").val($("#profilereviewform-yelp_url").attr("base_value"));
                                 $("#profilereviewform-yelp_url").prop("disabled",true);
                                 $(".save_yelp").toggleClass("hidden");
                                 $(this).toggleClass("hidden");  
                                 $("#edit_yelp").toggleClass("hidden");
                            '
                    ])
                    ?>
            </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>