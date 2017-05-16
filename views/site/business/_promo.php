<?php

use yii\bootstrap\Html;
use yii\bootstrap\Modal;

?>
<div class="content" id="promo">
    <div class="buz-page-narrow-container clearfix">
        <div class="row page_title text-center">
            <span>Promotion</span>
        </div>
    </div>
    <?php if ($model->haveActivePromos()) { ?>
        <?php $promos = \app\models\Promo::getActivePromos($model->id); ?>
        <?php foreach ($promos as $promo) { ?>
            <?php $menus = $promo->getBus()
                ->one()
                ->getMenus()
                ->all();
            ?>
            <div class="promo-section-panel">
                <div class="multiple-promos-wrapper">
                    <div class="exp-panel clearfix">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Expiration: <?= date('n/j/Y', strtotime($promo->end)) ?></p>
                            </div>
                            <div class="col-md-12">
                                <p>Terms: <?= Html::encode($promo->terms) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php foreach ($menus as $menu) { ?>
                        <?php $menuTitleShowed = false; ?>
                        <?php if ($menu->haveActivePromos()) { ?>
                            <?php foreach ($menu->getCategoriesWithPromoOnly($promo->id) as $category) { ?>
                                <?php $services = $category->getFilledServicesWithPromo(); ?>
                                <?php if (count($services) > 0) { ?>
                                    <?php if (!$menuTitleShowed) { ?>
                                        <?php $menuTitleShowed = true; ?>
                                        <div class="row">
                                            <div class="col-md-12 section_title">
                                                <h3><?= Html::encode($menu->title) ?></h3>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-md-12 catageory_name">
                                            <h4><?= Html::encode($category->title) ?></h4>
                                        </div>
                                        <?php foreach ($services as $service) { ?>
                                            <?php $tiers = $service->tiers; ?>
                                            <?php $countTiers = count($tiers); ?>
                                            <?php if ($countTiers == 1) { ?>
                                                <div class="service_name_price">
                                                    <div class="col-md-9 col-xs-6">
                                                        <h3><?= Html::encode($service->title) ?></h3>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6">
                                                        <div class="row pull-right">
                                                            <div class="col-md-12">
                                                                <h3 class="text-right promo_price">
                                                                    $<?= Html::encode($tiers[0]->getPromoPrice()) ?></h3>

                                                                <div class="promo-ratting-star">
                                                                    <?= $this->render('_star', [
                                                                        'savedIds' => $savedIds,
                                                                        'tierId' => $tiers[0]->id,
                                                                        'srvId' => $service->id,
                                                                        'promoId' => $promo->id,
                                                                    ]) ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <hr/>
                                                </div>
                                            <?php } else { ?>
                                                <?php $c = 0; ?>
                                                <?php foreach ($tiers as $tier) { ?>
                                                    <?php $c++; ?>
                                                    <?php foreach ($tier->getFieldsValueOrdered() as $val) { ?>
                                                        <?php if (!empty($val['val'])) { ?>
                                                            <?php if ($c == 1) { ?>
                                                                <div class="service_name_price">
                                                                    <div class="col-md-6 col-xs-6">
                                                                        <h3><?= Html::encode($service->title) ?></h3>
                                                                    </div>
                                                                    <div class="col-md-6 col-xs-6">
                                                                        <div class="row pull-right">
                                                                            <div class="col-md-12">
                                                                                <h3 class="text-right promo_price">
                                                                                    $<?= Html::encode($tier->getPromoPrice()) ?></h3>

                                                                                <div class="promo-ratting-star">
                                                                                    <?= $this->render('_star', [
                                                                                        'savedIds' => $savedIds,
                                                                                        'tierId' => $tier->id,
                                                                                        'srvId' => $service->id,
                                                                                        'promoId' => $promo->id,
                                                                                    ]) ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <p><?php echo Html::encode($val['val']) ?></p>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            <?php } ?>
                                                            <div class="service_name_price tier-wrapper clearfix">
                                                                <?php if ($c > 1) { ?>
                                                                    <div class="col-md-12 col-xs-12">
                                                                        <div class="row pull-right">
                                                                            <div class="col-md-12">
                                                                                <h3 class="text-right promo_price">
                                                                                    $<?= Html::encode($tier->getPromoPrice()) ?></h3>

                                                                                <div class="promo-ratting-star">
                                                                                    <?= $this->render('_star', [
                                                                                        'savedIds' => $savedIds,
                                                                                        'tierId' => $tier->id,
                                                                                        'srvId' => $service->id,
                                                                                        'promoId' => $promo->id,
                                                                                    ]) ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <p><?php echo Html::encode($val['val']) ?></p>
                                                                    </div>                                                                    
                                                                <?php } ?>
                                                            </div>                                                            
                                                            <?php if ($c < $countTiers) { ?>
                                                                <div class="col-md-8">&nbsp;</div>
                                                                <div class="col-md-4 border-indent-tier">&nbsp;</div>
                                                            <?php } ?>
                                                            <?php break;
                                                        }
                                                    } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php Modal::begin([
            'header' => '<span id="modalHeaderTitle"></span>',
            'id' => 'formModal',
            'size' => 'modal-sm',
            'options' => array(
                'data-keyboard' => "false",
                'data-backdrop' => "static"
            ),
            'closeButton' => array(
                'label' => 'Cancel'
            )
        ]);
        ?>
        <div id='modalContent' class="modal-content1">
            <span>
                <?php if (Yii::$app->user->isGuest) { ?>
                    You must be signed in to save promotions. Sign in <a href="/log-in">here</a>, or sign up <a
                        href="/sign-up">here</a> for a free account if you don't already have one.
                <?php } elseif (Yii::$app->user->identity->getBusiness()) { ?>
                    Promotions can only be saved by consumer accounts. Please create a consumer account if you want to save promotions.
                <?php } ?>
            </span>
        </div>
        <?php Modal::end();
        ?>
    <?php } else { ?>
        <div class="no-promosmsg text-center">
            <span>There are currently not any promotions running. Please check back later.</span>
        </div>
    <?php } ?>
</div>
