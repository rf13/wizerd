$('.user-account')
    .on('click', '.showModalButton', function (e) {
        var modal = $('#formModal');
        modal.find('#modalHeaderTitle').html('<p>' + $(this).attr('title') + '</p>');
        modal.find('#modalContent').load($(this).attr('value'));
        if (!modal.data('bs.modal').isShown) {
            modal.modal('show');
        }
    })
    .on('click', '.showModalButtonLg', function (e) {
        var modal = $('#formModalLg');
        modal.find('#modalHeaderTitleLg').html('<p>' + $(this).attr('title') + '</p>');
        modal.find('#modalContentLg').load($(this).attr('value'));
        if (!modal.data('bs.modal').isShown) {
            modal.modal('show');
        }
    })
    .on('click', 'ul.nav li', function (e) {
        var url = $(this).data('url'),
            content = "#" + $(this).data('content');
        if (!$(content).html()) {
            $.get(url)
                .done(function (data) {
                    $(content).html(data);
                });
        }
    }).on('click', '.btn-tip-show', function (e) {
        $('.panel-tip').show();
    }).on('click', '.btn-tip-hide', function (e) {
        $('.panel-tip').hide();
    });
$('.user-account ul.nav li.active').trigger('click');

function onlynums(input) {
    input.value = input.value.replace(/[^\d.,]/g, '');
}

// Menu
function menu_title_save(menu_id,link){
    title=$("#menu_name_"+menu_id).val();
    if ((canSaveNonamed==0)||(title.length>0)){
        $.ajax({
            type     :"POST",
            cache    : false,
            data     :{
                "menu_id": menu_id,
                "type": "title",
                "value": title
            },
            url: link.href
        });
        $("#menu_name_"+menu_id).attr("base_value", $("#menu_name_"+menu_id).val());
        $(".menu_edit_lnk_"+menu_id).toggleClass("hidden");
        $("#edit-menu-"+menu_id).toggleClass("hidden");
    }
}
function menu_title_cancel(menu_id){
    $("#menu_name_"+menu_id).val( $("#menu_name_"+menu_id).attr("base_value"));
    $(".menu_edit_lnk_"+menu_id).toggleClass("hidden");
    $("#edit-menu-"+menu_id).toggleClass("hidden");
}

