<?php
/* @var $this yii\web\View */
/* @var $id int */
/* @var $price int */
/* @var $time int */
/* @var $title string */
/* @var $categories array */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\CustomCategory;

?>
<?php

$script = <<< JS
var forTierAddSaved=[];
JS;
$this->registerJs($script);


$hasNonamed = false;
$hideAddCat = '';
$showAddCat = 'hidden';
$count = count($categories);
$countMenuServices = $menu->countServices();
$minSort = $menu->getMinCatSort();

if ($count > 0):
    ?>

    <?php if (($countMenuServices > 0) || ($count > 1)) { ?>
    <div class="row cat-nav-row more_then_0_srv_<?= $menu->id ?>">
        <div class="col-xs-1 n_p">
            <p>Position</p>
        </div>
        <div class="col-xs-11">
            <?php if ($count > 1) echo "Category, " ?>
            Service, Detail

        </div>
    </div>
<?php } ?>
    <?php
    foreach ($categories as $key => $cat):
        ?>
        <div class="category_m_<?= $menu->id ?>">
            <?php
            if ($cat->title == '') {
                $hide_params = 'hidden';
                if ($cat->is_menu_cat == 0)
                    $hasNonamed = true;
            } else
                $hide_params = '';
            if ($hasNonamed) {
                $hideAddCat = 'hidden';
                $showAddCat = '';
            } else {
                $hideAddCat = '';
                $showAddCat = 'hidden';
            }
            ?>

            <?php
            if ($cat->is_menu_cat != 1) {
                ?>
                <div class="row cat-nav-row n_detail_categories">
                    <div class="col-xs-12">
                    <span class="cat-nav  col-xs-1">
                        <?php
                        if (($key != 0) && ($minSort != $cat->sort)) {
                            $uDisabled = '';
                        } else {
                            $uDisabled = 'disabled';
                        }
                        ?>

                        <?=
                        Html::a(
                            '<i class="glyphicon glyphicon-chevron-up"></i>', Url::toRoute(['account/menu-cat-sort', 'id' => $cat['id'], 'up' => true]), [
                            'class' => 'btn btn-primary btn-xs ' . $uDisabled,
                            'onclick' => '
                                category_sort(this,' . $id . ');
                                return false;
                            '
                        ]);
                        ?>

                        <?php if ($key != $count - 1) {
                            $dDisable = '';
                        } else {
                            $dDisable = 'disabled';
                        }
                        ?>
                        <?=
                        Html::a(
                            '<i class="glyphicon glyphicon-chevron-down"></i>', Url::toRoute(['account/menu-cat-sort', 'id' => $cat['id'], 'up' => false]), [
                            'class' => 'btn btn-primary btn-xs ' . $dDisable,
                            'onclick' => '
                                category_sort(this,' . $id . ');
                                return false;'
                        ]);
                        ?>

                    </span>
                        <!-- Span for category Name -->
                    <span class="label label-category-title col-xs-2" id="div-title-cat-<?= $cat->id ?>">
                        <?php
                        $catHasTitle = ($cat['title'] != '') ? true : false;
                        if ($cat['title'] != '') {
                            $catHasTitle = true;
                            $editable = 'hidden';
                            $noeditable = '';
                        } else {
                            $catHasTitle = false;
                            $editable = '';
                            $noeditable = 'hidden';

                        }
                        echo Html::label($cat['title'], null, ['id' => 'label-title-cat-' . $cat->id, 'class' => $noeditable]);


                        echo Html::input('text', 'input-title-cat-' . $cat->id, $cat['title'], [
                            'id' => 'input-title-cat-' . $cat->id,
                            'size' => '45',
                            'class' => $editable
                        ]);
                        ?>

                    </span>


                        <?php {
                            ?>
                            <span class="cat-nav cat-nav-edit">
                        <?php
                        echo Html::a('Edit', null, [
                            'class' => ' btn-link ' . $noeditable,
                            'id' => 'edit_category_' . $cat->id,
                            'onclick' => '
                                category_edit_btn(this,' . $cat->id . ');
                            '
                        ]);
                        ?>
                        </span>
                            <span class="cat-nav">
                <?php
                echo Html::a('Save', Url::to(['account/update-category-name']), [
                    //'class' => 'btn btn-info hidden',
                    'class' => 'btn btn-info ' . $editable,
                    'id' => 'save_category_' . $cat->id,
                    'onClick' => '
                        category_edit_name_save(this,' . $cat->id . ',' . $id . ');
                        return false;
                    '
                ]);
                ?>
                        </span>
                            <span class="cat-nav">

                <?php
                echo Html::button('Cancel', [
                    'class' => 'btn btn-default ' . $editable,
                    'id' => 'cancel_category_' . $cat->id,
                    'onClick' => '
                        category_edit_cancel(this,' . $cat->id . ');
                        return false;
                    '
                ]);
                ?>
                        </span>
                            <span class="cat-nav">
                <?php
                echo Html::button('Delete', [
                    'class' => ' btn-link delete-link  ' . $editable,
                    'id' => 'delete_category_' . $cat->id,
                    'onclick' => '
                        $("#btn_delCategoryModal_confirm").attr("del_id",' . $cat->id . ');
                        $("#deleteCategoryModal").modal("show");
                    '
                ]);
                ?>
                        </span>
                            <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="row cat-nav-row n_detail_categories with-error" id="category_title_error_<?= $cat->id ?>">

                </div>
                <div class="row cat_params_<?= $cat->id ?> <?= $hide_params ?>">
                    <!-- Description block -->
                    <div class="col-xs-offset-1 col-xs-10">
                        <div class="form-group">
                            <?php
                            $descriptionCat = (!isset($cat->description) || $cat->description === '') ? null : $cat->description;
                            if ($descriptionCat === null) {
                                $addDescriptionCatVisible = true;
                            } else {
                                $addDescriptionCatVisible = false;
                            }
                            echo Html::label($cat->title . ' description', null, ['id' => 'description_cat_' . $cat->id . '_label', 'class' => (!$addDescriptionCatVisible) ? 'control-label' : 'control-label hidden']);
                            echo Html::button('+ Add '.$cat->title.' description', ['class' => ($addDescriptionCatVisible) ? ' btn-link' : ' btn-link  hidden',
                                'id' => 'add_description_cat_' . $cat->id,
                                'onclick' => '
                                    category_add_description_btn(this,' . $cat->id . ');
                                '
                            ]);
                            $description = (!isset($cat->description) || $cat->description === '') ? null : $cat->description;
                            echo Html::textarea('description_cat_' . $cat->id, $description, [
                                'id' => 'description_cat_' . $cat->id,
                                'style' => 'resize:none',
                                'class' => (!$addDescriptionCatVisible) ? 'form-control' : 'form-control hidden',
                                'base_value' => (!isset($cat->description) || $cat->description === '') ? '' : $cat->description,
                                'disabled' => true
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="col-xs-1 n_categories">
                        <div class="row">
                            <?php
                            echo Html::button('Edit', ['class' => (!$addDescriptionCatVisible) ? 'col-xs-12 btn-link' : ' btn-link col-xs-12 hidden',
                                'id' => 'edit_description_' . $cat->id,
                                'onClick' => 'editCatParam(this, ' . $cat->id . ', "description");'
                            ]);
                            ?>
                        </div>
                        <div class="row">
                            <?=
                            Html::a('Save', Url::to(['account/update-category-param']), [
                                'class' => 'btn btn-info col-xs-6 hidden',
                                'id' => 'save_description_cat_' . $cat->id,
                                'onClick' => 'saveCatParam(this, ' . $cat->id . ', "description") ;return false;'
                            ])
                            ?>
                        </div>
                        <div class="row">
                            <?=
                            Html::button('Cancel', ['class' => 'btn btn-default col-xs-6 hidden',
                                'id' => 'cancel_description_cat_' . $cat->id,
                                'onClick' => 'cancelCatParam(this, ' . $cat->id . ', "description") ;return false;'
                            ])
                            ?>
                        </div>
                        <div class="row">
                            <?php
                            echo Html::a('Delete', Url::to(['account/update-category-param']), ['class' => ' btn-link col-xs-12 delete-link  hidden',
                                'id' => 'delete_description_cat_' . $cat->id,
                                'onClick' => 'deleteCatParam(this, ' . $cat->id . ', "description") ;return false;'
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div id="menu-cat-<?= $cat->id ?>-services">
                <?php
                echo $this->renderAjax('_detail_services', [
                    'category' => $cat,
                    'services' => $cat->services,
                    'menu_id' => $id,
                ]);
                ?>
            </div>


            <?php
            if ($cat->is_menu_cat != 1) {
                ?>
                <div class="row n_add_disclaimer cat_params_<?= $cat->id ?> <?= $hide_params ?>">
                    <!-- Disclaimer block -->
                    <div class="col-xs-offset-1 col-xs-10">
                        <div class="form-group">
                            <?php
                            $disclaimerCat = (!isset($cat->disclaimer) || $cat->disclaimer === '') ? null : $cat->disclaimer;
                            if ($disclaimerCat === null) {
                                $addDisclaimerCatVisible = true;
                            } else {
                                $addDisclaimerCatVisible = false;
                            }
                            echo Html::label($cat->title . ' disclaimer', null, ['id' => 'disclaimer_cat_' . $cat->id . '_label', 'class' => (!$addDisclaimerCatVisible) ? 'control-label' : 'control-label hidden']);
                            echo Html::button('+ Add '.$cat->title.' disclaimer', ['class' => ($addDisclaimerCatVisible) ? ' btn-link' : '  btn-link  hidden',
                                'id' => 'add_disclaimer_cat_' . $cat->id,
                                'onclick' => '
                                    category_add_disclaimer_btn(this,' . $cat->id . ');
                                 '
                            ]);
                            $disclaimer = (!isset($cat->disclaimer) || $cat->disclaimer === '') ? null : $cat->disclaimer;
                            echo Html::textarea('disclaimer_cat_' . $cat->id, $disclaimer, [
                                'id' => 'disclaimer_cat_' . $cat->id,
                                'style' => 'resize:none',
                                'class' => (!$addDisclaimerCatVisible) ? 'form-control' : 'form-control hidden',
                                'base_value' => (!isset($cat->disclaimer) || $cat->disclaimer === '') ? '' : $cat->disclaimer,
                                'disabled' => true
                            ]);
                            ?>
                        </div>

                    </div>
                    <div class="col-xs-1 n_categories">
                        <div class="row">
                            <?php
                            echo Html::button('Edit', ['class' => (!$addDisclaimerCatVisible) ? ' btn-link col-xs-12' : ' btn-link  col-xs-12 hidden',
                                'id' => 'edit_disclaimer_' . $cat->id,
                                'onClick' => 'editCatParam(this, ' . $cat->id . ', "disclaimer") ;return false;'
                            ]);
                            ?>
                        </div>
                        <div class="row">
                            <?=
                            Html::a('Save', Url::to(['account/update-category-param']), ['class' => 'btn btn-info col-xs-6 hidden',
                                'id' => 'save_disclaimer_cat_' . $cat->id,
                                'onClick' => 'saveCatParam(this, ' . $cat->id . ', "disclaimer") ;return false;'
                            ])
                            ?>
                        </div>
                        <div class="row">
                            <?=
                            Html::button('Cancel', ['class' => 'btn btn-default col-xs-6 hidden',
                                'id' => 'cancel_disclaimer_cat_' . $cat->id,
                                'onClick' => 'cancelCatParam(this, ' . $cat->id . ', "disclaimer") ;return false;'
                            ])
                            ?>
                        </div>
                        <div class="row">
                            <?php
                            echo Html::a('Delete', Url::to(['account/update-category-param']), ['class' => ' btn-link col-xs-12  delete-link hidden',
                                'id' => 'delete_disclaimer_cat_' . $cat->id,
                                'onClick' => 'deleteCatParam(this, ' . $cat->id . ', "disclaimer") ;return false;'
                            ]);
                            ?>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>
    <?php endforeach; ?>

    <?php
    if ($cat->is_menu_cat != 1) {
        ?>
        <!--        <hr class="menu-section"/>-->

        <div class="row-cust-cat add_cat_<?= $id ?> <?= $hideAddCat ?>">
        <span class="cat-nav n-arrow col-xs-1">

            <?=
            Html::a(
                '<i class="glyphicon glyphicon-chevron-up"></i>', null, [
                'class' => 'btn btn-primary btn-xs',
                'disabled' => true,
            ]);
            ?>
            <?=
            Html::a(
                '<i class="glyphicon glyphicon-chevron-down"></i>', null, [
                'class' => 'btn btn-primary btn-xs',
                'disabled' => true,
            ]);
            ?>

        </span> 
        <span class="label label-category-title col-xs-3">
                <?= Html::label(null, null) ?>
        </span>
        <span class="cat-nav col-xs-6 "> 

            <?php
            echo Html::a('+ Add category', Url::to(['account/menu-category-add']), [
                'class' => 'btn-link add_cat_' . $id . ' ' . $hideAddCat,
                'id' => 'delete_description_cat_' . $id,
                'onclick' => '
                    category_add_btn(this,' . $id . ');
                    return false;
                '
            ]);
            echo Html::label('You must fill names to all Categories before creating new one', null, [
                'class' => 'add_cat_' . $id . ' ' . $showAddCat,
            ])
            ?>
        </span>
        </div>
    <?php } ?>

<?php else: ?>
<?php endif; ?>
