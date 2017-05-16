function photo_delete_add_photo(photo_id) {
    $("#btn_delPhotoModal_confirm").attr("del_id", photo_id);
    $("#delPhotoModal").modal("show");
    $("#addPhotoModal").css({
        "z-index": "1000"
    });

}

function photo_crop_add_photo(link) {
    $(".add_photo_title").addClass("hidden");
    $(".add_photo_title_crop").removeClass("hidden");
    $(".photos_part").addClass("hidden");
    $(".crop_part").removeClass("hidden");

    $.ajax({
        type: "GET",
        url: link.href,
        success: function (response) {
            $(".crop_part").html(response);
        }
    });
}


function photo_confirm_delete_add_photo(link) {
    attr_id = $(link).attr("del_id");
    $("#photo_" + attr_id).attr("del_val", 1);
    $.ajax({
        type: "POST",
        data: {"id": attr_id},
        url: link.href,
        success: function (success) {
            if (success) {
                $("#photo_" + attr_id).addClass("hidden");
                $("#delPhotoModal").modal("hide");
                $("#addPhotoModal").css({
                    "z-index": "1050"
                });
                c = $(".unsaved_photo[del_val=0]")
                if (c.length == 0) {
                    $("#addPhotoModal").modal("hide");
                    $("body").removeClass("modal-open");
                }else if(c.length==1){
                    $("#addPhotoModalDialog").removeClass("modal-md").addClass("modal-sm");
                    $(".unsaved_photo[del_val=0]").removeClass("col-md-6").addClass("col-md-12");
                }

            }
        }
    });
}

function photo_confirm_delete_edit_photo(link) {
    f_edit_modal_close = true;
    attr_id = $(link).attr("del_id");
    $("#photo_" + attr_id).attr("del_val", 1);
    $.ajax({
        type: "POST",
        data: {"id": attr_id},
        url: link.href,
        success: function (success) {
            if (success) {
                $("#editPhotoModal").removeAttr("style");
                $("#delPhotoModal_edit").modal("hide");

                $("#photo_" + attr_id).addClass("hidden");

                $("#editPhotoModal").modal("hide");
                $("body").removeClass("modal-open");
            }
        }
    });
}

function photo_crop_draw(model_id) {
    var c = document.getElementById("canvas_" + model_id);
    img = document.getElementById("big_img_" + model_id);
    canvasObj = $("#canvas_" + model_id);
    canvasObj.removeClass("hidden");
    $("#img_" + model_id).addClass("hidden");
    // $(".editPhotoModal_crop .n_img").addClass("hidden");
    canvas = $("#canvas_" + model_id).get(0);
    ctx = canvas.getContext("2d");

    if (crop[0] < 0) crop[0] = 0;
    if (crop[1] < 0) crop[1] = 0;
    if (crop[2] > img.width) crop[2] = img.width;
    if (crop[3] > img.height) crop[3] = img.height;

    delta = 1;

    if ((canvas.height - crop[3] < 0) || (canvas.width - crop[2] < 0)) {
        if (crop[3] / canvas.height > crop[2] / canvas.width) {
            delta = crop[3] / canvas.height;
        }
        else {
            delta = crop[2] / canvas.width;
        }
        canvas.width = crop[2] / delta;
        canvas.height = crop[3] / delta;
    }
    else if ((canvas.height - crop[3] >= 0) || (canvas.width - crop[2] >= 0)) {
        canvas.height = crop[3];
        canvas.width = crop[2];
    }

    ctx.rotate(crop[4] * Math.PI / 180);
    if (crop[4] == 0) {
        x = crop[0];
        y = crop[1];
        w = crop[2];
        h = crop[3];
        x_canvas = 0;
        y_canvas = 0;
        w_canvas = crop[2] / delta;
        h_canvas = crop[3] / delta;
    }
    else if (crop[4] == 90) {
        x = crop[1];
        y = img.height - crop[0] - crop[2];
        w = crop[3];
        h = crop[2];
        x_canvas = 0;
        y_canvas = -crop[2] / delta;
        w_canvas = crop[3] / delta;
        h_canvas = crop[2] / delta;
    }
    else if (crop[4] == 180) {
        x = img.width - crop[2] - crop[0];
        y = img.height - crop[3] - crop[1];
        w = crop[2];
        h = crop[3];
        x_canvas = -crop[2] / delta;
        y_canvas = -crop[3] / delta;
        w_canvas = crop[2] / delta;
        h_canvas = crop[3] / delta;
    }
    else if (crop[4] == 270) {
        x = img.width - crop[1] - crop[3];
        y = crop[0];
        w = crop[3];
        h = crop[2];
        x_canvas = -crop[3] / delta;
        y_canvas = 0;
        w_canvas = crop[3] / delta;
        h_canvas = crop[2] / delta;
    }
    ctx.drawImage(img, x, y, w, h, x_canvas, y_canvas, w_canvas, h_canvas);

    saved_draw_params = [x, y, w, h, x_canvas, y_canvas, crop[2] / delta, crop[3] / delta];
}


