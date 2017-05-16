<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Business support';
$script = <<< JS
$(document).ready(function(){	
switch (window.location.pathname) {
    case '/business-support':
        $('#main_nav_bar').addClass('tech')
    case '/business-support':
    case '/business-support':
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
<h1 class="text-center heading-margins"><?= Html::encode($this->title) ?></h1>
</div>	<!--./about_us_text-->
</div>    
</div>

<div class="container">	
<div class="row">	
<div class="col-md-12 about_us_text">	
<h2 class="mt0">Account creation</h2>
<p class="business-number">1. How do I create a Wizerd profile?</p>
<p>To create a profile you need to fill out the different sections located in each tab. For example: Menu, Photo, Profile. Note, not all sections are mandatory to be filled out. Look at the <a href="/user/account?active=setup" class="orng-clr" title="account-setup">Account setup</a> tab to see what is mandatory versus recommended.</p>

<p class="business-number">2. How do I fill out the different sections?</p>
<p>In each section there is a "Tip" button located in the far right hand corner. Click this button to get tip information on how to fill out the section.</p>

<p class="business-number">3. My Wizerd site is not showing up to consumers on the search engine?</p>
<p>All mandatory sections must be filled out before your profile can be shown in the search engine. Visit the <a href="/user/account?active=setup"  class="orng-clr" title="account-setup">Account setup</a> tab to see see the mandatory requirements to make your profile live to consumers.</p>

<p class="business-number">4. What is a contractor, as shown in the <a href="/user/account?active=profile_public" class="orng-clr" title="public-profile">Public profile</a> tab?</p>
<p>Contractors are people that run their own business, but also partner with another business. For example, a hair stylist that rents space at another salon. The hair stylist could be responsible for setting their own prices, managing their own schedule, etc. Contractors manage and own their Wizerd profile. The purpose of this is so contractors can change business partners in the future, but not lose all of their work and profile information. It also allows contractors to promote their services independent of the brick and mortar store they partner with.</p>
</div>
</div>
</div>	<!--./container-->

<div style="height:30px;"></div>

<div class="container">
<div class="row">
<div class="col-md-12 about_us_text">	
<h2 class="mt0">How it works</h2>
<p class="business-number">1. What should I do after I create my Wizerd profile?</p>
<p>After your profile is complete it will automatically show up to consumers in the search engine.</p>

<p class="business-number">2. How can I promote my services?</p>
<p>You can promote your services by posting your personalized Wizerd URL on your social media accounts. Find your Wizerd URL by clicking the <a href="/user/account?active=site" class="orng-clr" title="account-settings">Wizerd site</a> tab, which is located under the Profile tab.</p>

<p class="business-number">3. My promotion is not showing up on my Wizerd site or the search engine?</p>
<p style="margin-bottom: 15px;">After you create and save a promotion it must be "Published" before it will be displayed. Click the "Publish" button to activate the promo. Then the status will be changed to "Active".</p>
<p>Promotions are displayed only during the valid dates. This means future dated promos won't be shown until the valid date is reached.</p>
</div>	<!--./about_us_text-->
</div>	<!--./row-->
</div>	<!--./container-->        
</div>