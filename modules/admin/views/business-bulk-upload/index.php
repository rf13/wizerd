<?php
/**
 * @var $this yii\web\View
 * @var $isLocked bool
 * @var $isUpload bool
 * @var $ready bool
 * @var $checkErrors string
 * @var $bulkUploadModel \app\modules\admin\models\BusinessBulkUpload
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Business Bulk Upload';
$this->params['breadcrumbs'][] = $this->title;
$progressUrl = \yii\helpers\Url::to('get-progress');
$redirectUrl = \yii\helpers\Url::to('admin/business');
$isLockedStr = $isLocked
    ? 'true'
    : 'false';
?>
<div class="admin-panel">
    <div class="row">
        <div class="col-lg-1 col-lg-offset-11">
            <?php echo Html::a('Help', \yii\helpers\Url::to('help'), [
                'class' => 'btn btn-primary',
                'target' => '_blank'
            ]) ?>
        </div>
    </div>
    <div class="row">
        <?php if ($isLocked) {
            $this->registerJs(<<<JS
            var isLocked = $isLockedStr;
            setInterval(function () {
                if (isLocked) {
                    $.getJSON('$progressUrl', {}, function(data){
                        var progress = data.progress + '%';
                        $('.progress-bar').css('width', progress).attr('aria-valuenow', data.progress).html(progress);
                        if (data.progress >= 100) {
                            isLocked = false;
                            location.reload(true);
                        }
                    });
                }
            }, 2000);
JS
            );
            ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Processing
                </div>
                <div class="panel-body">
                    <div class="progress progress-striped active">
                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Upload JSON data file
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                    <?= $form->field($bulkUploadModel, 'dataFile')
                        ->fileInput([
                            'multiple' => true,
                            'accept' => 'application/json'
                        ]) ?>
                    <button class="btn btn-primary">Submit</button>
                    <?php ActiveForm::end() ?>
                </div>
            </div>

            <?php if ($isUpload) { ?>
                <?php if ($ready) { ?>
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            Ready to start
                        </div>
                        <div class="panel-body">
                            <?= Html::a('Start', 'start', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            Errors
                        </div>
                        <div class="panel-body">
                            <?php echo nl2br(Html::encode($checkErrors)) ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>
