<?php
use yii\helpers\Html;

$services = $category->services;
?>

<?php
foreach ($services as $key => $srv): ?>
    <div class="row service_name_price">
        <div class="col-md-6 col-xs-6">
            <h3><?= Html::encode($srv->title) ?></h3>
        </div>
        <?php if (count(($tiers = $srv->tiers)) > 1) { ?>
            <div class="col-md-6 col-xs-6">
                <h3 class="text-right">$<?= Html::encode($tiers[0]->price) ?></h3>
            </div>
        <?php } ?>
        <?php
        $c = 0;
        foreach ($tiers as $tier) {
            $c++;
            $tiers = $srv->tiers;
            $countTiers = count($tiers);
            ?>
            <?php if ($countTiers > 1 && $c > 1) { ?>
                <div class="col-md-12">
                    <h3 class="text-right">$<?= Html::encode($tier->price) ?></h3>
                </div>
            <?php } ?>
            <?php if ($countTiers == 1) { ?>
                <div class="col-md-6 col-xs-6">
                    <h3 class="text-right">$<?= Html::encode($tier->price) ?></h3>
                </div>
            <?php } ?>
            <?php if ($tier->isFilled()) {
                ?>
                <div class="col-md-12 col-xs-12">
                    <div class="mobile_double_brd_none service-attributes-panel<?php echo $countTiers == 1 ? ' service-attributes-panel-border' : '' ?>">
                        <?php foreach ($tier->getFieldsValueOrdered() as $val) {
                            if (!empty($val['val'])) {
                                ?>
                                <div class="row">
                                    <div class="col-md-11 col-xs-12">
                                        <p class="description-text"><?php echo Html::encode($val['val']) ?></p>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                    <?php if ($countTiers > 1 && $countTiers > $c) { ?>
                        <div class="col-md-8 col-xs-8"></div>
                        <div class="col-md-4 col-xs-4 border-indent-tier"></div>
                    <?php } elseif ($countTiers >= $c) { ?>
                        <hr/>
                    <?php } ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
<?php endforeach; ?>
