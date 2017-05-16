<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Promotions';
?>
<div class="business_promo_single">
    <div class="panel panel-success">
        <div class="panel-body">
            <div id="consumer_promo_saved" class="container tab-pane active">
                <div class="form-group col-md-12">
                    <div class="btn-group pull-right">
                        <?= Html::button('Tip', [
                            'class' => 'btn btn-default btn-tip-show',
                            'onclick' => 'for_setup_tip()'
                        ]); ?>
                        <?= Html::button('Hide tip', [
                            'class' => 'btn btn-default btn-tip-hide hidden',
                            'onclick' => 'for_setup_tip()'

                        ]); ?>
                    </div>
                </div>
                <div class="panel panel-default panel-tip col-md-12">
                    <div class="panel-body">
                        <p>
                            This section shows a list of all the promotions you have saved. Promos are saved for
                            specific services. To redeem a promo, just log in to your account on your mobile device and
                            show the merchant a copy of the promo. Be sure to review the promo expiration date and
                            terms.
                        </p>
                    </div>
                </div>
                <?php if (Yii::$app->session->hasFlash('error')): ?>
                    <div class="alert alert-error">
                        <p>
                            <?php echo Yii::$app->session->getFlash('error'); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if (Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-success">
                        <p>
                            <?php echo Yii::$app->session->getFlash('success'); ?>
                        </p>
                    </div>
                <?php endif; ?>
                <?php if (Yii::$app->session->hasFlash('tooltip') && !count($savedTiersData)) { ?>
                    <div class="row ">
                        <div class="col-sm-offset-2 col-sm-9 row-nomenu">
                            <div class="alert alert-info" role="alert">
                                <p><?php echo Yii::$app->session->getFlash('tooltip'); ?></p>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php foreach ($savedTiersData as $key => $data): ?>
                        <div class="panel-default panel-promo-tip col-md-7 panel-default-boder"
                             style="display: block; margin-top: 30px;">
                            <div class="panel-promo-tip-header">
                                <p>
                                    <a href="/<?= $data['business']['vanity_name']; ?>"><?= $data['business']['name']; ?></a>
                                </p>

                                <p>
                                    <?php
                                    $details = $data['business'];
                                    if ($details['is_home'] == 0) {
                                        if (isset($details['address'])) {
                                            echo Html::encode($details['address']);
                                        }
                                        if (isset($details['suite'])) {
                                            echo Html::encode($details['suite']);
                                        }
                                        echo '<br/>';
                                    } ?>
                                    <?= Html::encode($details['city_name']); ?>,
                                    <?= Html::encode($details['state_code']); ?>
                                    <?= Html::encode($details['zip']); ?><br/>
                                    <?= Html::img('@web/images/icon_phone.png', ['class' => 'business_icon']) ?>
                                    <?= Html::encode($details['phone']); ?><br/>
                                    <?= Html::img('@web/images/icon_web_site.png', ['class' => 'business_icon']) ?>
                                    <?= Html::encode($details['website']) ?><br/>
                                    <?= Html::img('@web/images/icon_email.png', ['class' => 'business_icon']) ?>
                                    <?= Html::encode($details['contact_email']) ?>
                                </p>
                            </div>    <!--./panel-tip-header-->
                        </div>
                        <?php foreach ($data['tiers'] as $key => $val): ?>
                            <div class="col-md-7 body-panel-border">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h3><strong><?= $val['promo_cat_title']; ?></strong></h3>

                                        <h3><strong><?= $val['promo_srv_title']; ?></strong></h3>

                                        <p>Expiration: <?= date('n/j/Y', strtotime($val['end'])); ?></p>

                                        <p><?= $val['terms']; ?></p>

                                        <p><strong><a href="/account/delete-consumer-promo"
                                                      class="delete-promo text-danger"
                                                      data-id="<?= $val['wl_id']; ?>"> Delete</a></strong></p>
                                    </div>
                                    <div class="col-md-2">
                                        <h3 style="text-decoration: line-through;">$<?= $val['standart_price']; ?></h3>

                                        <h3>$<?= $val['promo_price']; ?></h3>
                                    </div>
                                </div>    <!--./row-->
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.delete-promo', function () {
        var id = $(this).data('id'),
            url = $(this).attr('href');
        $.ajax({
            type: "GET",
            url: url,
            data: {id: id}
        });
        return false;
    });
</script>
