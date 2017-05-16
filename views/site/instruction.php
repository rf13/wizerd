<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'How it works';
$script = <<< JS
$(document).ready(function(){	
switch (window.location.pathname) {
    case '/how-it-works':
        $('#main_nav_bar').addClass('tech')
    case '/how-it-works':
    case '/how-it-works':
        $('#main_nav_bar').addClass('tech')
}
});
JS;
$this->registerJs($script);
?>
<div class="how-it-works-wrapper">    
   <div class="jumbotron">
      <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php echo Html::img('@web/images/how-it-works-top-img.png',['class'=>'img-responsive center-block how-it-works-top-img']);?>
      </div>      
    </div> <!--./jumbotron-->   
    <div class="container-fluid how-it-works-business-panel">
    <div class="container">
    <div class="row">	
    <div class="col-md-12"><h2>Business</h2></div>
    </div>	<!--./row-->
    
    <div class="row">	
  <div class="tellus-wrapper padding-wrapper clearfix">
    <div class="col-md-6 col-sm-6">
      <h3>1. Tell us who you are</h3>
      <p>Fill out a couple simple forms so we can get to know you. For example: business name, address, hours of service, phone, etc. Everything you already know :).</p>
    </div>
    <div class="col-md-6 col-sm-6">
      <?php echo Html::img('@web/images/tellus-laptop-img.png',['class'=>'img-responsive center-block']);?>   </div>
  </div>	<!--./tellus-wrapper-->    

  <div class="tellus-servofferings padding-wrapper clearfix">  
    <div class="col-md-6 col-sm-6 hidden-xs visible-lg">
      <?php echo Html::img('@web/images/service-offerings-img.png',['class'=>'img-responsive center-block']);?>     
    </div>
    <div class="col-md-6 col-sm-6">
      <h3>2. Tell us your service offerings</h3>
      <p>Create a menu for all the services your business offers. It's fully customizable to your business needs. The process is crazy simple and takes less than 10 minutes to complete.</p>
    </div>
    <div class="col-md-6 col-sm-6 visible-xs hidden-lg">
       <?php echo Html::img('@web/images/service-offerings-img.png',['class'=>'img-responsive center-block']);?>
    </div>
  </div>

  <div class="tellus-promotions padding-wrapper clearfix">
    <div class="col-md-6 hidden-sm hidden-xs hidden-lg">
     <?php echo Html::img('@web/images/how-it-works-promotions.png',['class'=>'img-responsive']);?>    
      </div>
    <div class="col-md-6 col-sm-6">   
      <h3>3. Run promotions (optional)</h3>
      <p>Send out real-time promotions to attract new customers. They can also be customized to increase sales during slow days, even specific times. Promotions are a breeze and take less than 60 seconds to setup.</p>
    </div>
    <div class="col-md-6">
      <?php echo Html::img('@web/images/how-it-works-promotions.png',['class'=>'img-responsive center-block']);?>    </div>
  </div>	<!--./tellus-promotions-->   

  <div class="tellus-relax padding-wrapper clearfix" style="padding-bottom:100px !important;">  
    <div class="col-md-6 hidden-xs visible-lg">
      <?php echo Html::img('@web/images/how-it-works-relax.png',['class'=>'img-responsive center-block']);?>   
    </div>
    <div class="col-md-6 col-sm-6">
      <h3>4. Relax</h3>
      <p>Wizerd's local search engine takes over. As consumers search, Wizerd will promote your business, services and promotions. This will bring new customers and sales. And the customer is happy becuase they found a great local business!</p>
    </div>
    <div class="col-md-6 visible-xs hidden-lg">
      <?php echo Html::img('@web/images/how-it-works-relax.png',['class'=>'img-responsive center-block']);?>   
    </div>
  </div>
</div>                     
    
    </div>	<!--./container-->
    </div>	<!--./how-it-works-business-panel-->
            