function start_photo_edit(link, photo_id) {
    $.ajax({
        type: "POST",
        url: link.href,
        data: {"id": photo_id},
        success: function (success) {
            if (success) {
                $(".editPhotoModal_body").html(success);
                $("#editPhotoModal").modal("show");
            }
        }
    });
}

function group_photo_save(link) {
    photos = $(".photo_params").serializeArray();
    $.ajax({
        type: "POST",
        url: link.href,
        data: {"photos": photos},
    });
}


function confirm_modal_cancel_default(modal_id, parent_id) {
    $(modal_id).modal("hide");
    $(parent_id).css({
        "z-index": "1050"
    });
}
function photo_first_modal_show_cancel(modal_id, parent_id) {
    $(modal_id).modal("show");
    $(parent_id).css({
        "z-index": "1000"
    });
}
function cancel_photo_modal_confirm(link) {
    nohideModalByCancel = false;
    $("#cancelPhotoModal").modal("hide");
    $("#addPhotoModal").modal("hide");
    $.ajax({
        type: "GET",
        url: link.href,
    });
}

function cancel_photo_modal_crop_confirm() {
    confirm_modal_cancel_default("#cancelPhotoModal_crop", "#addPhotoModal");
    add_photo_crop_close();
}
function cancel_photo_modal_edit_crop_confirm() {
    confirm_modal_cancel_default("#cancelPhotoModal_edit_crop", "#editPhotoModal");
    edit_photo_crop_close();
}
function prof_confirm_modal_confirm(link) {
    $("#editPhotoModal").modal("hide");
    p_id = $(link).attr("prof_id");
    $(".modal-backdrop").remove();
    $("body").removeClass("modal-open");
    $.ajax({
        type: "POST",
        data: {"id": p_id},
        url: link.href,
        success: function (response) {
            $("#photo_manage").html(response);
        }
    });
}
function cancel_photo_modal_edit_confirm() {
    $("#cancelPhotoModal_edit").modal("hide");
    $("#editPhotoModal").modal("hide");
    $("#editPhotoModal").removeAttr("style");
}

