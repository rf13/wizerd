<?php
/* @var $this yii\web\View */
/* @var $model app\models\Business */
use yii\helpers\Html;

$photoForShow=$model->getPhotoForShow();
if (count($photoForShow)>0){
    $this->registerJsFile('/js/photo/unitegallery.min.js',['depends'=> 'yii\web\YiiAsset']);
    $this->registerJsFile('/js/photo/ug-theme-tilesgrid.js',['depends'=> 'yii\web\YiiAsset']);
    $this->registerCssFile('/css/photo/unite-gallery.css');
    $script=<<<JS
    $("#gallery").unitegallery({
				tiles_type:"justified",
				tile_width: 250,
				tile_height: 250
	});
JS;

    $this->registerJs($script,\yii\web\View::POS_READY);
}
?>
<div class="row content" id="photo">
    <div class="container">
            <div class="row">
			<div class="col-sm-12 about_page_title text-center"> <span>Photo</span> </div>
            </div>
        </div>
    <div class="container photo-gallery">
        <div id="gallery" style="display:none;">
            <?php
            for($i=0;$i<count($photoForShow);$i++)
            {
                echo Html::img($photoForShow[$i]->getWebPathClear(),[
                    'data-image'=>$photoForShow[$i]->getWebPathSmallImageClear(),
                    'data-description'=>Html::encode($photoForShow[$i]->title),
                    'alt'=>Html::encode($photoForShow[$i]->title)."<br />".Html::encode($photoForShow[$i]->description),
                    'style'=>'display: none',
                ]);
            }
            ?>
        </div>
    </div>
</div>