function menu_delete(menu_id,link){
    var mess = 'Are you sure you want to delete the entire section? \nThis will also delete ALL the data in the section.';
    if (confirm(mess) ) {
        $.ajax({
            type     : "POST",
            cache    : false,
            data     : {"menu_id": menu_id },
            url      : link.href
        });
    };
}
function menu_change_sort(link){
    $.ajax({
        type: "POST",
        url: link.href,
    });
}
function menu_add_param_button(menu_id,type,link){
    $("#"+type+"_menu_"+menu_id).toggleClass("hidden");
    $("#"+type+"_menu_"+menu_id+"_label").toggleClass("hidden");
    $("#"+type+"_menu_"+menu_id).prop("disabled",false);
    $(link).toggleClass("hidden");
    $("#save_"+type+"_menu_"+menu_id).toggleClass("hidden");
    $("#cancel_"+type+"_menu_"+menu_id).toggleClass("hidden");


}
function saveMenuParam(link, menu_id, param) {
    jq=$("#" + param + "_menu_" + menu_id);
    $base = jq.attr("base_value");
    $val = jq.val();
    if (($val != "") || ($base != "")) {
        $.ajax({
            type: "POST",
            cache: false,
            data: {
                "menu_id": menu_id,
                "type": param,
                "value": jq.val()
            },
            url: $(link).attr("href"),
        });
        jq.attr("base_value", jq.val())
        jq.prop("disabled", true);
        if ($val != "") {
            $("#edit_" + param + "_" + menu_id).toggleClass("hidden");
        } else {
            $("#" + param + "_menu_" + menu_id).addClass("hidden");
            $("#" + param + "_menu_" + menu_id + "_label").toggleClass("hidden");
            $("#add_" + param + "_menu_" + menu_id).toggleClass("hidden");
        }
    }
    else {
        $("#add_" + param + "_menu_" + menu_id).toggleClass("hidden");
        $("#" + param + "_menu_" + menu_id).addClass("hidden");
        $("#" + param + "_menu_" + menu_id + "_label").toggleClass("hidden");
    }
    $("#cancel_" + param + "_menu_" + menu_id).toggleClass("hidden");
    $("#delete_" + param + "_menu_" + menu_id).addClass("hidden");
    $(link).toggleClass("hidden");
    //testForEmptyState(menu_id);
    menuCleaner(menu_id);

}
function editMenuParam(link, menu_id, param) {
    jq=$("#" + param + "_menu_" + menu_id);
    jq.removeClass("hidden");
    jq.prop("disabled", false);
    $(link).toggleClass("hidden");
    $("#save_" + param + "_menu_" + menu_id).toggleClass("hidden");
    $("#cancel_" + param + "_menu_" + menu_id).toggleClass("hidden");
    $("#delete_" + param + "_menu_" + menu_id).toggleClass("hidden");
}
function cancelMenuParam(link, menu_id, param) {
    jq=$("#" + param + "_menu_" + menu_id);
    jq.val(jq.attr("base_value"));
    if (jq.val() == "") {
        $("#add_" + param + "_menu_" + menu_id).toggleClass("hidden");
        $("#" + param + "_menu_" + menu_id).toggleClass("hidden");
        $("#" + param + "_menu_" + menu_id + "_label").toggleClass("hidden");
    }
    else
        $("#edit_" + param + "_" + menu_id).toggleClass("hidden");
    $("#save_" + param + "_menu_" + menu_id).toggleClass("hidden");
    $(link).toggleClass("hidden");
    jq.prop("disabled", true);
    $("#delete_" + param + "_menu_" + menu_id).addClass("hidden");
    //testForEmptyState(menu_id);
    menuCleaner(menu_id);
}
function deleteMenuParam(link, menu_id, param) {
    $.ajax({
        type: "POST",
        cache: false,
        data: {
            "menu_id": menu_id,
            "type": param,
            "value": ""
        },
        url: $(link).attr("href"),
    });
    jq=$("#" + param + "_menu_" + menu_id);
    jq.toggleClass("hidden");
    jq.attr("base_value", "")
    $("#add_" + param + "_menu_" + menu_id).toggleClass("hidden");
    $("#" + param + "_menu_" + menu_id + "_label").toggleClass("hidden");
    $("#cancel_" + param + "_menu_" + menu_id).toggleClass("hidden");
    $("#save_" + param + "_menu_" + menu_id).toggleClass("hidden");
    jq.val("");
    jq.prop("disabled", true);
    $(link).toggleClass("hidden");
    //testForEmptyState(menu_id);
}
// Category
function saveCatParam(link, cat_id, param) {
jq=$("#" + param + "_cat_" + cat_id);
    $base = jq.attr("base_value");
    $val = jq.val();
    if (($val != "") || ($base != "")) {
        $.ajax({
            type: "POST",
            cache: false,
            data: {
                "category_id": cat_id,
                "type": param,
                "value": $val
            },
            url: $(link).attr("href")
        });
        jq.attr("base_value", jq.val())
        jq.prop("disabled", true);
        if ($val !== "")
            $("#edit_" + param + "_" + cat_id).toggleClass("hidden");
        else {
            jq.addClass("hidden");
            $("#" + param + "_cat_" + cat_id + "_label").addClass("hidden");
            $("#add_" + param + "_cat_" + cat_id).toggleClass("hidden");
        }
    }
    else {
        $("#add_" + param + "_cat_" + cat_id).toggleClass("hidden");
        jq.addClass("hidden");
        $("#" + param + "_cat_" + cat_id + "_label").addClass("hidden");
    }
    $("#cancel_" + param + "_cat_" + cat_id).toggleClass("hidden");
    $("#delete_" + param + "_cat_" + cat_id).addClass("hidden");
    $(link).toggleClass("hidden");
}


