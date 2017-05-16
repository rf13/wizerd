<?php
/* @var $this yii\web\View */
/* @var $categories array */

use app\models\CustomCategory;
use yii\helpers\Html;

foreach ($categories as $key => $cat):
    $countTiers = $cat->countFilledTiers();
    if ($countTiers) {
        ?>
        <?php if ($cat->is_menu_cat != 1) : ?>
            <div class="business_cat_title_row">
                <div class="color-main">
                    <div class="col-md-12 business_cat_title">
                        <p><?= Html::encode($cat->title) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($cat->description)) : ?>
            <div class="row">
                <div class="col-md-12">
                    <p><?= nl2br(Html::encode($cat->description)); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php echo $this->render('_services_detail', ['category' => $cat]); ?>

        <?php if (isset($cat->disclaimer)) : ?>
            <div class="row">
                <div class="col-sm-12">
                    <p class="catageory-disclaimer">* <?= nl2br(Html::encode($cat->disclaimer)) ?><p>
                </div>
            </div>
        <?php endif; ?>

        <?php
    }
endforeach;
?>
