<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\forms\ProfilePublicForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\web\JsExpression;

$this->title = 'Public profile';
$city = $model->city;
$stat = $model->state;
$getAgencyLink = "'" . Url::to('/account/get-agency') . "'";
$dropMap = [];
$agency = $business->getAgency();
if ($agency !== null) {
    $dropMap[$agency->id] = $agency->makeAddressForDropdown();
    $name_label='Your name or business name';
    $agencyTitle=$agency->makeAddressForDropdown();
}else {
    $name_label='Business name';
}


$script = <<<JS
var getAgencyLink=$getAgencyLink;
var arrayBiz;

function contractor_checkbox(){
    if ($("#profilepublicform-contractor").prop('checked') ===true)
    {
        $(".agency_param").prop('readonly',true);
        $("#business_name_label").html("Your name or business name");
    }
    else {
        array=$(".agency_param");
        for(i=0;i<array.length;i++){
            $(array[i]).val($(array[i]).attr("base_value"));
        }
        $(".for_agency_select").val("");
        $("#agency_dropdown").html("");
        $(".no_agency_found").addClass("hidden");
        $(".agency_found").removeClass("hidden");
        $(".agency_param").prop('readonly',false);
        $("#business_name_label").html("Business name");
    }
    $("#contractor_additional").toggleClass("hidden");
    $('.ac_fields').toggleClass('ac_fields_border');
}

$("#search_agency_for_ic").on("click",function(e){
get_agency();
});
$("#save_public_btn").on("click",function(e){
    if($("#profilepublicform-contractor").prop('checked')===true){
        if($("#agency_dropdown").val()>0)
            return true;
        else return false;
    }
});

function get_agency(){
    $("#agency_dropdown").html("");
    url=$("#agency_url").val();
    zip=$("#agency_zip").val();
    address=$("#agency_address").val();

    if((url)||((zip.trim().length==5)&&(address.trim().length>3)))
        $.ajax({
            type:"POST",
            dataType: "json",
            cache:false,
            data:{
                "url": url,
                "zip": zip,
                "address": address,
            },
            url: getAgencyLink,
            success: function (res) {
                if(res) {
                    count=0;
                    arrayBiz=res;
                    dropdown='<option value="0">Select agency</option>';
                    $.each(res,function(key,val){
                        count++;
                        dropdown=dropdown+'<option value="'+val.id+'" >'+val.title+'</option>';
                    });
                    $("#agency_dropdown").html(dropdown);
                 //   $("#agency_dropdown").val("0").trigger("change");
                }
                if(count>0){
                    $(".no_agency_found").addClass("hidden");
                    $(".agency_found").removeClass("hidden");
                }
                else{
                    $(".no_agency_found").removeClass("hidden");
                    $(".agency_found").addClass("hidden");
                }

            }
        }).done(function(e){
        $('#agency_dropdown option:first-child').select();
        });
}

function fillContractorParams(id){
    $.each(arrayBiz,function(key,value){
        if (key==id){
            $("#profilepublicform-address").val(value.address);
            $("#profilepublicform-suite").val(value.suite);
            $("#profilepublicform-city").val(value.city);
            $("#profilepublicform-state").val(value.state);
            $("#profilepublicform-zip_code").val(value.zip);
        }
    });
}
$("#agency_dropdown").change(function(e){

    fillContractorParams($(this).val());
})

$("#cancel_edit_public").on("click",function(e){
    checkboxes=$(".is_checkbox");
    for(i=0;i<checkboxes.length;i++){
        if($(checkboxes[i]).attr("base_value")==0)
            value=false;
        else
            value=true;
        if ( $(checkboxes[i]).prop("checked")!==value){
            $(checkboxes[i]).prop("checked", value);
            $(checkboxes[i]).change();
        }
    }
    filds= $(".public_profile.can_cancel");
    for (i=0;i<filds.length;i++){
        $(filds[i]).val($(filds[i]).attr("base_value"));
    }
      $(".agency_hide").addClass("hidden");
    $(".public_profile").prop("disabled",true);
    $("#cancel_edit_public").toggleClass("hidden");
    $(".save_profile").toggleClass("hidden");
    $("#edit_profile").toggleClass("hidden");
    if ($("#profilepublicform-home").attr("base_value")==1)
        $("#profilepublicform-home").prop("checked","checked");
    else
        $("#profilepublicform-home").removeProp("checked");
})