function editCatParam(link, cat_id, param) {
    jq=$("#" + param + "_cat_" + cat_id);
    jq.removeClass("hidden");
    $("#" + param + "_cat_" + cat_id + "_label").removeClass("hidden");;
    jq.prop("disabled", false);
    $(link).toggleClass("hidden");
    $("#save_" + param + "_cat_" + cat_id).toggleClass("hidden");
    $("#cancel_" + param + "_cat_" + cat_id).toggleClass("hidden");
    $("#delete_" + param + "_cat_" + cat_id).toggleClass("hidden");
}
function cancelCatParam(link, cat_id, param) {
    jq=$("#" + param + "_cat_" + cat_id);
    jq.val(jq.attr("base_value"));
    if (jq.val() == "") {
        $("#add_" + param + "_cat_" + cat_id).toggleClass("hidden");
        jq.toggleClass("hidden");
        $("#" + param + "_cat_" + cat_id + "_label").toggleClass("hidden");
    }
    else
        $("#edit_" + param + "_" + cat_id).toggleClass("hidden");
    $("#save_" + param + "_cat_" + cat_id).toggleClass("hidden");
    $(link).toggleClass("hidden");
    jq.prop("disabled", true);
    $("#delete_" + param + "_cat_" + cat_id).addClass("hidden");

}
function deleteCatParam(link, cat_id, param) {
    $.ajax({
        type: "POST",
        cache: false,
        data: {
            "category_id": cat_id,
            "type": param,
            "value": ""
        },
        url: $(link).attr("href")
    });
    jq=$("#" + param + "_cat_" + cat_id);
    jq.toggleClass("hidden");
    $("#" + param + "_cat_" + cat_id + "_label").toggleClass("hidden");
    jq.attr("base_value", "")
    $("#add_" + param + "_cat_" + cat_id).toggleClass("hidden");
    $("#cancel_" + param + "_cat_" + cat_id).toggleClass("hidden");
    $("#save_" + param + "_cat_" + cat_id).toggleClass("hidden");
    jq.val("");
    jq.prop("disabled", true);
    $(link).toggleClass("hidden");
}
// Services
function editService(link, srv_id) {
    $(".title_service_" + srv_id).prop("disabled", false);
    $(".price_service_" + srv_id).prop("disabled", false);
    $(".field_service_" + srv_id).prop("disabled", false);
    $(".service_edits_" + srv_id).toggleClass("hidden");
    $(".tier_buttons_" + srv_id).toggleClass("hidden");
    $(link).toggleClass("hidden");
    changeInputsToTextareas(srv_id);
}
function changeInputsToTextareas(srv_id) {
    $("input.field_service_" + srv_id).each(function() {
        var input = $(this),
            val = input.val(),
            textarea = $("<textarea></textarea>").attr({
                id: input.prop('id'),
                name: input.prop('name'),
                class: input.prop('class'),
                base_value: input.attr('base_value')
            });
        textarea.val(val);
        input.after(textarea).remove();
    });
}
function changeTextareasToInputs(srv_id) {
    $("textarea.field_service_" + srv_id).each(function() {
        var textarea = $(this),
            val = textarea.val(),
            input = $("<input type='text' />").attr({
                id: textarea.prop('id'),
                name: textarea.prop('name'),
                class: textarea.prop('class'),
                base_value: textarea.attr('base_value')
            });
        input.val(val);
        textarea.after(input).remove();
    });
}
function clearserviceErrors(srv_id) {
    mFields = $("[class*=_service_" + srv_id + "].mandatory");
    $(mFields).parent().removeClass('div-error');
    $("#tr_errors_srv_" + srv_id).addClass("hidden");
    $("#td_errors_srv_" + srv_id).html("");
}
function validateService(srv_id) {
    cError = '';
    mField = $("#title_service_" + srv_id + ".mandatory");
    if ($(mField).val().trim().length < 1) {
        $(mField).parent().addClass("div-error");
        cError = 'Service can’t be blank';
    }
    return cError;
}
function validateServicePrices(srv_id) {
    cError = '';
    mFields = $("[class*=price_service_" + srv_id + "].mandatory");
    flag = true;
    for (i = 0; i < mFields.length; i++) {
        if ($(mFields[i]).val().length < 1) {
            $(mFields[i]).parent().addClass("div-error");
            flag = false;
        }
    }
    if (!flag) {
        cError = 'Price can’t be blank';
    }
    return cError;
}
function saveService(link, srv_id) {
    clearserviceErrors(srv_id);
    changeTextareasToInputs(srv_id);
    error = '';

    serviceValidate = validateService(srv_id);
    if (serviceValidate !== '')
        error = error + serviceValidate;
    priceValidate = validateServicePrices(srv_id);
    if (priceValidate !== '') {
        if (error !== '')
            error = error + ', ';
        error = error + priceValidate;
    }

    if (error === '') {

        $service = $(".title_service_" + srv_id).val();
        $prices = $(".price_service_" + srv_id).serializeArray();
        $fields = $(".field_service_" + srv_id).serializeArray();
        $pricesArray = $(".price_service_" + srv_id);

        flag = true;
        for (i = 0; i < $pricesArray.length; i++) {
            $curVal = $($pricesArray[i]);
            if ($curVal.val().length < 1) {
                flag = false;
            }
            else {
                if ($curVal.val().indexOf('.') != -1) {

                    $arrSpl = $curVal.val().split('.');

                    if ($arrSpl[1].length == 1)
                        $($pricesArray[i]).val($curVal.val() + '0');
                    if ($arrSpl[1].length == 0)
                        $($pricesArray[i]).val($curVal.val() + '00');
                }
            }
        }
        if ($service == '')
            flag = false;
        if (flag) {
            $.ajax({
                type: "POST",
                url: $(link).attr("href"),
                data: {
                    "srv_id": srv_id,
                    "service": $service,
                    "prices": $prices,
                    "fields": $fields
                },
                success: function (response) {
                }
            });
            for (i = 0; i < $pricesArray.length; i++) {

                $($pricesArray [i]).attr("base_value", $($pricesArray [i]).val());
            }
            $(".title_service_" + srv_id).prop("disabled", true);
            $(".price_service_" + srv_id).prop("disabled", true);
            $(".field_service_" + srv_id).prop("disabled", true);

            $(".tier_buttons_" + srv_id).toggleClass("hidden");
            $(".service_edits_" + srv_id).toggleClass("hidden");
            $("#edit_service_" + srv_id).toggleClass("hidden");
        }
    } else {
        $("#tr_errors_srv_" + srv_id).removeClass("hidden");
        $("#td_errors_srv_" + srv_id).html(error);

    }
    return false;
}
function cancelService(link, srv_id, cat_id) {
    clearserviceErrors(srv_id);
    changeTextareasToInputs(srv_id);
    if ($(".price_service_" + srv_id).attr("base_value") != '') {
        $("#title_service_" + srv_id).val($("#title_service_" + srv_id).attr("base_value"));
        flag = true;
        $array = $(".price_service_" + srv_id);
        for (i = 0; i < $array.length; i++) {
            if ($($array[i]).attr("base_value").length < 1)
                flag = false;
            $($array[i]).val($($array[i]).attr("base_value"));
        }
        $array = $(".field_service_" + srv_id);
        for (i = 0; i < $array.length; i++) {
            $($array[i]).val($($array[i]).attr("base_value"));
        }
        if (flag) {
            $(".title_service_" + srv_id).prop("disabled", true);
            $(".price_service_" + srv_id).prop("disabled", true);
            $(".field_service_" + srv_id).prop("disabled", true);

            $(".tier_buttons_" + srv_id).toggleClass("hidden");
            $(".service_edits_" + srv_id).toggleClass("hidden");
            $("#edit_service_" + srv_id).toggleClass("hidden");
        }
        else {
            $.ajax({
                type: "POST",
                url: $(link).attr("tiers"),
                data: {
                    "srv_id": srv_id,
                },
                success: function (response) {
                    $("#menu-cat-" + cat_id + "-services").html(response);
                }
            });
        }
    }
    else {
        deleteService(link, srv_id, cat_id);
    }
}

