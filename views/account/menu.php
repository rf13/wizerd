<?php
/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $menus array of app\models\Menu */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;

$this->title = 'Menu';

?>

<div class="business_menu">
    <div class="form-group">
        <div class="btn-group">
            <?=
            Html::button('+ Add section', [
                    'value' => Url::to(['account/menu-add']),
                    'title' => 'Add section',
                    'id' => 'addMenu',
                    'class' => 'showModalButton btn btn-warning n_add'
            ]);
            ?>
        </div>
        <div class="btn-group">
            <?=
            Html::button('+ Add category', [
                    'value' => Url::to(['account/menu-category-add-modal']),
                    'title' => 'Add category',
                    'id' => 'addCustomCategory',
                    'class' => 'showModalButton btn btn-warning n_add'
            ]);
            ?>
        </div>
        <div class="btn-group">
            <?=
            Html::button('+ Add service', [
                'value' => Url::to(['account/menu-service-add-modal']),
                'title' => 'Add service',
                'id' => 'addCustomService',
                'class' => 'showModalButton btn btn-warning n_add'
            ]);
            ?>
        </div>

        <div class="btn-group pull-right">
            <?= Html::button('Tip', ['class' => 'btn btn-default btn-tip-show',
                'onclick' => 'for_setup_tip()'
            ]); ?>
            <?= Html::button('Hide tip', ['class' => 'btn btn-default btn-tip-hide hidden',
                'onclick' => 'for_setup_tip()'

            ]); ?>
        </div>
    </div>
    <div class="panel panel-default panel-tip">
        <div class="panel-body">
            <p>Tip: Building a menu</p>

            <p>
                A menu is made up of 3 parts.
                A “section” is the top tier; a “category” is the middle tier; a “service” is the bottom tier.
                You can have as many sections, categories and services that you want.
                “+ Add” buttons are located at the top left and throughout the body of the menu.
            </p>

            <p>
                Custom details (description, time, etc) and pricing tiers can also be added to services.
                “+ Add” buttons are located in the body next to the service.
            </p>

            <p>
                Each section and category can have it’s own description and disclaimer explaining more about the tier.
                “+ Add” buttons are located in the body at the top and bottom of the tier.
            </p>

            <p>
                The menu system is fully customizable to meet your needs.<br />
                Play around and get creative!
            </p>

            <p>Here are some basic examples:</p>

            <p>
                Haircut (section)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;Female (category)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cut (service), $<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Color (service), $<br />
                &nbsp;&nbsp;&nbsp;&nbsp;Male (category)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cut (service), $<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Shave (service), $
            </p>

            <p>
                Massage (section)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;Deep Tissue (category)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;60 minutes (service), $, description (custom detail)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;90 minutes (service), $, description (custom detail)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;Swedish (category)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;60 minutes (service), $, description (custom detail)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;90 minutes (service), $, description (custom detail)
            </p>

            <p>
                Facial (section)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;Salt Scrub (service), $<br />
                &nbsp;&nbsp;&nbsp;&nbsp;Mud (service), $<br />
                &nbsp;&nbsp;&nbsp;&nbsp;Lemon Peel (service), $, time (custom detail)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(pricing tier) $, time (custom detail)<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(pricing tier) $, time (custom detail)
            </p>
            <div class="pull-right">
            </div>
        </div>
    </div>

    <?php
    $count = count($menus);
    if ($count):

        $items = [];
        foreach ($menus as $menu) {
            $active = false;
            if (!Yii::$app->session->has('current-menu'))
                Yii::$app->session->set('current-menu', $menu->id);
            else {
                if ($menu->id == Yii::$app->session->get('current-menu'))
                    $active = true;
            }
            $model->id = $menu->id;
            if (!empty($menu->description))
                $model->description = $menu->description;
            if (!empty($menu->disclaimer))
                $model->disclaimer = $menu->disclaimer;
            $items[] = [

                    'encode' => false,
                    'label' => ($menu->title) ? $menu->title : 'Nonamed Menu',
                    'content' => $this->render('menu/_detail', [
                            'menu' => $menu, 'model' => $model, 'tabs' => true
                    ]),
                    //'active' => $menu->main,
                    'active' => $active,
                    'linkOptions' => [
                            'id' => 'menu_link_' . $menu->id,
                            'class' => 'n_a_photo',
                            'onClick' => 'new function (){ 
                                $.ajax({
                                        type     :"POST",
                                        cache    : false,
                                        data     :{"menu": ' . $menu->id . '},
                                        url  : "' . Url::to(['account/set-current-menu']) . '",
                                        success  : function(response) {
                                        }
                                });}'],
                    'headerOptions' => [ 'id' => 'menu_li_' . $menu->id, ],

            ];
        }

        echo Tabs::widget([
                'id' => 'menu_tabs',
                'items' => $items,
        ]);
    ?>
    <?php else: ?>
        <div class="row ">
            <div class="col-sm-offset-2 col-sm-9 row-nomenu">
                <div class="alert alert-info" role="alert">
                    <p>Right now you don’t have a menu created.</p>
                    <p>To get started click the “+ Add section” button.</p>
                    <p>Click the “Tip” button for helpful hints on creating your menu.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade bs-example-modal-sm" id="deleteCategoryModal" tabindex="-1" role="dialog" data-backdrop="static"  data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Category delete</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Delete category and ALL data within the category?</p>
                </div>
                <div class="row">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id'=>'btn_delCategoryModal_close',
                        'onclick'=>'
                            $("#deleteCategoryModal").modal("hide");
                        '
                    ]); ?>
                    <?= Html::a('Confirm',
                        Url::to(['account/delete-category']),
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id'=>'btn_delCategoryModal_confirm',
                            'del_id'=>'',
                            'onclick'=>'
                               category_delete_confirm(this);
                                return false;
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bs-example-modal-sm" id="deleteServiceModal" tabindex="-1" role="dialog" data-backdrop="static"  data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Category delete</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>
                        Delete Service  and all service tiers?"
                    </p>
                    <p class=" del_last_tier hidden">

                    </p>
                </div>
                <div class="row">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id'=>'btn_delCategoryModal_close',
                        'onclick'=>'
                            $("#deleteCategoryModal").modal("hide");
                        '
                    ]); ?>
                    <?= Html::a('Confirm',
                        Url::to(['account/delete-category']),
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id'=>'btn_delCategoryModal_confirm',
                            'del_id'=>'',
                            'onclick'=>'
                               category_delete_confirm(this);
                                return false;
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>