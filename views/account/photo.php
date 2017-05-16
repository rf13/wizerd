<?php
/* @var $this yii\web\View */
/* @var $photos null|array */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Photo';

$this->registerJsFile('/js/account_photo.js');
?>
<div class="business_photo">
    <div class="form-group">
        <div class="btn-group">
            <?= Html::button('+ Add photo', [
                'title' => 'Add photo',
                'class' => ' btn btn-warning',
                'onclick' => 'photo_add_photo_click();'
            ]); ?>
        </div>
        <div class="btn-group pull-right">
            <?= Html::button('Tip', ['class' => 'btn btn-default btn-tip-show',
                                     'onclick' => 'for_setup_tip()'
            ]); ?>
            <?= Html::button('Hide tip', ['class' => 'btn btn-default btn-tip-hide hidden',
                                          'onclick' => 'for_setup_tip()'

            ]); ?>
        </div>
    </div>
    <div class="panel panel-default panel-tip">
        <div class="panel-body">

            <p>Tip: Adding photos</p>

            <p>
                Add photos that highlight your business and services.
                Customers love to see what they are buying.
                The “+ Add” button is located in the top left.
            </p>

            <p>
                We encourage you to add titles and descriptions as this significantly helps to turn “visitors” into “customers”!
                This also helps to rank your Wizerd site in popular search engines…meaning more customers.
            </p>

            <p>To crop your photo, select the link while in edit mode.</p>
            <div class="pull-right">
            </div>
        </div>
    </div>
    <div class="panel panel-success n_panel_success">
        <?php
        /*

        <div class="panel-heading">
          <?= $this->title?>
        </div>
        */
        ?>
        <div class="panel-body gray-content n_div_content">


            <?= Html::a('Photo',
                Url::toRoute('account/photo-manage'), [
                    'id'=>'get_all_photo',
                    'class' => 'n_a_photo active',
                    'onclick' => 'open_photos(this);return false;'
                ]
            ) ?>
            <?= Html::a('Profile photo',
                Url::toRoute('account/photo-profile') ,
                [
                    'id'=>'get_profile_photo',
                    'class' => 'n_a_profile',
                    'onclick' => 'open_profile_photo(this);return false;'
                ]
            ) ?>

            <div id="photo_manage">
                <?php
                $view='photo';

                if(Yii::$app->session->has('photo')){
                    $view=Yii::$app->session->get('photo');
                    Yii::$app->session->remove('photo');
                }
                if($view=='photo') {
                    echo $this->render('photo/_manage', ['photos' => $photos, 'model' => $model, 'new_model' => $new_model, 'unsaved' => $unsaved]);
                }
                else{
                    $this->registerJs("$('#get_profile_photo').click()");
                }

                ?>
            </div>
        </div>
    </div>
</div>