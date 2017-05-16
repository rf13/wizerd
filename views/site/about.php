<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
$this->title = 'About us';
$script = <<< JS
$(document).ready(function(){	
switch (window.location.pathname) {
    case '/about':
        $('#main_nav_bar').addClass('tech')
    case '/about':
    case '/about':
        $('#main_nav_bar').addClass('tech')
}
});
JS;
$this->registerJs($script);
?>
<div class="site-about">    
    <div class="container">
        <div class="row">
            <div class="col-md-12 about_us_text">
            <h1 class="text-center heading-margins"><?= Html::encode($this->title) ?>           
            <?php echo Html::img('@web/images/about_map_icon.png',['class'=>'img-responsive center-block mrg-top']);?>
            </h1>     
            <div class="about_content_panel">                                        
            <p>
            Wizerd is 100% dedicated to your local community. We are building products for local businesses and consumers. Our technology enhances the user experience, making everyone happy :).
            </p>  
            </div>            
            </div>
        </div>
    </div>

</div>