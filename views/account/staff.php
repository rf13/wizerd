<?php
/* @var $this yii\web\View */
/* @var $staff array|null */
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = 'Manage staff members';

?>
<div class="business-staff">
    <div class="form-group">
        <div class="btn-group">
            <?= Html::a('+ Add staff member',
                Url::to('/account/staff-new-add')
                ,[
                'class' => ' btn btn-warning',
                'onclick'=>'
                    $.ajax({
                        type: "POST",
                        url: $(this).attr("href"),
                        success: function(data) {
                            if(data){
                                $(".addStaffModal_body").html(data);
                                $("#addStaffModal").modal("show");
                            }
                        }
                    });
                    return false;
                '

            ]); ?>
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

            <p>Tip: Adding staff members</p>

            <p>
                Add profiles and photos for people who work at your business.
                Customers love to know who they will be working with.
                The “+ Add” button is in the top left.
            </p>

            <p>
                We encourage you to add photos, titles and profile bio’s as this significantly helps to turn “visitors” into “customers”!
                This also helps to rank your Wizerd site in popular search engines…meaning more customers.
            </p>

            <p>To crop or change your photo, select the link while in edit mode.</p>
            <div class="pull-right">
            </div>
        </div>
    </div>
    <div class="staff-manage">
        <div class="panel panel-success">
            <?php

            /*
            <div class="panel-heading">
                <?= $this->title ?>
            </div>
            */
            ?>
            <div class="panel-body">
                <div id="staff_manage_div">
                    <?= $this->renderAjax( 'staff/_manage', ['staff' => $staff]); ?>

                </div>
            </div>
        </div>
    </div>
</div>