function deleteService(link, srv_id, cat_id, menu_id) {
    $.ajax({
        type: "POST",
        url: $(link).attr("href"),
        data: {
            "srv_id": srv_id,
        },
        success: function (response) {
            $("#menu-cat-" + cat_id + "-services").html(response);
            menuCleaner(menu_id);
        }
    });
}
/**
 * Cleaning menu  items fo request params
 * hidding  titles if deleted the last service in menu
 */
function menuCleaner(menu_id) {
    $categories = $('.category_m_' + menu_id);
    $services = $('.service_m_' + menu_id);
    if (($categories.length <= 1) && ($services.length == 0)) {
        $(".more_then_0_srv_" + menu_id).addClass('hidden');
        testForEmptyState(menu_id);
    }
}
function testForEmptyState(menu_id) {
    if (($("#disclaimer_menu_" + menu_id).val() == '') && ($("#description_menu_" + menu_id).val() == '')) {
        $(".menu_addit_params_" + menu_id).addClass('hidden');
        $(".menu_empty_state_" + menu_id).removeClass('hidden');
    }
}
// Services - Additional Fields
function editSrvField(link, field_id) {
    $(".srvFieldEdit_" + field_id).removeClass('hidden');
    $("#srv-field-show_" + field_id).prop('disabled', false);
    $("#srv-field-title_" + field_id).prop('disabled', false);
    $(".srv-field-title_" + field_id).toggleClass('hidden');
    $(link).addClass('hidden');
}

