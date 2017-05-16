function handleFiles(files, origin_canvas, current_canvas, for_canvas_id) {
    if (files.length > 0) {
        var file = files[0];
        if (typeof FileReader !== "undefined" && file.type.indexOf("image") != -1) {
            var reader = new FileReader();
            // Note: addEventListener doesn't work in Google Chrome for this event
            reader.onload = function (evt) {
                load(evt.target.result, origin_canvas, current_canvas, for_canvas_id);
            };
            reader.readAsDataURL(file);
        }
    }
}

function load(src, origin_canvas, current_canvas, for_canvas_id) {
    img = new Image();
    img.onload = function () {
        addCropper(origin_canvas, current_canvas, for_canvas_id);
    };
    img.src = src;
}


function addCropper(origin_canvas, current_canvas, for_canvas_id) {
    origin_canvas.width = current_canvas.width = $(for_canvas_id).width();
    origin_canvas.height = current_canvas.height = $(for_canvas_id).height();
    delta = 1;


    if ((img.height > current_canvas.height) || (img.width > current_canvas.width)) {
        if (img.height / current_canvas.height >= img.width / current_canvas.width) {
            delta = (img.height / current_canvas.height);
        }
        if (img.height / current_canvas.height < img.width / current_canvas.width) {
            delta = (img.width / current_canvas.width);
        }
        current_canvas.width = img.width / delta;
        current_canvas.height = img.height / delta;
    }
    else {
        origin_canvas.width = current_canvas.width = img.width;
        origin_canvas.height = current_canvas.height = img.height;
    }

    ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, current_canvas.width, current_canvas.height);
    obj.cropper('destroy');
    obj.cropper({
        viewMode: 1,
        aspectRatio: 1,
        crop: function (e) {
            crop = [e.x * delta, e.y * delta, e.width * delta, e.height * delta, e.rotate];


        }
    })

}

function one_staff_delete(model_id) {
    $("#editStaffModal").css({
        "z-index": "1000"
    });
    $("#btn_delStaffModal_confirm").attr("del_id", model_id);
    $("#delStaffModal").modal("show");
}

function staff_cancel_delete() {
    $("#delStaffModal").modal("hide");
    $("#editStaffModal").css({
        "z-index": "1050"
    });
}
function staff_confirm_delete(link) {
    attr_id = $(link).attr("del_id");
    editModalClose = true;

    $("#staff_" + attr_id).attr("del_val", 1);
    $.ajax({
        type: "POST",
        url: link.href,
        data: {"id": attr_id},
        success: function (success) {
            if (success) {
                $("#staff_" + attr_id).addClass("hidden");
                $("#delStaffModal").modal("hide");
                $("#editStaffModal").modal("hide");
                $("#editStaffModal").removeAttr("style");
            }
        }
    }).done(function(e){
        leftStaff=$(".staff_member[del_val!=1]");
        if (leftStaff.length==0)
            $("#no_staff_div").removeClass("hidden");
        });
}

function staff_cancel_cancel_edit_crop() {
    $("#cancelStaffModal_edit_crop").modal("hide");
    $("#editStaffModal").css({
        "z-index": "1050"
    });
}
function staff_confirm_cancel_edit_crop() {
    if (input_id != 'edit_staff_photo') {
        editModalClose = true;
        $("#editStaffModal").modal("hide");
    }
    $("#cancelStaffModal_edit_crop").modal("hide");
    $("#editStaffModal").css({
        "z-index": "1050"
    });
    if (saved_img) {
        saved_ctx.drawImage(saved_img, saved_draw_params[0], saved_draw_params[1], saved_draw_params[2], saved_draw_params[3], saved_draw_params[4], saved_draw_params[5], saved_draw_params[6], saved_draw_params[7]);
    }
    $(".editStaffModal_body").removeClass("hidden");
    $(".editStaffModal_crop").addClass("hidden");

    //$("#btn_modal_close_edit").removeClass("hidden");
    //$("#btn_crop_close_edit").addClass("hidden");
    $(".staff_edit_title").removeClass("hidden");
    $(".staff_edit_title_crop").addClass("hidden");


}

function staff_cancel_cancel_edit() {
    $("#cancelStaffModal_edit").modal("hide");
    $("#editStaffModal").css({
        "z-index": "1050"
    });
}
function staff_confirm_cancel_edit() {
    editModalClose = true;
    $("#cancelStaffModal_edit").modal("hide");
    $("#editStaffModal").css({
        "z-index": "1050"
    });
    $("#editStaffModal").removeAttr("style");
    $("#editStaffModal").modal("hide");

}

function staff_cancel_cancel_add() {
    $("#cancelStaffModal_add").modal("hide");
    $("#addStaffModal").css({
        "z-index": "1050"
    });
}
function staff_confirm_cancel_add() {
    newImage = null;
    addModalClose = true;
    $(f).remove();
    f=null;
    $("#cancelStaffModal_add").modal("hide");
    $("#addStaffModal").css({
        "z-index": "1050"
    });
    $("#addStaffModal").removeAttr("style");
    $("#addStaffModal").modal("hide");

}

