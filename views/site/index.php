<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

//use yii\helpers\ArrayHelper;
//use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;

//use app\models\User;

$url_zip = Url::toRoute(['site/get-biz-by-zipcode']);
//$this->registerJsFile('/js/bootstrap-modal.min.js');
$script = <<< JS

$('#searchform-zip')
    .on('change keyup', function (e) {
    var zip =  String(this.value);
    if (zip.length == 5) {
        $.ajax({
            type: "GET",
            url: "$url_zip",
            data:{"zip":zip},
            success: function(response) {
                if (response != false) {
                     //   $('#search_query').prop("disabled",false);
                }
                else{
                    $('#searchform-zip').val('');
                    $('#zip_to_email').val(zip);
                    //$('.modal_head_message').html(':( .. Sorry but we are currently not live in your city');
                    //$('.modal-body-text').html('Please Check back ...we will be there soon<br> If you leave your email address and we notify you when we are live in your city. ');
                    $('#indexModal').modal('show');
                }
            }
        });
    }
	
}) ;

JS;
$this->registerJs($script);

$this->title = 'Wizerd';
if ($message) {
    $hideMessage = '';
} else {
    $hideMessage = 'hidden';
}
?>
<div class="form-inline header-form  searchpage-inputs">
    <?php
    $form = ActiveForm::begin([
        'action' => Url::to('/'),
        'id' => 'search-form',
        // 'enableClientValidation' => false,
        // 'enableAjaxValidation' => true,
        'method' => 'GET'
    ]);
    ?>
    <?php
    if ($model->zip) {
        ?>
        <div class="container">
            <div class="messages col-sm-6 col-sm-offset-3 <?= $hideMessage ?>">
                <?php

                //  $zipInt = preg_replace('/\D/', '', $model->zip);
                //$model->zip = '';
                //if (strlen($zipInt) != 5)

                if ($message === 'ziperror') {
                    ?>
                    <div class="message_close">
                        <?= Html::a('Close', null, [
                            'class' => 'btn-link',
                            'onclick' => '
                        $(".messages").addClass("hidden");
                        return false;
                    '
                        ]) ?>
                    </div>
                    <div class="message_body">
                        <p>
                            The zip code you entered is not valid. Please search again using a 5 digit zip code.
                        </p>
                    </div>
                    <?php
                } else {
                    if ($message === 'zipinactive') {
                        $js = "
                        $('#indexModal').modal('show');
                    ";
                        $this->registerJs($js);
                    } else {
                        $js = "
                        $('#main_nav_bar').removeClass('lightorng').addClass('stylenone');
                    ";
                        $this->registerJs($js);
                        ?>
                        <div class="message_body">
                        <div class="message_close">
                            <?= Html::a('Close', null, [
                                'class' => 'btn-link',
                                'onclick' => '
                    $(".messages").addClass("hidden");
                    return false;
                    '
                            ]) ?>
                        </div>
                        <div class="msg-content-padding">
                            <p>Sorry, we currently don’t have that type of business in your city.</p>

                            <p>Please check back as we are always adding new services and businesses.</p>

                            <p>Try searching one of these categories: hair salon, day spa, mani/pedi, massage, hair removal, facial.</p>
                        </div>    <!--.msg-content-padding-->
                        <?php
                        if (isset($message_city))
                            // echo Html::a('wizerd.com/' . strtolower($message_city->name), Url::to(strtolower($message_city->name)));
                            ?>
                            </div>

                            <?php
                    }
                }
                ?>
            </div>
        </div>

        <?php
        $model->zip = '';
    }
    ?>
    <div class="container invalid-search-margtop">
        <div class="col-md-12">
            <?php echo Html::img('@web/images/home-logo.png', ['class' => 'img-responsive center-block']); ?>
            <?php echo Html::img('@web/images/home-vector-object.png',
                ['class' => 'img-responsive center-block home-vector-object']); ?>
        </div>
    </div>
    <div class="container">
        <div class="col-md-12 text-center field-wrapper">
            <?php echo $form->field($model, 'zip')
                ->input('text', [
                    'placeholder' => $model->getAttributeLabel('zip'),
                    'class' => 'form-control n_zip_code',
                    'size' => 5
                ])
                ->label(false); ?>


            <?= $form->field($model, 'search')
                ->input('text', [
                    'class' => 'form-control n_tell_me',
                    'placeholder' => 'Enter search phrase',
                    'size' => 75										
                ])
                ->label(false); ?>                
            <?= Html::submitButton('Search', [
                'class' => 'btn btn-info n_search ',
                'name' => 'search-button'
            ]) ?>        
        </div>
        <div class="row">
        <div class="col-sm-12 height_panel"></div>
        <div class="col-sm-11">
        <div class="search_tagline">
        Search for local beauty services like "partial highlight", "massage 60 mintues" etc
        </div>
        </div>
        </div>
    </div> <!--./form-container-->
</div>

<!--<div class="container n_index_text padleft_30">
<p class="font-size13">Get pampered. Shop for massages, haircuts, facials, manicures, pedicures, etc.</p>
</div>
-->
<div class="container-fluid how_it_img">
    <h1>search local ... just different</h1>
    <?php echo Html::img('@web/images/home-icons.png', ['class' => 'img-responsive center-block homebottom-icons']); ?>
    <div class="home-icons-responisve clearfix">
        <ul>
            <li><?php echo Html::img('@web/images/hair-salon-ico.png',
                    ['class' => 'img-responsive center-block']); ?></li>
            <li><?php echo Html::img('@web/images/barber-ico.png', ['class' => 'img-responsive center-block']); ?></li>
            <li><?php echo Html::img('@web/images/day-spa-ico.png', ['class' => 'img-responsive center-block']); ?></li>
            <li><?php echo Html::img('@web/images/fac-ico.png', ['class' => 'img-responsive center-block']); ?></li>
            <li><?php echo Html::img('@web/images/wax-ico.png', ['class' => 'img-responsive center-block']); ?></li>
            <li><?php echo Html::img('@web/images/mani-ico.png', ['class' => 'img-responsive center-block']); ?></li>
            <li><?php echo Html::img('@web/images/massage-ico.png', ['class' => 'img-responsive center-block']); ?></li>
        </ul>
    </div>    <!--./home-icons-responisve-->

    <h2>Visit <?php echo Html::a('How it works', Url::toRoute('/site/instruction'), array('class' => 'orng-clr')); ?> to
        learn more.</h2>
</div> <!--.how_it_img-->
</div>
<?php $form::end(); ?>
</div>    <!--./form-inline-->


<div class="modal fade" id="indexModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal_head_message"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">Cancel</span></button>
            </div>
            <div class="modal-body">
                <div class="modal-body-text index-modal">

                    <p>Sorry, we have not launched in your city yet.</p>

                    <p>Please check back as we will be there soon.</p>

                    <p>Leave your email address and we will notify you once live in your city.</p>

                </div>
                <?php
                $emailForm = ActiveForm::begin([
                    'id' => 'save_wait_email',
                    'action' => Url::to(['site/save-wait-email'])
                ]);
                echo $emailForm->field($waitEmail, 'email')
                    ->input('email');
                echo Html::hiddenInput('SaveWaitEmail[zip]', null, ['id' => 'zip_to_email']);
                echo Html::submitButton('Submit', [
                    'class' => 'btn btn-info',
                    'name' => 'submit-button'
                ]);

                $emailForm::end();

                ?>
                <!--                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
            </div>

        </div>
    </div>
</div>
