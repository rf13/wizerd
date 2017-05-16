<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Promo;

/* @var $this yii\web\View */
/* @var $promo mixed */
/* @var $delete boolean */

$promo_groups = $promo->groups;
?>
<div class="promo-group">
    <h4>
        <?php foreach ($promo_groups as $group): ?>
            <?= Html::label($group->category->title . ' services', null, [
                'class' => 'label label-default inline-block',
            ]) ?>
        <?php endforeach; ?>
    </h4>
    <div class="row">
    <div class="col-sm-3 form-group flt-none">
        <?= Html::label('Discount, %') ?>
        <?= Html::input('text', 'discount', $promo->discount, [
            'class' => 'form-control', 'readonly' => true,
            'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">%</span></div>',
        ]) ?>
    </div>
        <div class="col-sm-3 form-group flt-none">
            <?= Html::label('Price rounding') ?>
            <?= Html::dropDownList('round', $promo->round, [
                Promo::ROUND_UP => 'Round price up to nearest dollar',
                Promo::ROUND_DOWN => 'Round price down to nearest dollar',
                Promo::ROUND_NOT => 'Don’t round price',
            ], [
                'class' => 'form-control', 'disabled' => 'disabled'
            ]) ?>
        </div>
    </div>
    <div class="row">
<!--        <div class="col-xs-6 col-sm-3 form-group">-->
<!--             Html::label('NCO')-->
<!--             Html::dropDownList('nco', $promo->nco,-->
<!--                [Promo::NCO_NO => 'No', Promo::NCO_YES => 'Yes'],-->
<!--                ['class' => 'form-control', 'disabled' => 'disabled']-->
<!--            ) -->
<!--        </div>-->
<!--        <div class="col-xs-6 col-sm-3 form-group">-->
<!--             Html::label('Combine')-->
<!--             Html::dropDownList('combine', $promo->combine,-->
<!--//                [Promo::COMBINE_NO => 'No', Promo::COMBINE_YES => 'Yes'],-->
<!--//                ['class' => 'form-control', 'disabled' => 'disabled']-->
<!--//            )-->
<!--        </div>-->
        <div class="col-xs-6 col-sm-3 form-group">
            <?= Html::label('Valid') ?>
            <?= Html::input('text', 'start', date('n/j/Y', strtotime($promo->start)), [
                'class' => 'form-control', 'readonly' => true
            ]) ?>
        </div>        
    </div>
    
    <div class="row">
    <div class="col-xs-6 col-sm-3 form-group">
            <?= Html::label('Expiration') ?>
            <?= Html::input('text', 'end', date('n/j/Y', strtotime($promo->end)), [
                'class' => 'form-control', 'readonly' => true
            ]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-8 form-group">
            <?= Html::label('Terms') ?>
            <?= Html::textarea('desc', $promo->terms, [
                'readonly' => true, 'class' => 'form-control', 'style' => 'resize:none'
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <?= Html::label('Status: ' . $promo->status, null, [
                'class' => 'status-label'
            ]) ?>
        </div>
        
<!--        <div class="col-xs-4">-->
<!--            Html::input('text', 'saved', 'Consumer saves: ' . count($promo->wishLists), [-->
<!--                'class' => 'form-control', 'readonly' => true-->
<!--            ])-->
<!--        </div>-->
    </div>
    <div class="row">
            <?php
            if ($promo->active == Promo::STATUS_CREATE) {
                ?>
            <div class="col-sm-2">
            <?php
                echo Html::a('Publish',
                    Url::toRoute(['account/promo-group-publish', 'id' => $promo->id]), [
                        'class' => 'btn btn-success btn-block',
                        'onclick' => '
                            $.ajax({
                                type: "POST",
                                url: $(this).attr("href"),
                                success: function(data) {
                                    if (data == false) {
                                        console.log("The promotion was not published");
                                    }
                                }
                            });
                        return false;'
                    ]);
                ?>
                </div>
                <div class="col-xs-2">
                <?php
                echo Html::a('Edit',Url::toRoute(['account/promo-group-update', 'id' => $promo->id]), [
                'class' => 'btn btn-new btn-block edit_btn',
                'onclick' => '
                    $(".edit_btn").toggleClass("disabled");
                    $(".add-promo").html("");
                   
                    $.ajax({
                        type: "GET",
                        url: $(this).attr("href"),
                        success: function(data) {
                            if (data) {
                                $("#promo_' . $promo->id . '").html(data);
                                 
                            }
                        }
                    });
                return false;'
            ]);
                ?>
            </div>
            
                <?php
            } else if ($promo->active == Promo::STATUS_ACTIVE) {
                echo Html::a('End now', Url::toRoute(['account/promo-group-end', 'id' => $promo->id]), [
                    'class' => 'btn btn-danger btn-block end_btn_promo',
                    'onclick' => '
                        $.ajax({
                            type: "POST",
                            url: $(this).attr("href"),
                            success: function(data) {
                                if (data == false) {
                                    console.log("The promotion was not ended");
                                }
                            }
                        });
                    return false;'
                ]);
            } else 
                //echo Html::a('Create new', Url::toRoute(['account/promo-group-update', 'id' => $promo->id,'clear'=>2]), [
                echo Html::a('Create new', Url::toRoute(["account/promo-group-add", 'id' => $promo->id]), [
                'class' => 'btn btn-new btn-block create_new_btn_promo',
                'onclick' => '
                    $.ajax({
                        type: "GET",
                        url: $(this).attr("href"),
                        success: function(data) {
                            if (data) {
                             $(".add-promo").html(data);
                                //$("#promo_' . $promo->id . '").html(data);
                            }
                        }
                    });
                return false;'
            ]);
            ?>
            
            

    </div>
    <?php if ($delete): ?>
        <div class="row">
            <div class="col-sm-8 form-group">
                <?= Html::a('Delete', Url::toRoute(['account/promo-group-delete', 'id' => $promo->id]), [
                    'class' => 'btn btn-delete',
                    'onclick' => '
                        $.ajax({
                            type: "POST",
                            url: $(this).attr("href"),
                            success: function(data) {
                                if (data == false) {
                                    console.log("The promotion was not ended");
                                }
                            }
                        });
                    return false;'
                ]) ?>
            </div>
        </div>
    <?php endif; ?>
</div>