function saveSrvField(link, field_id) {
    idSearch = "srv-field-title_" + field_id;
    idTo = 'srv-field-title-error-' + field_id;
    clearTableHeaderErrors(idTo);
    titleValidator = categoryTableHeaderValidator(idSearch);
    jq=$('#srv-field-show_' + field_id);
    if (titleValidator === '') {
        var visible = 0;
        if (jq.prop('checked'))
            visible = 1;

        var title = $('#srv-field-title_' + field_id).val();
        if (title.length > 0) {
            $.ajax({
                type: "POST",
                cache: false,
                data: {
                    'field_id': field_id,
                    "title": title,
                    "visible": visible
                },
                url: $(link).attr("href"),
            });

            jq.attr('base_value', visible);

            $('#l-srv-field-title_' + field_id).html(title);
            $(".srvFieldEdit_" + field_id).addClass('hidden');
            $("#edit_srvField_" + field_id).removeClass('hidden');
            $(".srv-field-title_" + field_id).toggleClass('hidden');
            jq.prop('disabled', true);
        }
    } else {
        $("#" + idTo).removeClass("hidden").html(titleValidator);
    }
}
function cancelSrvField(link, field_id) {
    jq=$('#srv-field-title-error-' + field_id);
    jq.addClass("hidden").html("");
    jq.parent().removeClass('div-error')

    $('#srv-field-title_' + field_id).val($('#l-srv-field-title_' + field_id).html());
    $ch = $("#srv-field-show_" + field_id)
    if ($ch.attr('base_value') == 1)
        $("#srv-field-show_" + field_id).prop('checked', true);
    else
        $("#srv-field-show_" + field_id).prop('checked', false);

    $(".srvFieldEdit_" + field_id).addClass('hidden');
    $("#edit_srvField_" + field_id).removeClass('hidden');
    $(".srv-field-title_" + field_id).toggleClass('hidden');
    $ch.prop('disabled', true);
}
// Services - Base  Fields
function editSrvBaseField(link, cat_id, param) {
    $(".srv_" + param + "_field_edit_" + cat_id).toggleClass('hidden');
    $("#srv_" + param + "_show_" + cat_id).prop('disabled', false);
    $("#l_srv_" + param + "_title_srv_" + cat_id).prop('disabled', false);
}
function categoryTableHeaderValidator(id, title = "Title") {
    cError = '';
    mField = $("#" + id);
    if ($(mField).val().trim().length < 1) {
        $(mField).parent().addClass("div-error");
        cError = title + ' can’t be blank.';
    }
    return cError;

}
function clearTableHeaderErrors(id) {
    mField = $("#" + id);
    $(mField).parent().removeClass('div-error');
    $("." + id).addClass("hidden").html("");

}
function saveSrvBaseField(link, cat_id, param) {
    id = "srv_" + param + "_title_" + cat_id;
    field = $("#" + id);
    console.log(id);
    clearTableHeaderErrors(id);
    titleValidator = categoryTableHeaderValidator(id);
    if (titleValidator === '') {
        chBox = $("#srv_" + param + "_show_" + cat_id);
        var visible = 0;
        if (chBox.prop('checked'))
            visible = 1;
        var title = $("#srv_" + param + "_title_" + cat_id).val();

        if (title.length > 0) {
            $.ajax({
                type: "POST",
                cache: false,
                data: {
                    "cat_id": cat_id,
                    "param": param,
                    "title": title,
                    "visible": visible
                },
                url: $(link).attr("href"),
            });

            chBox.attr('base_value', visible);
            $("#l_srv_" + param + "_title_" + cat_id).html(title);
            $(".srv_" + param + "_field_edit_" + cat_id).toggleClass('hidden');
            chBox.prop('disabled', true);
        }
    } else {

        $("." + id).removeClass("hidden").html(titleValidator);
    }

}

