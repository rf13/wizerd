<?php
use yii\helpers\Html;
?>

<div class="content" id="about">
  <div class="row biz-pofile-aboout-panel">
  <div class="biz-menupage-narrow-container clearfix brd-none">
    <div class="col-sm-12 about_page_title text-center"> <span>About Us</span> </div>
    <div class="row">
    <div class="col-sm-12 about_description">
      <?= nl2br(Html::encode($model->description)); ?>
    </div>
    </div>
  </div>
  </div>  
  <!--./biz-menupage-narrow-container--> 
</div>

