<?php
/* @var $this yii\web\View */
/* @var $services array */
/* @var $category Cat */
/* @var $price int */
/* @var $time int */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CustomService;

$script = <<< JS
   for(i=0;i<forTierAddSaved.length;i++)
    {
       $("[name="+forTierAddSaved[i].name+"]").val(forTierAddSaved[i].value);
    }

$("[data-toggle='tooltip']").tooltip();
$("[data-toggle='popover']").popover();


JS;
$this->registerJs($script);

$chBoxTooltip = "Check this box if you to want to display the column title on your Wizerd menu. If left unchecked, it will not be shown.";

$count_srv = count($services);
if (($count_srv > 0)) {
    ?>

    <div class="row">
        <div class="col-xs-12">
            <table class="table  menu-detail-table">
                <thead>
                <tr>
                    <td  class="n_arrow"></td>
                    <td class=" n_service">
                        <div class="text-center">
                            <?= Html::checkbox('show_service', ($category->srv_title_vis == 1) ? true : false, [
                                'id' => 'srv_service_show_' . $category->id,
                                'class' => 'n_checkbox',
                                'disabled' => true,
                                'base_value' => ($category->srv_title_vis == 1) ? 1 : 0,
                            ]);?>
                            <?=  Html::tag('span', '<i class="glyphicon glyphicon-question-sign btn-sx "></i>', [
                                    'title'=>$chBoxTooltip,
                                    'data-toggle'=>'tooltip',
                                    'class'=>'chbox_info hidden srv_service_field_edit_' . $category->id,
                                ]);
                            ?>
                        </div>
                        <div class="n_service">
                            <?= Html::label($category->srv_title, null, [
                                'id' => 'l_srv_service_title_' . $category->id,
                                'class' => 'srv_service_field_edit_' . $category->id,
                            ]); ?>
                            <?= Html::input('text', null, $category->srv_title, [
                                'id' => 'srv_service_title_' . $category->id,
                                'class' => 'form-control hidden srv_service_field_edit_' . $category->id,
                                'base_value' => $category->srv_title,
                            ]);

                            ?>
                        </div>

                        <div class='srv_service_title_<?= $category->id?> with-error'></div>

                        <?php
                        echo Html::a('Edit', null, [
                            'class' => 'btn-xs col-xs-12 srv_service_field_edit_' . $category->id,
                            // 'id' => 'edit_srvPriceField_' . $category->id,
                            'onclick' => ' editSrvBaseField(this,' . $category->id . ',"service");return false;'
                        ]);
                        echo Html::a('Save', Url::to(['account/menu-srv-cat-field-save']), [
                            'class' => 'btn btn-info n_save hidden srv_service_field_edit_' . $category->id,
                            'id' => 'save_srv_service_field_' . $category->id,
                            'onClick' => 'saveSrvBaseField(this,' . $category->id . ',"service"); return false;'
                        ]);
                        echo Html::a('Cancel', null, [
                            'class' => 'btn btn-default n_save  hidden srv_service_field_edit_' . $category->id,
                            'id' => 'cancel_srvBaseField_' . $category->id,
                            'onClick' => 'cancelSrvBaseField(this,' . $category->id . ',"service"); return false;'
                        ]);
                        ?>
                    </td>
                    <td class="n_price">
                        <?php
                        // Price
                        ?>
                        <div>
                            <?= Html::checkbox('show_price', ($category->price_title_vis == 1) ? true : false, [
                                'id' => 'srv_price_show_' . $category->id,
                                'class' => 'n_checkbox',
                                'disabled' => true,
                                'base_value' => ($category->price_title_vis == 1) ? 1 : 0,
                            ]); ?>
                            <?=  Html::tag('span', '<i class="glyphicon glyphicon-question-sign btn-xs "></i>', [
                                'title'=>$chBoxTooltip,
                                'data-toggle'=>'tooltip',
                                'class'=>'chbox_info hidden srv_price_field_edit_' . $category->id,
                            ]);
                            ?>
                        </div>
                        <div>
                            <?= Html::label($category->price_title, null, [
                                'id' => 'l_srv_price_title_' . $category->id,
                                'class' => 'srv_price_field_edit_' . $category->id,
                            ]); ?>
                            <?= Html::input('text', null, $category->price_title, [
                                'id' => 'srv_price_title_' . $category->id,
                                'class' => 'form-control  hidden srv_price_field_edit_' . $category->id,
                                'base_value' => $category->price_title,

                            ]);
                            ?>
                        </div>
                        <div class='srv_price_title_<?= $category->id?> with-error'></div>
                        <?php
                        echo Html::a('Edit', null, [
                            'class' => 'btn-xs col-xs-12 srv_price_field_edit_' . $category->id,
                            // 'id' => 'edit_srvPriceField_' . $category->id,
                            'onclick' => ' editSrvBaseField(this,' . $category->id . ',"price");return false;'
                        ]);
                        echo Html::a('Save', Url::to(['account/menu-srv-cat-field-save']), [
                            'class' => 'btn btn-info hidden n_save srv_price_field_edit_' . $category->id,
                            'id' => 'save_srv_price_field_' . $category->id,
                            'onClick' => 'saveSrvBaseField(this,' . $category->id . ',"price"); return false;'
                        ]);
                        echo Html::a('Cancel', null, [
                            'class' => 'btn btn-default n_save hidden srv_price_field_edit_' . $category->id,
                            'id' => 'cancel_srvBaseField_' . $category->id,
                            'onClick' => 'cancelSrvBaseField(this,' . $category->id . ',"price"); return false;'
                        ]);
                        ?>
                    </td>

                    <?php
                    // Additional Fields
                    foreach ($category->srvFields as $field) {
                        ?>
                        <td class="random-field">
                            <div>
                                <?= Html::checkbox('srv-field-show_' . $field->id, ($field->visible == 1) ? true : false, [
                                    'id' => 'srv-field-show_' . $field->id,
                                    'class' => 'n_checkbox',
                                    'disabled' => true,
                                    'base_value' => ($field->visible == 1) ? 1 : 0,
                                ]); ?>
                                <?=  Html::tag('span', '<i class="glyphicon glyphicon-question-sign btn-xs "></i>', [
                                    'title'=>$chBoxTooltip,
                                    'data-toggle'=>'tooltip',
                                    'class'=>'chbox_info hidden srv-field-title_' . $field->id,
                                ]);
                                ?>
                            </div>
                            <div>
                                <?= Html::label($field->title, null, [
                                    'class' => 'srv-field-title_' . $field->id,
                                    'id' => 'l-srv-field-title_' . $field->id
                                ]); ?>
                                <?= Html::input('text', null, $field->title, [
                                    'id' => 'srv-field-title_' . $field->id,
                                    'class' => 'form-control hidden srv-field-title_' . $field->id,
                                    'base_value' => $field->title,
                                    'disabled' => true,
                                ]); ?>
                                <div id='srv-field-title-error-<?=  $field->id?>' class='with-error'></div>
                            </div>

                            <div>
                                <?php
                                echo Html::a('Edit', null, ['class' => 'btn-xs col-xs-12',
                                    'id' => 'edit_srvField_' . $field->id,
                                    'onclick' => ' editSrvField(this,' . $field->id . ');return false;'
                                ]);

                                echo Html::a('Save', Url::to(['account/menu-srv-field-save']), [
                                    'class' => 'btn btn-info n_save hidden srvFieldEdit_' . $field->id,
                                    'id' => 'save_srvField_' . $field->id,
                                    'onClick' => 'saveSrvField(this,' . $field->id . '); return false;'
                                ]);
                                echo Html::a('Cancel', null, ['class' => 'btn btn-default n_save hidden srvFieldEdit_' . $field->id,
                                    'id' => 'cancel_srvField_' . $field->id,
                                    'onClick' => 'cancelSrvField(this,' . $field->id . '); return false;'
                                ]);

                                echo Html::a('Delete', Url::to(['account/menu-srv-field-delete', "field_id" => $field->id]), [
                                    'class' => 'n_save  hidden delete-link srvFieldEdit_' . $field->id,
                                    'id' => 'delete_srvField_' . $field->id,
                                ]);
                                ?>
                                <div class="col-xs-12">
                                    <?php
                                    $up = ($field->getMinSort() != $field->sort) ? true : false;
                                    if ($up) {
                                        echo Html::a(
                                            '<i class="glyphicon glyphicon-chevron-left glyphicon-default btn-xs "></i>', Url::toRoute(['account/service-additional-field-sort', 'id' => $field->id, 'up' => true]), [
                                            //  'class' => 'col-xs-12',
                                            'onclick' => '
                                            service_additional_field_sort(this,' . $menu_id . ');
                                            return false;
                                        '
                                        ]);
                                    } else {
                                        echo '<i class="glyphicon glyphicon-chevron-left glyphicon-disabled btn-xs "></i>';
                                    }
                                    ?>
                                    <span class="position">Position</span>
                                    <?php
                                    $down = ($field->getMaxSort() != $field->sort) ? true : false;
                                    if ($down) {

                                        echo Html::a(
                                            '<i class="glyphicon glyphicon-chevron-right glyphicon-default btn-xs "></i>', Url::toRoute(['account/service-additional-field-sort', 'id' => $field->id, 'up' => false]), [
                                            'class' => '',
                                            'onclick' => '
                                            service_additional_field_sort(this,' . $menu_id . ');
                                            return false;
                                        '
                                        ]);
                                    } else {
                                        echo '<i class="glyphicon glyphicon-chevron-right glyphicon-disabled btn-xs "></i>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                        <?php
                    }
                    ?>
                    <td class="n_add_detail">
                        <?=
                        Html::a('+ Add detail', Url::to(['account/field-add']), [
                            'class' => 'n_add_a',
                            'id' => 'detail_add_link_' . $category->id,
                            'onclick' => '
                                detail_add_btn(this,' . $category->id . ');
                                return false;
                            '
                        ]);
                        ?>
                        <div id="new_field_<?= $category->id ?>"></div>
                    </td>
                    <?php
                    /*
                    ?>
                    <td class="n_check_box">
                        <p>Check box if you want to display the column title on your Wizerd menu</p>
                    </td>
                    */
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                //--------------- Table Body
                foreach ($services as $ind => $srv):
                    if (!$srv->isAllFilled()) {
                        $formActive = true;
                        $hide = '';
                        $show = 'hidden';
                    } else {
                        $formActive = false;
                        $hide = 'hidden';
                        $show = '';
                    }

                    $rowspan = count($srv->tiers);
                    $first = true;
                    foreach ($srv->tiers as $tier) {
                        ?>
                        <tr>

                            <?php
                            if ($first) {
                                ?>
                                <td class="n_arrow " rowspan="<?= $rowspan ?>">
                                    <?php $up_class = ($ind == 0) ? ' disabled' : ''; ?>
                                    <?=
                                    Html::a(
                                        '<i class="glyphicon glyphicon-chevron-up"></i>', Url::toRoute(['account/menu-srv-sort', 'id' => $srv['id'], 'up' => true, 'menu_id' => $menu_id,]), [
                                        'class' => 'btn btn-primary btn-xs' . $up_class,
                                        'onclick' => '
                                            service_sort(this,' . $category->id . ');
                                            return false;
                                        '
                                    ]);
                                    ?>
                                    <?php $down_class = ($ind == $count_srv - 1) ? ' disabled' : ''; ?>
                                    <?=
                                    Html::a(
                                        '<i class="glyphicon glyphicon-chevron-down"></i>', Url::toRoute(['account/menu-srv-sort', 'id' => $srv['id'], 'up' => false, 'menu_id' => $menu_id,]), [
                                        'class' => 'btn btn-primary btn-xs' . $down_class,
                                        'onclick' => '
                                            service_sort(this,' . $category->id . ');
                                            return false;
                                        '
                                    ]);
                                    ?>
                                </td>
                                <td class=" n_service" rowspan="<?= $rowspan ?>">
                                    <div>
                                    <?php
                                    //Service Name
                                    echo Html::input('text', 'title_pricing-' . $srv->id, $srv->title, [
                                        'class' => 'form-control mandatory service_m_' . $menu_id . ' title_service_' . $srv->id,
                                        'placeholder' => 'Service name',
                                        'base_value' => $srv->title,
                                        'id' => 'title_service_' . $srv->id,
                                        'disabled' => ($formActive) ? false : true
                                    ]);
                                    ?>
                                        </div>
                                </td>
                                <?php
                            }
                            ?>
                            <td class="n_price">
                                <div class="input-group n_price_td">
                                    <span class="input-group-addon">$</span>
                                    <?php
                                    // Price
                                    $value = null;
                                    if ($tier->price) {
                                        if ($tier->price - floor($tier->price) > 0)
                                            $value = $tier->price;
                                        else
                                            $value = floor($tier->price);
                                    }
                                    echo MaskedInput::widget([
                                        'name' => 'tier-price_' . $tier->id,
                                        'value' => $value,
                                        'clientOptions' => [
                                            'max' => '99999999.99',
                                            'alias' => 'currency',
                                            'prefix' => '',
                                            'placeholder' => '',
                                            'digitsOptional' => 1,
                                            'groupSeparator' => ',',
                                            'autoGroup' => true
                                        ],
                                        'options' => [
                                            'class' => 'form-control mandatory tier_' . $tier->id . ' price_service_' . $srv->id,
                                            'base_value' => ($tier->price) ? $tier->price : '',
                                            'id' => 'tier-price_' . $tier->id,
                                            'value' => $value,
                                            'disabled' => ($formActive) ? false : true,
                                        ]
                                    ]);
                                    ?>
                                </div>
                            </td>
                            <?php
                            foreach ($tier->getFieldsValueOrderedModels() as $fieldValue) {
                                // Additional Fields
                                ?>

                                <td class="random-field ">
                                    <?php
                                    echo Html::input('text', 'tier-field-val_' . $tier->id . '_' . $fieldValue->id, $fieldValue->value, [
                                        'class' => 'form-control tier_' . $tier->id . ' field_service_' . $srv->id,
                                        'base_value' => $fieldValue->value,
                                        'id' => 'tier-field-val_' . $fieldValue->id,
                                        'disabled' => ($formActive) ? false : true
                                    ]);
                                    ?>

                                </td>
                                <?php
                            }
                            ?>
                            <td width=" ">
                                <?php
                                if (!$first)
                                    echo Html::a('Delete tier', Url::to(['account/menu-service-tier-delete']), [
                                        'class' => 'delete-link btn-sm  tier_buttons_' . $srv->id . ' ' . $hide,
                                        'id' => 'delete_tier_' . $tier->id,
                                        'onClick' => 'deleteServiceTier(this,' . $tier->id . ',' . $category->id . ');return false; '
                                    ]);
                                ?>
                            </td>
                            <?php
                            if ($first) {
                                $first = false;
                                ?>
                                <td class="n_last_col" rowspan="<?= $rowspan ?>">
                                    <?php
                                    echo Html::a('Edit', null, [
                                        'class' => 'col-xs-12 btn-sm ' . $show,
                                        'id' => 'edit_service_' . $srv->id,
                                        'onclick' => '  editService(this,' . $srv->id . ')'
                                    ]);
                                    echo Html::a('Save', Url::toRoute(['account/menu-service-save', 'service_id' => $srv->id]), [
                                        'class' => 'btn btn-info btn-sm  col-xs-12  service_edits_' . $srv->id . ' ' . $hide,
                                        'id' => 'save_pricing_' . $srv->id,
                                        'onClick' => 'saveService(this,' . $srv->id . ');return false;'
                                    ]);
                                    echo Html::a('Cancel', Url::to(['account/menu-service-delete', "service_id" => $srv->id]), [
                                        'class' => 'btn btn-default btn-sm col-xs-12  service_edits_' . $srv->id . ' ' . $hide,
                                        'id' => 'cancel_pricing_' . $srv->id,
                                        'tiers' => Url::to(['account/menu-service-tiers-empty-delete']),
                                        'onClick' => 'cancelService(this,' . $srv->id . ',' . $category->id . ');return false;'
                                    ]);


                                    echo Html::a('Delete', Url::to(['account/menu-service-delete', "service_id" => $srv->id]), [
                                        'class' => 'col-xs-12 btn-sm delete-link service_edits_' . $srv->id . ' ' . $hide,
                                        'id' => 'delete_service_' . $srv->id,
                                        'onClick' => 'deleteService(this,' . $srv->id . ',' . $category->id . ',' . $menu_id . ');return false;'
                                    ]);
                                    ?>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr class="hidden" id="tr_errors_srv_<?=$srv->id?>">
                        <td></td>
                        <td colspan="<?php echo count($category->srvFields) + 3; ?>" class='with-error' id="td_errors_srv_<?=$srv->id?>">

                    </tr>
                    <tr class="n_add_tier  tier_buttons_<?php echo $srv->id . ' ' . $hide; ?>">
                        <td colspan="2"></td>

                        <td colspan="<?php echo count($category->srvFields) + 2; ?>">
                            <?=
                            Html::a('+ Add tier', Url::to(['account/menu-service-tier-add']), [
                                'class' => '',
                                'id' => 'tier_add_link_' . $srv->id,
                                'onClick' => 'addServiceTier(this,' . $srv->id . ',' . $category->id . ');return false; '
                            ]);
                            ?>
                        </td>
                    </tr>
                    <?php
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>

<?php if ((($category->is_menu_cat == 1) && (count($category->services) == 0)) || (($category->is_menu_cat == 0) && ($category->title == ''))) {
    $addSrvHide = 'hidden';
} else {
    $addSrvHide = '';
}
?>
<div class="form-group col-xs-offset-1 row-base-tb cat_params_<?= $category->id ?> <?= $addSrvHide ?>">
    <?php
    echo Html::a('+ Add service', Url::to(['account/menu-service-add']), [
        'class' => ' btn-link ',
        // 'id' => 'delete_description_cat_' . $cat->id,
        'onclick' => '
        service_add_btn(this,' . $category->id . ');

            return false;
        '
    ]);
    ?>
</div>