function public_change_zip(default_city ,default_state ,url,inp){
            $.ajax({
                method: "POST",
                url: url,
                data: {zip: $(inp).val()},
                dataType:"json"
            })
            .done(function(data) {
                if (data != null) {
                    $("#profilepublicform-city").val(data.city);
                    $("#profilepublicform-state").val(data.state);
                } else {
                    $("#profilepublicform-city").val(default_city);
                    $("#profilepublicform-state").val(default_state);
                }
            });
}

$("#edit_profile").on("click",function(e){
 $(".public_profile").prop("disabled",false);
                                 $(".save_profile").toggleClass("hidden");
                                 $("#edit_profile").toggleClass("hidden");
                                 $("#cancel_edit_public").toggleClass("hidden");
                                 $(".agency_hide").removeClass("hidden");
})

JS;

$this->registerJs($script);

if ($model->contractor) {
    $contractorHidden = "";
    $contractorDisabled = true;
} else {
    $contractorHidden = "hidden";
    $contractorDisabled = false;
}
?>
<div class="user-account-public-profile gray-content">

<?php

    $disabled = $model->isFilled();
    if($disabled){
        $agencyHide='hidden';
    }else{
        $agencyHide='';
    }
    $hide = '';

    if ($disabled) $hidden = 'hidden'; else $hidden = '';
    //Yii::$app->tip->display('text 333 text 333 text 333 text 333 text');

    $form = ActiveForm::begin([
        'id' => 'public-profile-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        // 'options' => ['class' => 'form-horizontal'],
        'layout' => 'horizontal',

        'fieldConfig' => [
            'template' => "{label} {beginWrapper} {input} {error} {endWrapper} \n {hint}",
            //'template' => '<div class="col-sm-3 col-sm-offset-1">{label}</div> <div class="col-sm-8">{beginWrapper} {input} {error} {endWrapper}</div> {hint}',
            'horizontalCssClasses' => [
                'offset' => '',
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-6 input-div',
                'error' => '',
                'hint' => 'col-sm-6',
                //'hint' => 'col-sm-6',

            ],
        ],
    ]);
    $hint = 'Select this box if you are a home based business. ';
    $hint .= 'This means you don’t have a physical store and perform your services at the consumer’s location. ';
    $hint .= 'In this scenario Wizerd will hide your physical address.';

    $hintAC = "Select this box if you are a contractor and partner with another business. For example, you are a hair stylist and rent space at ABC Salon. You may be responsible for setting your prices, acquiring your clients, managing your schedule, etc. In this scenario you will control your profile, but Wizerd will tag you to ABC Salon. Read more in <a href=\"/business-support\" >business support.</a>";
    ?>
    <div class="col-sm-12 n_profile_field">
        <div class="col-sm-2 text_right_label_s">
            <?= Html::label('Home') ?>

        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'home')->checkbox(['class' => 'public_profile can_cancel is_checkbox', 'disabled' => $disabled, 'base_value' => $model->home])->label(false); ?>
        </div>
        <div class="col-sm-8 n_profile_text">
            <?= $hint ?>
        </div>
    </div>
    <div class="ac_fields col-sm-12">
        <div class="col-sm-12 n_profile_field">
            <div class="col-sm-2 text_right_label_s">
                <?= Html::label('Contractor') ?>
            </div>
            <div class="col-sm-1 ">
                <?= $form->field($model, 'contractor')->checkbox([
                    'class' => 'public_profile can_cancel is_checkbox',
                    'disabled' => $disabled,
                    'base_value' => $model->contractor,
                    'onchange' => '
                        contractor_checkbox();
                    '
                ])->label(false); ?>
            </div>
            <div class="col-sm-8 n_profile_text">
                <?= $hintAC ?>
            </div>
        </div>

        <div id="contractor_additional" class=" col-sm-12  <?= $contractorHidden ?>">
            <div class="col-sm-10 col-sm-offset-1 agency_hide n_prof_ac_fields_step <?=$agencyHide?>">
                Step 1: Enter the Wizerd URL or zip code and address for the business you partner with. Then select search.
            </div>

            <div class="col-sm-12 ">
                <?= $form->field($model, 'agency_url', ['template' => '<div class="col-sm-2 text_right_label">{label}</div><div class="input-group http-div col-sm-6"><span class="input-group-addon">http://</span>{input}</div>',])->input('text', [
                    'id' => 'agency_url',
                    'class' => 'form-control public_profile for_agency_select public_profile',
                    'placeholder' => 'wizerd.com/example-business-name',
                    'disabled' => $disabled,
                ])->label("Wizerd URL", ['class' => '']) ?>
            </div>

            <div class="col-sm-12 agency_hide <?=$agencyHide?>">
                <div class="col-sm-2 text_right_or">
                    <?= Html::label('OR') ?>
                </div>
            </div>

            <div class="col-sm-2 text_right_label">
                <?= Html::label('Address') ?>
            </div>

            <div class="col-sm-2 no_padding">
                <?= Html::input('text', 'agency_zip', $model->agency_zip, [
                    'id' => 'agency_zip',

                    'class' => 'col-sm-3 form-control for_agency_select public_profile',
                    'placeholder' => 'Enter zip code',
                    'disabled' => $disabled,
                ]) ?>

            </div>
            <div class="col-sm-4 no_padding">
                <?= Html::input('text', 'agency_address', $model->agency_address, [
                    'id' => 'agency_address',
                    'class' => ' form-control for_agency_select public_profile',
                    'placeholder' => 'Enter address',
                    'disabled' => $disabled,
                ]) ?>

            </div>

            <div class="row agency_hide <?=$agencyHide?>">
                <div class="col-sm-offset-2 col-sm-10  padding_top">
                    <?= Html::button('Search', [
                        'class' => 'btn btn-info col-sm-2 public_profile',
                        'id' => 'search_agency_for_ic',
                        'disabled' => $disabled,
                    ]) ?>
                </div>
            </div>

            <div class="col-sm-10 col-sm-offset-1 n_prof_ac_fields_step agency_hide <?=$agencyHide?>">
                Step 2: Select your business partner from the list below.
            </div>

            <div class="row">
                <div class="container">
                    <div class="col-md-12 agency_found_div">
                        <div class="col-sm-2 text_right_label">
                            <?= Html::label('Partner') ?>
                        </div>
                        <div class="col-sm-6  agency_found">
                            <?php
                            /*echo $form->field($model, 'agency')->widget(\kartik\select2\Select2::classname(), [
                                'hideSearch' => true,
                                'data' => $dropMap,
                                'options' => [
                                    'class' => 'form-control public_profile',
                                    'id' => 'agency_dropdown',
                                    'disabled' => $disabled,
                                   // 'placeholder'=>'testholder',

                                ],
                                'pluginOptions' => [
                                    'allowClear' => true

                                ],
                            ])->label(false);
*/
                            echo $form->field($model, 'agency', ['template' => '{input}'])->dropDownList($dropMap, [
                                'id' => 'agency_dropdown',
                                'class' => 'form-control public_profile',
                                'disabled' => $disabled,
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2 no_agency_found hidden">
                            <p>
                                At this time your partner company has not signed up.
                                Please try searching again if you feel this is a mistake.
                            </p>

                            <p>
                                You can still create a personalized Wizerd site without your partner company.
                                To create your account sign up as you were doing, but don’t select the “Contractor” button.
                            </p>

                            <p>
                                Please encourage your partner company to sign up also.
                                After they sign up you can update your profile and tag yourself to them.
                            </p>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <label id="business_name_label" class="control-label col-sm-2" for="profilepublicform-name">Business name</label>
    <?= $form->field($model, 'name')
        ->input('text', ['class' => 'form-control public_profile can_cancel', 'disabled' => $disabled, 'base_value' => $model->name])
        ->label(false) ?>
    <label class="control-label col-sm-2" for="profilepublicform-address">Physical address</label>
    <?= $form->field($model, 'address')->label(false)->input('text', [
        'class' => 'form-control public_profile can_cancel agency_param',
        'disabled' => $disabled,
        'readonly' => $contractorDisabled,
        'base_value' => $model->address,
    ]) ?>
    <label class="control-label col-sm-2" for="profilepublicform-suite">Suite</label>
    <?= $form->field($model, 'suite')->label(false)->input('text', [
        'class' => 'form-control public_profile can_cancel agency_param',
        'disabled' => $disabled,
        'readonly' => $contractorDisabled,
        'base_value' => $model->suite,
    ]) ?>
    <label class="control-label col-sm-2" for="profilepublicform-zip_code">Zip code</label>
    <?= $form->field($model, 'zip_code')->label(false)->textInput([
        'class' => 'form-control public_profile agency_param can_cancel',
        'onchange' => '
            public_change_zip("'.$city.'","'.$stat. '","'. Url::toRoute(['user/zip-address']).'",this);

        ',
        'base_value' => $model->zip_code,
        'disabled' => $disabled,
        'readonly' => $contractorDisabled,
    ]) ?>
    <label class="control-label col-sm-2" for="profilepublicform-city">City</label>
    <?= $form->field($model, 'city')->label(false)->input('text', [
        'class' => 'form-control public_profile agency_param can_cancel',
        'disabled' => $disabled,
        'readonly' => $contractorDisabled,
        'base_value' => $model->city,
        // 'id' => 'biz_city',

    ]) ?>
    <label class="control-label col-sm-2" for="profilepublicform-state">State</label>
    <?= $form->field($model, 'state')->label(false)->input('text', [
        'class' => 'form-control public_profile agency_param can_cancel',
        'disabled' => $disabled,
        'readonly' => $contractorDisabled,
        'base_value' => $model->state,
        // 'id' => 'biz_state'
    ]) ?>
    <label class="control-label col-sm-2" for="profilepublicform-phone">Business phone</label>
    <?= $form->field($model, 'phone')->label(false)->widget(MaskedInput::className(), ['options' => [
        'disabled' => $disabled,
        'base_value' => $model->phone,
        'class' => 'form-control public_profile can_cancel'
    ],
        'model' => $model, 'name' => 'phone', 'type' => 'tel', 'mask' => '(999) 999 - 9999', 'id' => 'test'
    ])
    ?>
    <?php //echo  $form->field($model, 'website')->widget(MaskedInput::className(), ['model' => $model, 'name' => 'website', 'clientOptions' => ['alias' =>  'url']    ])  ?>

    <label class="control-label col-sm-2" for="profilepublicform-website">Website</label>
    <?= $form->field($model, 'website', ['inputTemplate' => '<div class="input-group "><span class="input-group-addon">http://</span>{input}</div>'
    ])->label(false)->input('text', ['class' => 'form-control public_profile can_cancel', 'disabled' => $disabled, 'base_value' => $model->website]) ?>

    <label class="control-label col-sm-2" for="profilepublicform-contact_email">Contact us email</label>
    <?= $form->field($model, 'contact_email')->label(false)->input('email', ['class' => 'form-control public_profile can_cancel', 'disabled' => $disabled, 'base_value' => $model->contact_email]) ?>
    <?php
    //$hint = '<span class="attention">(This will be used on the search results page if service description for a menu ';
    //$hint .= 'item is left blank. It will also be shown on the homepage of your Wizerd site.)</span>';
    ?>
    <?php //echo $form->field($model, 'description')->textArea(['rows' => '4', 'style' => 'resize:none'])->hint($hint);  ?>
    <label class="control-label col-sm-2" for="profilepublicform-description">About us</label>
    <?= $form->field($model, 'description')->label(false)->textArea(['rows' => '4', 'style' => 'resize:none', 'class' => 'form-control public_profile can_cancel', 'disabled' => $disabled, 'base_value' => $model->description]); ?>
    <?php unset($hint); ?>
    <div class="form-group">

        <div class="row n_profile_btn">

            <div class=" col-sm-offset-2 col-sm-4">

                <?php
                if ($disabled) $hide = 'hidden';
                echo Html::submitButton('Save', ['class' => 'btn btn-block btn-info save_profile ' . $hide, 'id' => 'save_public_btn']) ?>
                <?php
                if (!$disabled) $hide = 'hidden';
                else $hide = '';

                echo Html::a('Edit', null, [
                    'id' => 'edit_profile',
                    'class' => 'btn col-sm-2 edit_profile ' . $hide,

                ])
                ?>
                <?=
                Html::a('Cancel', null, [
                    'class' => 'btn btn-block btn-default  hidden',
                    'id' => 'cancel_edit_public',
                ])
                ?>
            </div>

            <div class="col-sm-1 ">



            </div>


        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
