<?php
use yii\bootstrap\Html;
use yii\helpers\Url;

?>
<div class="row content" id="menu">
    <div class="buz-page-narrow-container clearfix">
        <div class="row page_title">
            <span>Menu</span>
        </div>
    </div>
    <div class="biz-menupage-narrow-container clearfix">
        <?php foreach ($model->menus as $menu) : ?>
            <?php if ($menu->countFilledTiers()) : ?>
                <?php if ($menu->nonamed == 0): ?>
                    <div class="category_title">
                        <div class="category_title_div">
                            <div class="col-sm-12 category_title_inner clearfix text-center">
							<p><?= Html::encode($menu->title) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($menu->description)) : ?>
                    <div class="buz-page-narrow-container clearfix">
                        <div class="row description">
                            <div class="col-sm-12 description_div top-decs-paddding">
                                <?= nl2br(Html::encode($menu->description)) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?= $this->render('_detail_categories', ['categories' => $menu->categories,]); ?>
                <?php if (isset($menu->disclaimer)) : ?>
                    <div class="col-sm-12 menu_disclaimer">
                        * <?= nl2br(Html::encode($menu->disclaimer)) ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