function cancelSrvBaseField(link, cat_id, param) {
    fieldTitleId = "srv_" + param + "_title_" + cat_id;
    clearTableHeaderErrors(fieldTitleId);
    $(".srv_" + param + "_field_edit_" + cat_id).toggleClass('hidden');
    $("#srv_" + param + "_show_" + cat_id).prop('disabled', true);


    $("#srv_" + param + "_title_" + cat_id).val($("#l_srv_" + param + "_title_" + cat_id).html());
    $ch = $("#srv_" + param + "_show_" + cat_id).attr('base_value');
    if ($ch == 1)
        $("#srv_" + param + "_show_" + cat_id).prop('checked', true);
    else
        $("#srv_" + param + "_show_" + cat_id).prop('checked', false);

}
// Services - Tiers
function deleteServiceTier(link, tier_id, cat_id) {
    $.ajax({
        type: "POST",
        url: $(link).attr("href"),
        data: {"tier_id": tier_id},
        success: function (response) {
            $("#menu-cat-" + cat_id + "-services").html(response);
        }
    });
}
function addServiceTier(link, srv_id, cat_id) {
    serviceParams = $('[class*=_service_' + srv_id + ']');
    for (i = 0; i < serviceParams.length; i++) {
        forTierAddSaved = serviceParams.serializeArray()
    }
    $.ajax({
        type: "POST",
        cache: false,
        data: {"srv_id": srv_id,},
        url: $(link).attr("href"),
        success: function (response) {
            $("#menu-cat-" + cat_id + "-services").html(response);
        }
    });

}
function sendAjaxPlace(link, param, id, place) {
    array = $(param + id).serializeArray();
}

function savePhoto(link, id) {
    if ($('#photo_main_' + id).prop('checked'))
        main = 1;
    else main = 0;
    $.ajax({
        type: "POST",
        cache: false,
        data: {
            "id": id,
            "title": $("#photo_title_" + id).val(),
            "description": $("#photo_description_" + id).val(),
            "main": main
        },
        url: $(link).attr("href"),
        success: function (response) {
            $("#photo_manage").html(response);
        }
    });
}

