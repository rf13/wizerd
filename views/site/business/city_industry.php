<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.12.15
 * Time: 16:46
 */
use yii\helpers\Html;
?>
<h1>city <?= $city->name?> industry <?= $industry->title?></h1>
<?php


foreach ($city->zipCodes as $zip) {
    foreach ($zip->businesses as $biz) {
        ?>
        <div class="row">
            <div class="col-sm-12">
                <?=Html::encode($biz->name)?>
            </div>
        </div>

        <?php
    }

}

?>

