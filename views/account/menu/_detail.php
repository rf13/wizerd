<?php
/* @var $this yii\web\View */
/* @var $tabs boolean */
/* @var $menu mixed */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$countMenuServices = $menu->countServices();
$countNonamed = $menu->countNonamed();
$haveFilledTiers = $menu->countFilledTiers();
$menuServices = $menu->countServices();

if (((($menu->description !== '') && ($menu->description !== null)) || (($menu->disclaimer !== '') && ($menu->disclaimer !== null))) || (($menuServices > 0) || (count($menu->categories) > 1))) {
    $hideAllAdditional = '';
} else {
    $hideAllAdditional = 'hidden';
}
$script=<<<JS
canSaveNonamed=$countNonamed;
limitChars(".n_edit_menu input");
JS;
$this->registerJs($script);

?>
<div class="menu-detail <?php echo $tabs ? 'gray-content' : ''; ?>">
    <?php
    ?>
    <div class="row menu-manage-row-head n_edit_menu">
        <div class="col-xs-10 ">

            <?=
            Html::input('text', 'menu_name_' . $menu->id, ($menu->nonamed == 0) ? Html::encode($menu->title) : null, [
                'class' => 'col-sm-3 hidden n_photo_input menu_edit_lnk_' . $menu->id,
                'id' => 'menu_name_' . $menu->id,
                'placeholder' => 'Section name',
                'base_value' => $menu->title,
                'size' => 30,
            ]);
            ?>

            <?php
            echo Html::a('Edit section', null, [
                'class' => 'col-sm-3 edit-link',
                'id' => 'edit-menu-' . $menu->id,
                'onclick' => '
                    $(".menu_edit_lnk_' . $menu->id . '").toggleClass("hidden");
                    $(this).toggleClass("hidden");
                    return false;
                '
            ]);
            echo Html::a('Save', Url::to(['account/update-menu-param']), [
                'class' => 'col-sm-2 btn btn-info hidden menu_edit_lnk_' . $menu->id,
                'id' => 'save-menu-' . $menu->id,
                'onclick' => '
                    menu_title_save('.$menu->id.',this);
                    return false;
                '
            ]);
            echo Html::a('Cancel', null, [
                'class' => 'col-sm-2  btn btn-default hidden menu_edit_lnk_' . $menu->id,
                'id' => 'cancel-menu-' . $menu->id,
                'onclick' => '
                    menu_title_cancel('.$menu->id.');
                    return false;
                '
            ]);
            echo Html::a('Delete entire section', Url::to(['account/menu-delete']), [
                'class' => 'col-sm-3 delete-link hidden menu_edit_lnk_' . $menu->id,
                'id' => 'delete-menu-' . $menu->id,
                'onclick' => '
                    menu_delete('.$menu->id.',this);
                    return false;
                '
            ]);
            ?>

        </div>
        <div class="col-xs-2">
            <?php
            if ($menu->nonamed == 0) {

                $up = ($menu->sort != $menu->getMinSort()) ? true : false;
                if ($up) {
                    echo Html::a(
                        '<i class="glyphicon glyphicon-chevron-left glyphicon-default"></i>', Url::toRoute(['account/menu-sort', 'id' => $menu->id, 'up' => true]), [
                        'class' => '',
                        'onclick' => '
                            menu_change_sort(this);
                            return false;
                        '
                    ]);
                } else {
                    echo '<i class="glyphicon glyphicon-chevron-left glyphicon-disabled"></i>';
                }
                ?>
                <span> Section position</span>
                <?php
                $down = ($menu->sort != $menu->getMaxSort()) ? true : false;
                if ($down) {

                    echo Html::a(
                        '<i class="glyphicon glyphicon-chevron-right glyphicon-default"></i>', Url::toRoute(['account/menu-sort', 'id' => $menu->id, 'up' => false]), [
                        'class' => '',
                        'onclick' => '
                            menu_change_sort(this);
                            return false;
                        '
                    ]);
                } else {
                    echo '<i class="glyphicon glyphicon-chevron-right glyphicon-disabled"></i>';
                }
            }
            ?>

        </div>
    </div>

    <div class="row n_description menu_addit_params_<?= $menu->id ?> <?= $hideAllAdditional ?>">
        <div class="col-xs-11 ">
            <?php
                $description = (!isset($menu->description) || $menu->description === '') ? null : $menu->description;
                if ($description === null) {
                    $addDescriptionVisible = true;
                } else {
                    $addDescriptionVisible = false;
                }
            ?>
            <?php
            //master description
            echo Html::label($menu->title . ' description', null, ['id' => 'description_menu_' . $menu->id . '_label', 'class' => (!$addDescriptionVisible) ? 'control-label' : 'control-label hidden']);
            ?>
            <?php
                echo Html::a('+ Add '. $menu->title . ' description', null, ['class' => ($addDescriptionVisible) ? 'btn-link' : ' btn-link hidden',
                    'id' => 'add_description_menu_' . $menu->id,
                    'onclick' => 'menu_add_param_button('.$menu->id.',"description",this);'
                ]);
            ?>
            <?php
                echo Html::textarea('description_menu_' . $menu->id, $description, [
                    'id' => 'description_menu_' . $menu->id,
                    'style' => 'resize:none',
                    'class' => (!$addDescriptionVisible) ? 'form-control' : 'form-control hidden',
                    'base_value' => (!isset($menu->description) || $menu->description === '') ? '' : $menu->description,
                    'disabled' => true
                ]);
            ?>
        </div>
        <div class="col-xs-1 n_btn_div">
            <div class="row">
                <?php
                echo Html::a('Edit', null, ['class' => (!$addDescriptionVisible) ? 'col-xs-12 btn-link' : 'col-xs-12 btn-link hidden',
                    'id' => 'edit_description_' . $menu->id,
                    'onclick' => ' editMenuParam(this,' . $menu->id . ',"description"); return false; '
                ]);
                ?>
            </div>
            <div class="row 2">
                <?=
                Html::a('Save', Url::to(['account/update-menu-param']), ['class' => 'btn btn-info col-xs-6 hidden',
                    'id' => 'save_description_menu_' . $menu->id,
                    'onClick' => ' saveMenuParam(this,' . $menu->id . ',"description"); return false; '
                ])
                ?>
            </div>
            <div class="row ">
                <?=
                Html::a('Cancel', null, ['class' => 'btn btn-default col-xs-6 hidden',
                    'id' => 'cancel_description_menu_' . $menu->id,
                    'onClick' => 'cancelMenuParam(this,' . $menu->id . ',"description");return false;'
                ])
                ?>
            </div>
            <div class="row">
                <?php
                echo Html::a('Delete', Url::to(['account/update-menu-param']), ['class' => 'col-xs-12 btn-link delete-link hidden',
                    'id' => 'delete_description_menu_' . $menu->id,
                    'onclick' => 'deleteMenuParam(this,' . $menu->id . ',"description");return false; '
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="" id="menu-<?= $menu->id ?>-detail">

        <?php
        echo $this->render('_detail_categories', [
            'menu' => $menu,
            'countMenuServices' => $countMenuServices,
            'id' => $menu->id,
            'categories' => $menu->categories,
            'title' => $menu->title,
        ]);
        ?>
    </div>

    <div class="row n_add_disclaimer menu_addit_params_<?= $menu->id ?> <?= $hideAllAdditional ?>">
        <div class="col-xs-10 col-xs-offset-1 ">
            <?php
//                echo Html::label('master ' . $menu->title . ' disclaimer ', null, ['class' => 'control-label']);
                $disclaimer = (!isset($menu->disclaimer) || $menu->disclaimer === '') ? null : $menu->disclaimer;
                if ($disclaimer === null) {
                    $addDisclaimerVisible = true;
                } else {
                    $addDisclaimerVisible = false;
                }
                echo Html::a('+ Add  ' . $menu->title . '  Disclaimer', null, ['class' => ($addDisclaimerVisible) ? 'btn-link' : 'btn-link hidden',
                    'id' => 'add_disclaimer_menu_' . $menu->id,
                    'onclick' => '
                                     menu_add_param_button('.$menu->id.',"disclaimer",this);
                            '
                ]);
                echo Html::textarea('disclaimer_menu_' . $menu->id, $disclaimer, [
                    'id' => 'disclaimer_menu_' . $menu->id,
                    'placeholder' => 'This field is not mandatory.',
                    'style' => 'resize:none',
                    'class' => (!$addDisclaimerVisible) ? 'form-control' : 'form-control hidden',
                    'base_value' => (!isset($menu->disclaimer) || $menu->disclaimer === '') ? '' : $menu->disclaimer,
                    'disabled' => true
                ]);
            ?>
        </div>
        <div class="col-xs-1 n_btn_div">
            <div class="row">
                <?php
                echo Html::a('Edit', null, ['class' => (!$addDisclaimerVisible) ? 'col-xs-12 btn-link' : 'col-xs-12 btn-link hidden',
                    'id' => 'edit_disclaimer_' . $menu->id,
                    'onclick' => ' editMenuParam(this,' . $menu->id . ',"disclaimer"); return false; '
                ]);
                ?>
            </div>
            <div class="row">
                <?=
                Html::a('Save', Url::to(['account/update-menu-param']), ['class' => 'btn btn-info col-xs-6 hidden',
                    'id' => 'save_disclaimer_menu_' . $menu->id,
                    'onClick' => ' saveMenuParam(this,' . $menu->id . ',"disclaimer"); return false; '
                ])
                ?>
            </div>
            <div class="row">
                <?=
                Html::a('Cancel', null, ['class' => 'btn btn-default col-xs-6 hidden',
                    'id' => 'cancel_disclaimer_menu_' . $menu->id,
                    'onClick' => 'cancelMenuParam(this,' . $menu->id . ',"disclaimer");return false;'
                ])
                ?>
            </div>
            <div class="row">
                <?php
                echo Html::a('Delete', Url::to(['account/update-menu-param']), ['class' => 'col-xs-12 btn-link  delete-link hidden',
                    'id' => 'delete_disclaimer_menu_' . $menu->id,
                    'onclick' => 'deleteMenuParam(this,' . $menu->id . ',"disclaimer");return false; '
                ]);
                ?>
            </div>
        </div>
    </div>

    <?php
        if ((count($menu->categories) == 1) && ($countMenuServices == 0)) {
            $hideEmptyState = "";
        } else {
            $hideEmptyState = "hidden";
        }
    ?>
    <div class="row  menu_empty_state_<?= $menu->id ?> <?= $hideEmptyState ?>">
        <div class="col-sm-offset-2 col-sm-9 row-nomenu">
            <div class="alert alert-info" role="alert">
                <p>Congrats, your section is created.</p>
                <p>Now you can add categories and services by clicking the appropriate “+ Add” button.</p>
                <p>Click the “Tip” button for helpful hints on creating your menu.</p>
            </div>
        </div>
    </div>
</div>