function category_sort(link, cat_id) {
    $.ajax({
        type: "POST",
        url: link.href,
        success: function (response) {
            if (response != false) {
                $("#menu-" + cat_id + "-detail").html(response);
            }
        }
    });
}
function category_edit_btn(link, cat_id) {
    $("#label-title-cat-" + cat_id).toggleClass("hidden");
    $("#input-title-cat-" + cat_id).toggleClass("hidden");
    $("#category_" + cat_id).prop("disabled", false);
    $(link).toggleClass("hidden");
    $("#save_category_" + cat_id).toggleClass("hidden");
    $("#cancel_category_" + cat_id).toggleClass("hidden");
    $("#delete_category_" + cat_id).toggleClass("hidden");
}

function category_edit_name_save(link, cat_id, menu_id) {
    id = "input-title-cat-" + cat_id;
    $("#category_title_error_" + cat_id).addClass("hidden").html("");
    $('#' + id).parent().removeClass('div-error');
    titleValidator = categoryTableHeaderValidator(id, 'Category');
    if (titleValidator === '') {
        label = $("#label-title-cat-" + cat_id).html();
        title = $("#input-title-cat-" + cat_id).val();
        if (title.length > 0) {
            $.ajax({
                type: "POST",
                cache: false,
                data: {
                    "category_id": cat_id,
                    "title": title
                },
                url: link.href,
            });
            if (title.length > 0)
                $(".cat_params_" + cat_id).removeClass("hidden");
            else
                $(".cat_params_" + cat_id).addClass("hidden");
            $(".cat_title_" + cat_id).html(title);
            if (((title.length == 0) && (label.length != 0)) || ((title.length != 0) && (label.length == 0)))
                $(".add_cat_" + menu_id).toggleClass("hidden");
            $("#label-title-cat-" + cat_id).html($("#input-title-cat-" + cat_id).val());
            $("#label-title-cat-" + cat_id).toggleClass("hidden");
            $("#input-title-cat-" + cat_id).toggleClass("hidden");

            $("#edit_category_" + cat_id).toggleClass("hidden");
            $("#cancel_category_" + cat_id).toggleClass("hidden");
            $("#delete_category_" + cat_id).addClass("hidden");
            $(link).toggleClass("hidden");
        }
    } else {
        $("#category_title_error_" + cat_id).removeClass("hidden").html(titleValidator);
    }
}
function category_edit_cancel(link, cat_id) {
    id = "input-title-cat-" + cat_id;
    label = $("#label-title-cat-" + cat_id).html();
    if (label.length > 0) {
        $("#category_title_error_" + cat_id).addClass("hidden").html("");
        $('#' + id).parent().removeClass('div-error');
        $("#input-title-cat-" + cat_id).val($("#label-title-cat-" + cat_id).html());
        $("#label-title-cat-" + cat_id).toggleClass("hidden");
        $("#input-title-cat-" + cat_id).toggleClass("hidden");
        $("#edit_category_" + cat_id).toggleClass("hidden");
        $("#save_category_" + cat_id).toggleClass("hidden");
        $("#delete_category_" + cat_id).addClass("hidden");
        $(link).toggleClass("hidden");
    } else {
        var url = "/account/cancel-category";
        category_cancel(cat_id, url);
    }
}

function category_cancel(id, url) {
    $.ajax({
        type: "POST",
        cache: false,
        data: {"category_id": id},
        url: url,
        success: function (response) {
            var obj = $.parseJSON(response);
            if (obj.success == 1){
                $("#div-title-cat-" + id).parent().parent().parent().remove();
                $(".row-cust-cat").removeClass("hidden");
                $(".row-cust-cat .cat-nav a").removeClass("hidden");
                $(".row-cust-cat .cat-nav label").addClass("hidden");
            }
        }
    });
}

