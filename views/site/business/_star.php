<?php
use yii\helpers\Html;
use yii\helpers\Url;

$denied = 0;
if (Yii::$app->user->isGuest) {
    $denied = 1;
} elseif (Yii::$app->user->identity->getBusiness()) {
    $denied = 1;
}
if (in_array($tierId, $savedIds)) {
    $toclass = 'glyphicon-star';
} else {
    $toclass = 'glyphicon-star-empty';
}
?>
    <br/>
<?php //echo Html::img('@web/images/promo-star.png', ['class' => 'img-responsive center-block promo-star']); ?>
<?php echo Html::a('<span  class="glyphicon ' . $toclass . '"  id="saved_' . $srvId . '_' . $tierId . '"></span>',
    Url::toRoute(['account/save-promo']), [
        'class' => 'btn btn-primary btn-xs promo-star',
        'data-denied' => $denied,
        'onclick' => '
            var denied = $(this).data("denied");
            if (denied == 1) {
                $("#formModal").modal();
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: $(this).attr("href"),
                    data:{
                        srv:' . $srvId . ',
                        tier:' . $tierId . ',
                        promo:' . $promoId . '
                    },
                    success: function(response) {
                        if (response){
                            $("#saved_' . $srvId . '_' . $tierId . '").removeClass("glyphicon-star-empty").addClass("glyphicon-star");
                        }
                    }
                });
            }
            return false;'
    ]);
?>
