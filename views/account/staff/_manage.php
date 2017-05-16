<?php
/* @var $this yii\web\View */
/* @var $staff array|null */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Staff';

$this->registerJsFile('/js/account_staff.js');

$sc = <<< JS
var editModalClose=false;
var addModalClose=false;
$('#delStaffModal').on('hidden.bs.modal', function (e) {
    if(!editModalClose){
        $("body").addClass("modal-open");
    }else{
        editModalClose=false;
    }
});
$('#cancelStaffModal_edit_crop').on('hidden.bs.modal', function (e) {
      if(!editModalClose){
        $("body").addClass("modal-open");
    }else{
        editModalClose=false;
        }
});
$('#cancelStaffModal_edit').on('hidden.bs.modal', function (e) {
    if(!editModalClose){
        $("body").addClass("modal-open");
    }else{
        editModalClose=false;
    }
});
$('#cancelStaffModal_add').on('hidden.bs.modal', function (e) {
    if(!addModalClose){
        $("body").addClass("modal-open");
    }else{
        addModalClose=false;
    }
});
$('#cancelStaffModal_add_crop').on('hidden.bs.modal', function (e) {
         $("body").addClass("modal-open");
});

JS;
$this->registerJs($sc);
?>




<?php if ($staff): ?>
    <?php foreach ($staff as $emp): ?>
        <div id="staff_<?= $emp->id ?>" class="form-group">
            <?= $this->renderAjax('_employee', ['emp' => $emp]); ?>
        </div>

    <?php endforeach; ?>
    <div id="new_staff"></div>
    <div class="add_staff_link">
        <?php
        /*echo  Html::a('+Add staff',Url::to('/account/staff-new-add'),
            [
            'class'=>'btn-link',
            'onclick'=>'
                $.ajax({
                         type: "POST",
                         url: $(this).attr("href"),
                          success: function(data) {
                                if(data){

                                $("#new_staff").html(data);
                                $(".no_staff").addClass("hidden");
                                }

                          }
                 });
                 return false;


            '
        ]);
 */
        ?>
    </div>
<?php else: ?>
    <div id="new_staff "></div>
    <div class="row ">
        <div class="col-sm-offset-2 col-sm-9 row-nomenu">
            <div class="alert alert-info" role="alert">
                <p>Right now you don’t have any employees.</p>
                <p>To get started click the “+ Add staff member” button.</p>
                <p>Click the “Tip” button for helpful hints on adding staff members.</p>
            </div>
        </div>
    </div>

<?php endif; ?>


