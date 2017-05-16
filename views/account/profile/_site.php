<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
/*
$this->registerJsFile(Url::to('/js/ZeroClipboard.js'));
$this->registerJs('

  var clip = new ZeroClipboard.Client();
  clip.setText( "Copy me!" );
  clip.glue( "copy_link_btn" );
');

*/

$script = <<< JS

function copyToClipboard() {
    var temp = $("<input>")
    $("body").append(temp);
    temp.val($("#link_cp").text()).select();
    document.execCommand("copy");
    temp.remove();

    return false;
}


JS;

$this->registerJs($script);



$this->title = 'wizerd Site';
?>





    <div class="user-account-public-profile gray-content">

        <div class="row n_profile_site_text">
            <div class="col-sm-12">
                <p>
                    Here is the link to your personalized Wizerd site. This is what consumers will see when they are shopping for your services.
                </p>

                <p>
                    Businesses that promote their Wizerd site by posting their URL on social media are seeing are increase in customers and sales.
                    You can manually copy the URL or use the copy button.
                </p>
            </div>
        </div>
        <div class="row n_profile_site_text">
            <div class="col-sm-12">
                <?php
                if (!empty($business->vanity_name)) {
                    ?>
                    <div class="row">
                        <div class="col-sm-12 n_profile_site_link">
                            <span id="link_cp"><?= Html::a(Html::encode($business->makeBusinessLink()),Url::to('/'.Html::encode($business['vanity_name'])),['target'=>'_blank','class'=>'btn-link'] )?></span>
                            <?= Html::a('Copy link',null, [
                                'class' => 'btn btn-info',
                                'id'=>'copy_link_btn',
                                'onclick'=>'copyToClipboard(this); return false;'
                            ]) ?>
                        </div>
                    </div>

                    <?php
                } else {
                    ?>
                    <div class="row">
                        <div class="col-sm-10 ">
                            <label>Wizerd Site Not setup yet. </label>
                            <?= Html::a('Click here to activate', Url::to(['/user/account', 'active' => 'settings']), ['class' => 'btn btn-info btn-lg']) ?>
                        </div>
                    </div>

                <?php } ?>


            </div>
        </div>

    </div>


<?php

?>