<!--consumer block starts here-->    
<div class="container-fluid how-it-works-business-panel consumer-wrapper" style="border: 1px solid #d9d9d9;">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2>Consumer</h2>
      </div>
    </div>
    <!--./row-->
    
    <div class="row">
      <div class="tellus-wrapper padding-wrapper clearfix">
        <div class="col-md-6 col-sm-6">
          <h3>1. Use Wizerd search engine</h3>
          <p>Look for services in your city (no sign up required). The Wizerd search engine will find and show all local businesses that provide that specific 
          service.</p>
        </div>
        <div class="col-md-6"> <?php echo Html::img('@web/images/wizard-search-vector.png',['class'=>'img-responsive center-block']);?> </div>
      </div>
      <!--./tellus-wrapper-->
      
      <div class="tellus-servofferings padding-wrapper clearfix">
        <div class="col-md-6 hidden-xs visible-lg"> <?php echo Html::img('@web/images/how-itworks-compare.png',['class'=>'img-responsive center-block']);?> </div>
        <div class="col-md-6 col-sm-6">
        <h3>2. Compare (everything)</h3>
        <p>Review all the local businesses from the search engine results. Compare important business information, including a menu of services with descriptions and prices.
        </p>
        </div>
        <div class="col-md-6 visible-xs hidden-lg"> <?php echo Html::img('@web/images/how-itworks-compare.png',['class'=>'img-responsive center-block']);?> </div>
      </div>
      
      <div class="tellus-promotions padding-wrapper clearfix">       
        <div class="col-md-6 col-sm-6">
          <h3>3. Find a promotion</h3>
          <p>Want to try something new, but not sure? How about looking for a promotion to help make the decision. If you find one, click the "Save" icon and a copy is saved in your profile. Simply show this to the merchant upon redemption.</p>
        </div>
        <div class="col-md-6 hidden-xs visible-lg"> <?php echo Html::img('@web/images/compare-promo.png',['class'=>'img-responsive center-block']);?> </div>
        <div class="col-md-6 visible-xs hidden-lg"> <?php echo Html::img('@web/images/compare-promo.png',['class'=>'img-responsive center-block']);?> </div>
      </div>
      <!--./tellus-promotions-->
      
      <div class="tellus-relax padding-wrapper clearfix" style="padding-bottom:100px !important;">
        <div class="col-md-6 hidden-xs visible-lg"> <?php echo Html::img('@web/images/wizard-hire-local-business.png',['class'=>'img-responsive center-block']);?> 
        </div>
        <div class="col-md-6">
          <h3>4. Hire local business</h3>
          <p>Contact the business that meets your needs. Give yourself a pat on the back for all the time and money you just saved...and knowing you're getting a great service at a fair price.</p>
        </div>
        <div class="col-md-6 visible-xs hidden-lg"> <?php echo Html::img('@web/images/wizard-hire-local-business.png',['class'=>'img-responsive center-block']);?> </div>
      </div>
    </div>
    <!--./row--> 
  </div>
  <!--./container--> 
</div>
<!--./how-it-works-business-panel-->
<!--consumer block ends here-->     

<!--signup-now block starts here-->    
<div class="container-fluid how-it-works-business-panel signup-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2>Sign up now</h2>
      </div>
    </div>    
    <div class="row">
    <div class="col-md-12 text-center">
    <h4 style="font-size:18px;">Use of search engine does not require an account.<br>  Consumer only needs an account to save promotions.<br> Business must have an account.  <br>
    <span class="acounts-free">All accounts are Free.</span></h4>
    </div>
    </div>

    <?php echo $this->renderFile(dirname(__FILE__) . '/../user/register.php', ['waitEmail' => $waitEmail,'model'  => $model, 'singlePage' => $singlePage]); ?>
    <!--./row-->              
  </div>
  <!--./container--> 
</div>
<!--./how-it-works-business-panel-->
<!--consumer block ends here-->                
</div>	<!--./how-it-works-wrapper-->