<div class="modal fade " id="addStaffModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal_head_message staff_add_title">Staff add</span>
                <span class="modal_head_message staff_add_title_crop hidden">Staff crop</span>

                <?= Html::button('Cancel', [
                    'class' => "close staff_add_title",
                    'id' => 'btn_modal_close_add',
                    'onclick' => '
                        cancel_staff_modal_default("#addStaffModal","#cancelStaffModal_add");
                    '
                ]) ?>
                <?= Html::button('Cancel ', [
                    'class' => "close staff_add_title_crop hidden",
                    'id' => 'btn_crop_close_add',
                    'onclick' => '
                        cancel_staff_modal_default("#addStaffModal","#cancelStaffModal_add_crop");
                    '
                ]) ?>

            </div>
            <div class="modal-body addStaffModal_body">

            </div>
            <div class="row addStaffModal_crop hidden">

                <div class="">
                    <div class="col-xs-12">
                        <div id="for_canvas" style="min-height:250px" class="hidden">
                            <canvas id="new_staff_canvas" class=""></canvas>
                        </div>
                    </div>

                    <div class="btn-group n_button col-xs-12">
                        <?= Html::button('<span class="glyphicon glyphicon-arrow-left"></span>', [
                            'onclick' => 'obj.cropper("move", -10, 0);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-arrow-right"></span>', [
                            'onclick' => 'obj.cropper("move", 10, 0);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-arrow-up"></span>', [
                            'onclick' => 'obj.cropper("move", 0, -10);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-arrow-down"></span>', [
                            'onclick' => 'obj.cropper("move", 0, 10);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-zoom-in"></span>', [
                            'onclick' => 'obj.cropper("zoom", 0.1);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-zoom-out"></span>', [
                            'onclick' => 'obj.cropper("zoom", -0.1);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-repeat"></span>', [
                            'onclick' => 'obj.cropper("rotate", 90);'
                        ]) ?>
                    </div>

                    <div class="col-xs-12">
                        <?= Html::button("Save", [
                            'class' => 'btn btn-info col-xs-12',
                            'onclick' => '
                                staff_add_modal_save(o_ctx);
                            '
                        ]) ?>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>


<div class="modal fade " id="editStaffModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal_head_message staff_edit_title">Staff edit</span>
                <span class="modal_head_message staff_edit_title_crop hidden">Staff crop</span>

                <?= Html::button('Cancel', [
                    'class' => "close staff_edit_title",
                    //'id' => 'btn_modal_close_edit',
                    'onclick' => '
                         cancel_staff_modal_default("#editStaffModal","#cancelStaffModal_edit");
                    '
                ]) ?>
                <?= Html::button('Cancel', [
                    'class' => "close staff_edit_title_crop hidden",
                  //  'id' => 'btn_crop_close_edit',
                    'onclick' => '
                         cancel_staff_modal_default("#editStaffModal","#cancelStaffModal_edit_crop");
                    '

                ]) ?>
            </div>
            <div class="modal-body editStaffModal_body">

            </div>
            <div class="row editStaffModal_crop hidden">


                <div class="">
                    <div class="col-xs-12">
                        <div id="for_canvas_edit" style="min-height:250px" class="hidden">
                            <canvas id="edit_staff_canvas" class=""></canvas>
                        </div>
                    </div>

                    <div class="btn-group n_button col-xs-12">
                        <?= Html::button('<span class="glyphicon glyphicon-arrow-left"></span>', [
                            'onclick' => 'obj.cropper("move", -10, 0);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-arrow-right"></span>', [
                            'onclick' => 'obj.cropper("move", 10, 0);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-arrow-up"></span>', [
                            'onclick' => 'obj.cropper("move", 0, -10);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-arrow-down"></span>', [
                            'onclick' => 'obj.cropper("move", 0, 10);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-zoom-in"></span>', [
                            'onclick' => 'obj.cropper("zoom", 0.1);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-zoom-out"></span>', [
                            'onclick' => 'obj.cropper("zoom", -0.1);'
                        ]) ?>
                        <?= Html::button('<span class="glyphicon glyphicon-repeat"></span>', [
                            'onclick' => 'obj.cropper("rotate", 90);'
                        ]) ?>
                    </div>

                    <div class="col-xs-12">
                        <?= Html::button("Save", [
                            'class' => 'btn btn-info col-xs-12',
                            'onclick' => '
                                staff_edit_modal_save(o_e_ctx,input_id);
                            '
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bs-example-modal-sm" id="delStaffModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Staff delete</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Do you want to delete this staff member?</p>
                </div>
                <div class="row">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id' => 'btn_delStaffModal_close',
                        'onclick' => '
                            staff_cancel_delete();
                        '
                    ]); ?>
                    <?= Html::a('Confirm',
                        Url::toRoute(['account/staff-delete']),
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id' => 'btn_delStaffModal_confirm',
                            'del_id' => '',
                            'onclick' => '
                                staff_confirm_delete(this);
                                return false;
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" id="cancelStaffModal_edit_crop" tabindex="-1" role="dialog" data-keyboard="false"
     data-backdrop="static" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Staff crop cancel</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>
                        Are you sure you want to cancel. Your changes will not be saved?
                    </p>
                </div>
                <div class="row">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id' => 'btn_editStaffModal_crop_close',
                        'onclick' => '
                            staff_cancel_cancel_edit_crop();
                        '
                    ]); ?>
                    <?= Html::button('Confirm',
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id' => 'btn_editStaffModal_crop_confirm',
                            'del_id' => '',
                            'onclick' => '
                                staff_confirm_cancel_edit_crop(this);
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" id="cancelStaffModal_edit" tabindex="-1" role="dialog" data-keyboard="false"
     data-backdrop="static" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Staff cancel</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>
                        Are you sure you want to cancel. Your changes will not be saved?
                    </p>
                </div>
                <div class="row">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id' => 'btn_editStaffModal_cancel_close',
                        'onclick' => '
                            staff_cancel_cancel_edit();
                        '
                    ]); ?>
                    <?= Html::button('Confirm',
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id' => 'btn_editStaffModal_cancel_confirm',
                            'del_id' => '',
                            'onclick' => '
                                staff_confirm_cancel_edit(this);
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" id="cancelStaffModal_add" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Staff add cancel</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>
                        Are you sure you want to cancel. Your changes will not be saved?
                    </p>
                </div>
                <div class="row">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id' => 'btn_addStaffModal_cancel_close',
                        'onclick' => '
                            staff_cancel_cancel_add();
                        '
                    ]); ?>
                    <?= Html::button('Confirm',
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id' => 'btn_addStaffModal_cancel_confirm',
                            'onclick' => '
                                staff_confirm_cancel_add(this);
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" id="cancelStaffModal_add_crop" tabindex="-1" role="dialog" data-keyboard="false"
     data-backdrop="static" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content n_div_modal_content">
            <div class="modal-header">
                <span class="modal_head_message">Staff crop cancel</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>
                        Are you sure you want to cancel. Your changes will not be saved?
                    </p>
                </div>
                <div class="row">
                    <?= Html::button('Cancel', [
                        'class' => "btn btn-default col-md-5",
                        'id' => 'btn_addStaffModal_crop_close',
                        'onclick' => '
                            staff_cancel_cancel_add_crop();
                        '
                    ]); ?>
                    <?= Html::button('Confirm',
                        [
                            'class' => "btn btn-info col-md-5 col-md-offset-1",
                            'id' => 'btn_addStaffModal_crop_confirm',
                            'del_id' => '',
                            'onclick' => '
                                staff_confirm_cancel_add_crop(this);
                            '
                        ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
