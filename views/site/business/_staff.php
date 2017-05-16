<?php
use yii\helpers\Html;
?>

<?php if (count($model->staffs)>0) : ?>
<div class="row content " id="staff">
    <div class=" staff_title_row">
        <div class="container ">
            <div class="row">
                <div class="col-sm-9 staff_title">
                    <p>Staff Members</p>
                </div>
                <div class='col-sm-3 staff_title_empty'></div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php
            for ($i = 0; $i < count($model->staffs); $i++) {
            ?>
                <?php if ($i % 3 == 0): ?>
                    </div>
                    <div class="row row-staff">
                <?php endif;?>

                <div class="col-xs-12 col-sm-4 staff">
                    <div class="col-sm-12 staff_img">
                        <?php if ($model->staffs[$i]->url) echo Html::img($model->staffs[$i]->getWebPath(), ['class' => 'img-responsive','alt' => Html::encode($model->staffs[$i]->name)])?>
                    </div>
                    <div class="col-sm-12 staff_name">
                        <p> <?= Html::encode($model->staffs[$i]->name) ?></p>
                    </div>
                    <div class="col-sm-12 staff_role">
                        <p><?= Html::encode($model->staffs[$i]->role) ?></p>
                    </div>
                    <div class="col-sm-12 staff_description">
                        <p><?= Html::encode(substr($model->staffs[$i]->description, 0, 250)); ?></p>

                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<?php endif;?>