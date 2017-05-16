<?php
/* @var $this yii\web\View */
/* @var $model app\models\Photo */
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJsFile("https://api.mapbox.com/mapbox.js/v2.2.3/mapbox.js");
$this->registerCssFile("https://api.mapbox.com/mapbox.js/v2.2.3/mapbox.css");

//$this->registerJsFile('https://api.mapbox.com/mapbox.js/plugins/mapbox-directions.js/v0.4.0/mapbox.directions.js');
//$this->registerCssFile('https://api.mapbox.com/mapbox.js/plugins/mapbox-directions.js/v0.4.0/mapbox.directions.css');

$this->registerCss("
    body { margin:0; padding:0; }
    #map { position:absolute; bottom:0; width:100%; top: -37px !important; z-index: 99999999;}
");
$business_site = Html::encode($model->getWebsite(true));
$business_site_short = Html::encode($model->getWebsite());
$business_email = Html::encode($model->getEmail(true));
$business_email_short = Html::encode($model->getEmail());

$this->registerJs("
    L.mapbox.accessToken = '" . Yii::$app->params['mapboxApiToken'] . "';

    var map = L.mapbox.map('map', 'mapbox.streets')
        .setView([" . $model->latitude . ", " . $model->longitude . "], 14);

    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();

    L.mapbox.featureLayer({
        // this feature is in the GeoJSON format: see geojson.org
        // for the full specification
        type: 'Feature',
        geometry: {
            type: 'Point',
            // coordinates here are in longitude, latitude order because
            // x, y is the standard for GeoJSON and many formats
            coordinates: [
              " . $model->longitude . ",
              " . $model->latitude . "
            ]
        },
        properties: {
            title: '" . Html::encode($model->name) . "',
            //description: '1718 14th St NW, Washington, DC',
            // one can customize markers by adding simplestyle properties
            // https://www.mapbox.com/guides/an-open-platform/#simplestyle
            'marker-size': 'large',
            'marker-color': '#92befe',
            //'marker-symbol': 'cafe'
        }
    }).addTo(map);

//    $(document).on('click', 'span:contains(Get directions)', function(){
//        var mapDir = L.mapbox.map('map-dir', 'mapbox.streets', {
//            zoomControl: false
//        }).setView([" . $model->latitude . ", " . $model->longitude . "], 14);
//
//        mapDir.attributionControl.setPosition('bottomleft');
//
//        var directions = L.mapbox.directions();
//
//        directions.setOrigin(L.latLng(" . $model->latitude . ", " . $model->longitude . "));
//        directions.setDestination(L.latLng(" . $model->latitude . ", " . $model->longitude . "));
//        directions.query();
//
//        var directionsLayer = L.mapbox.directions.layer(directions)
//            .addTo(mapDir);
//
//        var directionsInputControl = L.mapbox.directions.inputControl('inputs-dir', directions)
//            .addTo(mapDir);
//
//        var directionsErrorsControl = L.mapbox.directions.errorsControl('errors-dir', directions)
//            .addTo(mapDir);
//
//        var directionsRoutesControl = L.mapbox.directions.routesControl('routes-dir', directions)
//            .addTo(mapDir);
//
//        var directionsInstructionsControl = L.mapbox.directions.instructionsControl('instructions-dir', directions)
//            .addTo(mapDir);
//
////        $('div.mapbox-directions-destination div.mapbox-close-icon').click();
//        $('div#map-dir').css('display', 'block');
//    });

if (window.isMobile.any()) {
    var business_site = '$business_site_short';
    var business_email = '$business_email_short';
} else {
    var business_site = '$business_site';
    var business_email = '$business_email';
}
$('#business-site').html(business_site);
$('#business-email').html(business_email);

");
?>
<div class="row content" id="home">
    <div class="buz-page-narrow-container clearfix">
        <div class="row n_business_profile">
            <div class="col-sm-9 n_busi_prof_img clearfix">
                <?php /*?><?php echo Html::img('@web/images/profile-pic.jpg',['class'=>'img-responsive center-block']);?><?php */ ?>
                <?php
                if ($model->checkProfilePhoto()) echo Html::img($model->getMainPhoto()
                    ->getWebPathSmallImage(), [
                    'class' => 'img-responsive',
                    'alt' => Html::encode($model->getMainPhoto()->title)
                ])
                ?>
            </div>
            <div class="col-sm-3 biz-profile-address-block">
                <address class="n_address_business">
                    <div class="street-adrs">
                        <?php
                        if ($model->is_home == 0) {
                            if (isset($model->address)) {
                                echo Html::encode($model->address);
                            }
                            if (isset($model->suite)) {
                                echo ', Suite ' . Html::encode($model->suite);
                            }
                        }
                        ?>
                        <br>
                        <?php //echo Html::a($model->zipCode->city->name,Url::to('/'.strtolower($model->zipCode->city->name))) ?>

                        <?= $model->zipCode->city->name ?>,
                        <?= $model->zipCode->city->state->code ?>
                        <?= $model->zipCode->zip ?>
                    </div>
                    <div class="open-times">
                        <?php
                        if ($model->checkOperations()) {
                            if ($model->operations[date('N') - 1]->active == 0) {
                                echo 'Open today ' . date('g:i a', strtotime($model->operations[date('N') - 1]->open))
                                    . ' - ' . date('g:i a', strtotime($model->operations[date('N') - 1]->end));
                            } else {
                                echo 'Today is day Off';
                            }
                        }
                        ?>
                    </div>

                    <div class="row review-star-pad-btm">
                        <?php
                        $yelpObj = $model->getYelpObject();
                        if ($yelpObj instanceof stdClass): ?>
                        	<div class="business-page-yelpstar">
                            <div class="col-sm-6">
                                <a href="http://<?= $model['yelp_url']; ?>" target="_blank">
                                    <?php echo $this->render('_yelp_rating', ['rating' => $yelpObj->rating]) ?>
                                </a>
                            </div>
                            </div>	<!--./business-page-yelpstar-->
                            
                            <div class="business-page-search-review-count">
                            <div class="col-sm-2">
                                <span><?php echo $yelpObj->review_count ?></span>
                            </div>
                            </div>	<!--./business-page-search-review-count-->
                            
                            <div class="business_search_yelp_powered_btn">                                                       
                            <div class="col-sm-4">
                                <a href="http://yelp.com/" target="_blank">
                                    <?php echo Html::img('@web/images/yelp_powered_btn_light.png', ['height' => 15]) ?>
                                </a>
                            </div>
                             </div>	<!--./business_search_yelp_powered_btn-->
                        <?php endif; ?>
                    </div>

                    <?php /*?><div class="row col-sm-12 review-star-pad-btm">
                    <?=Html::img('@web/images/test-start-profilepage.gif',['class'=>'business_icon'])?>
                    </div><?php */ ?>

                    <div class="row col-sm-12 time">
                        <?= Html::img('@web/images/icon_phone.png', ['class' => 'business_icon']) ?>
                        <?= Html::encode($model->phone); ?>
                    </div>

                    <?php if (strlen(trim($model->website)) > 0): ?>
                        <div class="row col-sm-12 time">
                            <a href="http://<?= Html::encode($model->website) ?>">
                                <?= Html::img('@web/images/icon_web_site.png', ['class' => 'business_icon']) ?>
                                <span id="business-site"></span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (strlen(trim($model->contact_email)) > 0): ?>
                        <div class="row col-sm-12 time">
                            <a href="mailto:<?= Html::encode($model->contact_email) ?>">
                                <?= Html::img('@web/images/icon_email.png', ['class' => 'business_icon']) ?>
                                <span id="business-email"></span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!--                    <div class="row col-sm-12 time">-->
                    <?php //echo Html::img('@web/images/icon_auto.png',['class'=>'business_icon']); ?>
                    <!--                            <span>Get directions</span>-->
                    <!--                    </div>-->
                </address>
            </div>
        </div>

        <!--        <div class="row" style="position: relative; margin-bottom: 40px; display: none;">-->
        <!--            <div id="map-dir" style="height: 500px;position: relative;display: none;">-->
        <!--            <div id="inputs-dir"></div>-->
        <!--            <div id="errors-dir"></div>-->
        <!--            <div id="directions-dir">-->
        <!--                <div id="routes-dir"></div>-->
        <!--                <div id="instructions-dir"></div>-->
        <!--            </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <style>
            #inputs-dir,
            #errors-dir,
            #directions-dir {
                position: absolute;
                width: 33.3333%;
                max-width: 300px;
                min-width: 200px;
            }

            #inputs-dir {
                z-index: 10;
                top: 10px;
                left: 10px;
            }

            #directions-dir {
                z-index: 99;
                background: rgba(0, 0, 0, .8);
                top: 0;
                right: 0;
                bottom: 0;
                overflow: auto;
            }

            #errors-dir {
                z-index: 8;
                opacity: 0;
                padding: 10px;
                border-radius: 0 0 3px 3px;
                background: rgba(0, 0, 0, .25);
                top: 90px;
                left: 10px;
            }

        </style>

        <div class="row biz-about-wrapper">
            <div class="biz-about-header clearfix">
                <div class="col-md-8 mobile_none">About Us <span class="hidden-lg">/ Hours</span></div>
                <div class="col-md-4 n_business_p">Hours</div>
            </div> <!--./biz-about-header-->
            <div class="col-sm-4 n_business_about">
                <div class="col-sm-12 n_business_about_text">
                    <?= nl2br(Html::encode(substr($model->description, 0, 290) . ((strlen($model->description) > 290)
                            ? "..."
                            : ""))); ?>
                </div>
                <div class="col-sm-12 more">
                    <?= Html::a('More', '#about') ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div id='map' style="height:240px;"></div>
            </div>
            <div class="col-sm-4">
                <div class="n_business_days">
                    <?php
                    if ($model->checkOperations()) {
                        $days = [
                            'Monday',
                            'Tuesday',
                            'Wednesday',
                            'Thursday',
                            'Friday',
                            'Saturday',
                            'Sunday'
                        ];
                        foreach ($model->operations as $operation) {
                            ?>
                            <div class="col-sm-4 col-xs-4"><?= $days[$operation->day] ?></div>
                            <div class="col-sm-8 operation-open">
                                <?php
                                if ($operation->active == 0) {
                                    echo date('g:i a', strtotime($operation->open)) . ' - ' . date('g:i a',
                                            strtotime($operation->end));
                                } else {
                                    echo '<p class="operations-closed">Closed</p>';
                                }
                                ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