function staff_cancel_cancel_add_crop() {
    $("#cancelStaffModal_add_crop").modal("hide");
    $("#addStaffModal").css({
        "z-index": "1050"
    });
}
function staff_confirm_cancel_add_crop() {
    newImage = saved_img;
    //  console.log(saved_img);
    $("#cancelStaffModal_add_crop").modal("hide");
    $("#addStaffModal").css({
        "z-index": "1050"
    });

    //$("#btn_modal_close_add").removeClass("hidden");
    //$("#btn_crop_close_add").addClass("hidden");
    $(".staff_add_title").removeClass("hidden");
    $(".staff_add_title_crop").addClass("hidden");

    $(".addStaffModal_body").removeClass("hidden");
    $(".addStaffModal_crop").addClass("hidden");


    if (newImage) {
        saved_ctx.rotate(saved_crop[4] * Math.PI / 180);
        saved_ctx.drawImage(saved_img, saved_draw_params[0], saved_draw_params[1], saved_draw_params[2], saved_draw_params[3], saved_draw_params[4], saved_draw_params[5], saved_draw_params[6], saved_draw_params[7]);
    }
    else {
    //    console.log(newImage);
        $("#new_staff_photo").val("");
        $(f).remove();
        f=null;
    }
    //console.log('f1 save='+file_1);
    //console.log('f2 save='+file_2);
}
function staff_add_modal_save(ctx_current) {

    staff_modal_save(ctx_current);

    //$("#btn_modal_close_add").removeClass("hidden");
    //$("#btn_crop_close_add").addClass("hidden");
    $(".staff_add_title").removeClass("hidden");
    $(".staff_add_title_crop").addClass("hidden");

    $(".addStaffModal_body").removeClass("hidden");
    $(".addStaffModal_crop").addClass("hidden");

if(recrop==0) {
    if (file_edit == 1) {
        file_1 = 1;
        file_2 = 0;
        file_edit = 2;
    }
    else {
        file_1 = 0;
        file_2 = 1;
        file_edit = 1;
    }
}
    recrop=0;
   //console.log('f1 save='+file_1);
   // console.log('f2 save='+file_2);

   // f = $("#new_staff_photo").clone().attr("id", "to_load");
    $("#o_for_canvas").removeClass("hidden");
    $(".staff-add-avatar").addClass("hidden");
}

function staff_edit_modal_save(ctx_current, input_id) {
    staff_modal_save(ctx_current);
    f = $("#" + input_id).clone().attr("id", "to_load");

    $("#o_for_canvas").removeClass("hidden");
    $(".staff-edit-avatar").addClass("hidden");
    nophoto = 2;
    //$("#btn_modal_close_edit").removeClass("hidden");
    //$("#btn_crop_close_edit").addClass("hidden");

    $(".staff_edit_title").removeClass("hidden");
    $(".staff_edit_title_crop").addClass("hidden");

    $(".editStaffModal_body").removeClass("hidden");
    $(".editStaffModal_crop").addClass("hidden");
}


function staff_modal_save(ctx_current) {
    ctx_current.rotate(crop[4] * Math.PI / 180);

    if (crop[4] == 0) {
        x = crop[0];
        y = crop[1];
        w = crop[2];
        h = crop[3];
        x_canvas = 0;
        y_canvas = 0;
    }
    else if (crop[4] == 90) {
        x = crop[1];
        y = img.height - crop[0] - crop[3];
        w = crop[3];
        h = crop[2];
        x_canvas = 0;
        y_canvas = -crop[3] / delta;
    }
    else if (crop[4] == 180) {
        x = img.width - crop[2] - crop[0];
        y = img.height - crop[3] - crop[1];
        w = crop[2];
        h = crop[3];
        x_canvas = -crop[2] / delta;
        y_canvas = -crop[3] / delta;
    }
    else if (crop[4] == 270) {
        x = img.width - crop[1] - crop[3];
        y = crop[0];
        w = crop[3];
        h = crop[2];
        x_canvas = -crop[2] / delta;
        y_canvas = 0;
    }

    ctx_current.drawImage(img, x, y, w, h, x_canvas, y_canvas, crop[2] / delta, crop[3] / delta);

    $("#crop_params").val(crop.toString());
    saved_img = img;
    saved_crop = crop;
    saved_delta = delta;
    saved_ctx = ctx_current;
    saved_draw_params = [x, y, w, h, x_canvas, y_canvas, crop[2] / delta, crop[3] / delta];
}
function cancel_staff_modal_default(modal_id, parent_id) {

    $(modal_id).css({
        "z-index": "1000"
    });
    $(parent_id).modal("show");
}

function staff_wid(link, emp_id) {
    $.ajax({
        type: "POST",
        url: link,
        data: {
            "id": emp_id,
            "file_input_id": "inp_field_photo_" + emp_id
        },
        success: function (success) {
            if (success) {
                $(".editStaffModal_body").html(success);
                $("#editStaffModal").modal("show");
            }
        }
    });
}
function start_staff_edit(link, emp_id) {
    $.ajax({
        type: "POST",
        url: link.href,
        data: {"id": emp_id},
        success: function (success) {
            if (success) {
                $(".editStaffModal_body").html(success);
                $("#editStaffModal").modal("show");
            }
        }
    });

}