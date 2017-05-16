<?php
/* @var $this yii\web\View */
/* @var $new_promo boolean */
/* @var $model app\models\Promo */
/* @var $menu_service array */
/* @var $promos mixed */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\models\Promo;

if (!isset($promos)) {
    $promos = [];
}
$this->title = 'Promotion';
?>
<div class="business_promo_double">
    <div class="form-group">
        <div class="btn-group">
            <?php if (!$new_promo): ?>
                <?php
                echo Html::a('+ Add promotion', Url::to(['account/promo-group-add']), [
                    'class' => 'btn btn-warning',
                    'onClick' => '
                        $.ajax({
                            type: "POST",
                            url: $(this).attr("href"),
                            data     :{},
                            success: function(response) {
                                $(".add-promo").html(response);
                            }
                        });
                        return false;'
                ]);


                ?>
            <?php endif; ?>
        </div>
        <div class="btn-group pull-right">
            <?= Html::button('Tip', ['class' => 'btn btn-default btn-tip-show', 'onclick' => 'for_setup_tip()']); ?>
            <?= Html::button('Hide tip', ['class' => 'btn btn-default btn-tip-hide hidden', 'onclick' => 'for_setup_tip()']); ?>
        </div>
    </div>
    <div class="panel panel-default panel-tip">
        <div class="panel-body">
            <p>Promotions can be created for any service that is listed in your menu. You can run as many promotions as
                you want. However, the same menu category can't have two promotions running at the same time. This means
                you must select different categories if you want to run multiple promotions at the same time.</p>

            <p><strong>Step 1:</strong> Fill out promo form and select "Save". Note, your promo will only be shown
                during dates it is valid.</p>

            <p><strong>Step 2:</strong> -- <span style="color:#f00;">Important</span> -- You must select "Publish" to
                activate the promo or else it won't be shown to consumers. You can also select "Edit" if you need to
                make any changes before being Published.</p>

            <p><strong>Step 3:</strong> The promo is now active and will expire as it was setup. You can also
                immediately end the promo by selecting the "End now" button.</p>

            <p><strong>Step 4:</strong> Run more promos by selecting "<strong>+ Add promotion</strong>" button at the
                top. Get creative and have fun.</p><br/>
            Visit <a href="/business-support">Business Support</a> to learn more about creating promotions.
            <div class="pull-right">
            </div>
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
            <?= $this->title ?>
        </div>
        <div class="panel-body">
            <div class="add-promo">

                <?php //else:   ?>
                <?php if (count($promos) == 0): ?>
                    <?php
                    echo $this->renderAjax('promo/_add', [
                        'model' => $model,
                        'menu_service' => $menu_service,
                        'popup' => false
                    ]);

                    ?>
                <?php else: ?>                   
                <?php endif; ?>
            </div>
            <?php foreach ($promos as $key => $promo): ?>
                <div id="promo_<?= $promo->id ?>" class="form-group promo-form">

                    <?php
                    $promo->start = date('n/j/Y', strtotime($promo->start));
                    $promo->end = date('n/j/Y', strtotime($promo->end));
                    ?>
                    <?= $this->render('promo/_group', [
                        'promo' => $promo,
                        'menu_service' => $menu_service,
                        // 'delete' => (count($promos) > 1)
                        'delete' => ($promo->active != Promo::STATUS_ACTIVE)
                    ]); ?>
                </div>
                <?php if ($key != count($promos) - 1): ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php //endif;  ?>
        </div>
    </div>
</div>