function category_delete_confirm(link) {
    to_del_id = $(link).attr('del_id');
    $.ajax({
        type: "POST",
        cache: false,
        data: {"category_id": to_del_id},
        url: link.href
    });
}
function category_add_description_btn(link, cat_id) {
    $("#description_cat_" + cat_id).removeClass("hidden");
    $("#description_cat_" + cat_id + "_label").removeClass("hidden");
    $("#description_cat_" + cat_id).prop("disabled", false);
    $(link).toggleClass("hidden");
    $("#save_description_cat_" + cat_id).toggleClass("hidden");
    $("#cancel_description_cat_" + cat_id).toggleClass("hidden");
}

function category_add_disclaimer_btn(link, cat_id) {
    $("#disclaimer_cat_" + cat_id).toggleClass("hidden");
    $("#disclaimer_cat_" + cat_id + "_label").toggleClass("hidden");
    $("#disclaimer_cat_" + cat_id).prop("disabled", false);
    $(link).toggleClass("hidden");
    $("#save_disclaimer_cat_" + cat_id).toggleClass("hidden");
    $("#cancel_disclaimer_cat_" + cat_id).toggleClass("hidden");
}
function category_add_btn(link, menu_id) {
    $.ajax({
        type: "POST",
        cache: false,
        data: {
            "menu_id": menu_id
        },
        url: link.href,
        success: function (response) {
            if (response)
                $("#menu-" + menu_id + "-detail").html(response);
            $("add_cat_" + menu_id).addClass("hidden");
        }
    });
}
function service_additional_field_sort(link, menu_id) {
    $.ajax({
        type: "POST",
        url: link.href,
        success: function (response) {
            if (response != false) {
                $("#menu-" + menu_id + "-detail").html(response);
            }
        }
    });
}
function service_sort(link, cat_id) {
    $.ajax({
        type: "POST",
        url: link.href,
        success: function (response) {
            if (response != false) {
                $("#menu-cat-" + cat_id + "-services").html(response);
            }
        }
    });
}
function detail_add_btn(link, cat_id) {
    $.ajax({
        type: "POST",
        cache: false,
        data: {"cat_id": cat_id},
        url: link.href,
        success: function (data) {
            if (data) {
                $("#detail_add_link_" + cat_id).hide();
                $("#new_field_" + cat_id).html(data);
            }
        }
    });
}
function service_add_btn(link, cat_id) {


    $.ajax({
        type: "POST",
        cache: false,
        data: {"cat_id": cat_id},
        url: link.href,
        success: function (response) {
            if (response)
                $("#menu-cat-" + cat_id + "-services").html(response);
        }
    });
}
function operation_hours_edit_btn(link) {
    $.ajax({
        type: "GET",
        url: link.href,
        success: function (data) {
            $(".to_hours").html(data);
        }
    });
    $(".save_operation").toggleClass("hidden");
    $(link).toggleClass("hidden");
}
function operation_hours_cancel(link, operation) {
    $(".to_hours").html();
    $.ajax({
        type: "GET",
        url: link.href,
        success: function (data) {
            $(".to_hours").html(data);
            if (operation == 1)
                $("#edit_operation").toggleClass("hidden");
        }
    });
}
function operation_hours_switch_day(key) {
    jq=$(".day_" + key);
    if ( jq.prop("disabled") == true) {
        jq.prop("disabled", false);
        jq.attr("dayoff", 0);
    }
    else {
        jq.prop("disabled", true);
        jq.attr("dayoff", 1);
    }
}
function for_setup_tip() {
    $(".btn-tip-show").toggleClass("hidden");
    $(".btn-tip-hide").toggleClass("hidden");
}
function limitChars(elem) {
    $(elem).keyup(function(){
        var str = $(this).val(),
            count = str.length,
            max = $(this).attr("size");
        if(count > max){
            $(this).val(str.substr(0, max));
        }
    });
}