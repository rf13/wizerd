<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Frequently ask questions';
$script = <<< JS
$(document).ready(function(){	
switch (window.location.pathname) {
    case '/support':
        $('#main_nav_bar').addClass('tech')
    case '/support':
    case '/support':
        $('#main_nav_bar').addClass('tech')
}
});
JS;
$this->registerJs($script);
?>
    <div class="site-faq">
    <div class="container">
    <div class="row">
    <div class="col-md-12 about_us_text">
    <h1 class="text-center heading-margins"><?= Html::encode($this->title) ?>
    <?php echo Html::img('@web/images/faq.png',['class'=>'img-responsive center-block mrg-top-per']);?>
    </h1>
    </div>	<!--./about_us_text-->
    </div>    
    </div>
    <div class="container">
    <div class="row">
    <div class="col-md-12">
    <h2 class="mt0">What, Who</h2>
    </div>
    <div class="col-md-12 about_us_text">
    <p class="business-number">1. What is Wizerd?</p>
    <p>Wizerd is a platform that connects local businesses and consumers. The service is 100% free to use. Visit <a href="/how-it-works" class="orng-clr" title="How it works">How it works</a> to learn more.</p>        
    <p class="business-number">2. Who can use it?</p>
    <p>Anyone, businesses and consumers. Anyone can use the search engine without an account. Business must have an account. Consumer's only need an account to save promotions. </p>
    </div>
    
    <div class="col-md-12">
    <h2>How</h2>
    </div>        
    <div class="col-md-12 about_us_text">
    <p class="business-number">1. How much does it cost?</p>
    <p>Wizerd is free for everyone, both businesses and consumers.</p>
    <p class="business-number">2. How do I sign up?</p>
    <p><a href="/sign-up" class="orng-clr" title="sign-up">Click here to sign up</a>. It only takes a second to create an account.</p>
    <p class="business-number">3. How do consumers save promotions?</p>
    <p>Promotions are shown on business sites under the menu section. They can be saved by clicking the star next to service item. </p>
    <p class="business-number">4. How do consumers redeem promotions?</p>
    <p>When visiting the business, just log in to your Wizerd account on your mobile device and show them a copy of the promotion.</p>
    </div>        
    </div>
    <div style="height: 30px;"></div>
    </div>	<!--./row-->                     
    </div>	<!--./site-faq-->