function one_photo_add_crop_save(model_id) {
   // $("#crop_close").addClass("hidden");
    //$("#modal_close").removeClass("hidden");

    $(".add_photo_for_modal").removeClass("hidden");
    $(".add_photo_for_crop").addClass("hidden");

    photo_crop_draw(model_id);

    $(".crop_part").html("");
    $(".crop_part").addClass("hidden");
    $(".photos_part").removeClass("hidden");
}
function edit_photo_crop_close() {
    $(".editPhotoModal_crop").html("");
    $(".editPhotoModal_body").removeClass("hidden");
    $(".editPhotoModal_crop").addClass("hidden");

    //$("#btn_modal_close_edit").removeClass("hidden");
    //$("#btn_model_close_crop").addClass("hidden");
    $(".edit_photo_for_modal").removeClass("hidden");
    $(".edit_photo_for_crop").addClass("hidden");
}
function crop_edit_photo_start(link) {
  //  $("#editPhotoModal .modal-dialog").removeClass("modal-sm");
    $(".edit_photo_for_modal").addClass("hidden");
    $(".edit_photo_for_crop").removeClass("hidden");
    //$("#btn_modal_close_edit").addClass("hidden");
    //$("#btn_model_close_crop").removeClass("hidden");

    $(".editPhotoModal_body").addClass("hidden");
    $(".editPhotoModal_crop").removeClass("hidden");

    $.ajax({
        type: "GET",
        url: link.href,
        success: function (response) {
            $(".editPhotoModal_crop").html(response);
        }
    });
}
function start_modal_mark_as_profile_photo(photo_id) {
    $("#profile_btn_model_confirm").attr("prof_id", photo_id);
    $("#profConfirmModal").modal("show");
    $("#editPhotoModal").css({
        "z-index": "1000"
    });
}
function start_modal_delete_photo(photo_id) {
    $("#btn_delPhotoModal_confirm_edit").attr("del_id", photo_id);
    $("#editPhotoModal").css({
        "z-index": "1000"
    });
    $("#delPhotoModal_edit").modal("show");
}
function save_edit_photo(link) {
    photos = $(".photo_params").serializeArray();
    $.ajax({
        type: "POST",
        url: link.href,
        data: {"photos": photos},
        success: function (success) {
            $("#photo_manage").html(success);
        }
    });
}
function one_photo_edit_crop_save(model_id) {
    $("#editPhotoModal .modal-dialog").addClass("modal-sm");
    //$("#btn_modal_close_edit").removeClass("hidden");
    //$("#btn_model_close_crop").addClass("hidden");
    $(".edit_photo_for_modal").removeClass("hidden");
    $(".edit_photo_for_crop").addClass("hidden");
    photo_crop_draw(model_id);

    $(".editPhotoModal_crop").html("");
    $(".editPhotoModal_crop").addClass("hidden");
    $(".editPhotoModal_body").removeClass("hidden");
}
function photo_add_photo_click() {
    $("#new_photo_wid").click();
    $("#new_profile_photo_wid").click();
}
function open_photos(link) {
    $.get(link.href).done(function (data) {
        $("#photo_manage").html(data);
        //add Nedogarko
        $(".n_a_profile").removeClass("active");
        $(".n_a_photo").addClass("active");
    });
}
function open_profile_photo(link) {
    $.get(link.href).done(function (data) {
        $("#photo_manage").html(data);
        //add Nedogarko
        $(".n_a_photo").removeClass("active");
        $(".n_a_profile").addClass("active");
    });
}

function edit_profile_photo(link, photo_id) {
    $.ajax({
        type: "POST",
        url: link.href,
        data: {"id": photo_id},
        success: function (success) {
            if (success) {
                $("#for_image").html(success);
                makeCroper("original_image");
                $(".photo_btns_" + photo_id).toggleClass("hidden");
            }
        }
    });
}
function save_profile_photo(link, photo_id) {
    $.ajax({
        type: "POST",
        url: link.href,
        data: {
            "id": photo_id,
            "crop": cropStr
        },
        success: function (success) {
            if (success) {
                $("#photo_manage").html(success);
            }
        }
    });
}

function cancel_profile_photo_edit(link) {
    $("#cancelProfilePhotoModal").modal("hide");
    $(".modal-backdrop").remove();
    $("body").removeClass("modal-open");
    $.ajax({
        type: "POST",
        url: link.href,
        data: {"id": $(link).attr("photo_id")},
        success: function (success) {
            if (success) {
                $("#photo_manage").html(success);
            }
        }
    });
    $(".photo_btns_" + $(link).attr("photo_id")).toggleClass("hidden");
}