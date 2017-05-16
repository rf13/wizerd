<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$script = <<< JS
    $(document).scroll(function () {
        if($(this).scrollTop() > 50) {
            $("#map").css({
                "position":"fixed",
                'top':"0",

                //"left":"0",
                "z-index":"1010",

                 'paddingBottom': '8px',
                 'borderTop': '10px solid white'
            });
        }
        if($(this).scrollTop() < 50) {
            $("#map").css({
                "position":"relative",
                "z-index":"0",
                'paddingBottom': '35px',
                 'borderTop': 'none'
            });
        }
    });
    $(document).on('click', '.n_name_biz, .business_icon, .n_category_title', function(){
        window.sessionStorage.setItem('history.back', window.location.href);
    });
JS;
$this->registerJs($script);
$start = microtime(true);

if (count($tiers) > 0) {
    $this->registerJsFile("https://api.mapbox.com/mapbox.js/v2.2.3/mapbox.js");
    $this->registerCssFile("https://api.mapbox.com/mapbox.js/v2.2.3/mapbox.css");
    $this->registerCss("body { margin:0; padding:0; }
    #map { position:absolute; top:0; bottom:0; width:100%; }");

    $this->registerJs("
        L.mapbox.accessToken = '" . Yii::$app->params['mapboxApiToken'] . "';
        var map = L.mapbox.map('map', 'mapbox.streets')
        .setView([" . $zip->latitude . ", " . $zip->longitude . "], 11);
        map.touchZoom.disable();
        map.doubleClickZoom.disable();
        map.scrollWheelZoom.disable();
    ");
}
?>
<div class="n_search_result_row searchpage-results">
    <div class="container">
        <div class="row">
            <!--        --><?php //echo '<pre>'; print_r($tiers); echo '</pre>'; ?>
            <div class="col-sm-5 col-xs-12 search_map">
                <div id='map' style="height: 450px; width: 450px"></div>
            </div>
            <div class="col-sm-7 results-display">
                <?php
                $limit = 10;
                $iter = 0;
                $j = 0;

                foreach ($tiers as $tier) {
                    {
                        $iter++;
                        $service = $tier['service'];
                        $category = $tier['category'];
                        $menu = $tier['menu'];
                        $business = $tier['business'];
                        $zipCode = $tier['zipCode'];
                        $city = $tier['city'];
                        $tier_price = $tier['price'];
                        $promo_price = $tier['promoPrice'];
                        $style = '';
                        $this->registerJs("
                            var myLayer = L.mapbox.featureLayer().addTo(map);
                            var geojson = {
                                type: 'Feature',
                                geometry: {
                                    type: 'Point',

                                    coordinates: [
                                      " . $business['longitude'] . ",
                                      " . $business['latitude'] . "
                                    ]
                                },
                                properties: {
                                    title: '" . Html::encode($business['name']) . "',
                                    'marker-size': 'large',
                                    'marker-color': '#92befe',
                                    'marker-symbol': '" . $tier['letter'] . "',
                                },
                            };
                            myLayer.setGeoJSON(geojson);
                            myLayer.on('mouseover', function(e) {
                                e.layer.openPopup();
                            });
                            myLayer.on('mouseout', function(e) {
                                e.layer.closePopup();
                            });
                        ");
                        ?>
                        <div class="row">
                            <div class="col-sm-12 biz-row">
                                <div class="col-sm-12 n_name_biz">
                                    <?= Html::a(Html::encode($business['name']),
                                        Html::encode($business['vanity_name'])) ?>
                                </div>
                                <div class="col-sm-8 col-xs-8">
                                    <address class="n_address">
                                        <?php
                                        if ($business['is_home'] == 0) {
                                            if (isset($business['address'])) {
                                                echo Html::encode($business['address']) . ', ';
                                            }
                                            //echo Html::a($business->zipCode->city->name,$business->zipCode->city->name);
                                            echo $city->name;
                                            if (isset($business['suite'])) {
                                                echo ', Suite ' . Html::encode($business['suite']);
                                            }
                                        } else {
                                            //echo 'city'.Html::a($business->zipCode->city->name,$business->zipCode->city->name);
                                            echo $business->zipCode->city->name;
                                        }
                                        ?>
                                        <br>
                                        <?= Html::encode($business['phone']) ?>
                                    </address>                                                                        
                                    <div class="row search_page_yelpstar">
                                    <?php
                                    $yelpObj = $business->getYelpObject();
                                    if ($yelpObj instanceof stdClass) { ?>
                                    	
                                        <div class="yelp_star_search">
                                        <div class="col-sm-4 col-xs-4">
                                            <a href="http://<?= $business->yelp_url; ?>" target="_blank">
                                                <?php echo $this->renderFile(dirname(__FILE__)
                                                    . '/../business/_yelp_rating.php',
                                                    ['rating' => $yelpObj->rating]) ?>
                                            </a>
                                        </div>
                                        </div>	<!--./yelp_star_search-->   
                                        <div class="search-review-count">
                                        <div class="col-sm-2 col-xs-4">
										<span><?php echo $yelpObj->review_count ?></span>
                                        </div>
                                        </div> <!--./search-review-count-->                                        	
                                            <div class="search_yelp_powered_btn">
                                            <div class="col-sm-5 col-xs-4">
                                            <a href="http://yelp.com/" target="_blank">
                                                <?php echo Html::img('@web/images/yelp_powered_btn_light.png', ['height' => 15]) ?>
                                            </a>
                                        </div>
                                            </div>	<!--./yelp_powered_btn-->											
                                    <?php } ?>
                                </div>	<!-- search_page_yelpstar-->
                                </div>                                
                                <div class="col-sm-4 col-xs-4 text-center promo_price_wrapper">
                                    <?php if ($tier_price): ?>
                                        <p <?= $style; ?>> $<?= $tier_price; ?></p>
                                    <?php endif; ?>
                                    <?php if ($promo_price): ?>
                                        <p style="padding-right:13%; margin:0; color:#A9A9A9;">$<?= $promo_price; ?>
                                            promo</p>
                                    <?php endif; ?>
                                </div>    <!--./promo_price_wrapper-->
                                <div class="col-sm-12 col-xs-12 clearfix">
                                    <?php
                                    echo Html::a(Html::img('@web/images/icon_menu.png'),
                                        Html::encode($business['vanity_name'] . '#menu'), ['class' => 'business_icon']);
                                    ?>
                                    <?php
                                    echo Html::a(Html::img('@web/images/icon_photo.png'),
                                        Html::encode($business['vanity_name'] . '#photo'),
                                        ['class' => 'business_icon']);
                                    ?>
                                    <?php
                                    //                                        if ($business->checkStaff()){
                                    //                                            echo Html::a(Html::img('@web/images/icon_stuff.png'), Html::encode($business['vanity_name'] . '#staff'), ['class' => 'business_icon']);
                                    //                                        }
                                    ?>
                                    <?php
                                    //                                        if ($business['latitude'] > 0){
                                    //                                            echo Html::a(Html::img('@web/images/icon_auto.png'), Html::encode($business['vanity_name']), ['class' => 'business_icon']);
                                    //                                        }
                                    ?>
                                    <?php
                                    //                                        echo Html::a(Html::img('@web/images/icon_marker_pointer.png'), Html::encode($business['vanity_name']), ['class' => 'business_icon']);
                                    echo Html::a('<strong>' . $tier['letter'] . '</strong>',
                                        Html::encode($business['vanity_name']), ['class' => 'business_icon needle']);
                                    ?>
                                </div>
                                <div class="col-sm-12 col-xs-12 catag-service-anchor">
                                    <?php
                                    if (strlen($category->title) > 0) {
                                        $urlTitle = $category->title;
                                    } else {
                                        $urlTitle = $menu->title;
                                    }
                                    $urlTitle .= ' / ' . $service->title;
                                    echo Html::a('<span class="n_category_title">' . Html::encode($urlTitle)
                                        . '</span>', Html::encode($business['vanity_name']) . '#menu');
                                    $rowItems = [];
                                    for ($v = 0; $v < min(5, count($tier['tier']->fieldValues)); $v++) {
                                        $fieldVal = $tier['tier']->fieldValues[$v];
                                        if ($fieldVal->value != '') {
                                            if ($fieldVal->field->visible == 1) {
                                                $item = $fieldVal->field->title . ': ' . $fieldVal->value;
                                            } else {
                                                $item = $fieldVal->value;
                                            }
                                            $rowItems[] = $item;
                                        }
                                    }
                                    if (strlen($category->description) > 0) {
                                        $rowItems[] = $category->description;
                                    }
                                    if (strlen($menu->description) > 0) {
                                        $rowItems[] = $menu->description;
                                    }
                                    echo '<span class="n_box_text">';
                                    if (count($rowItems) > 0) {
                                        $strLen = 170;
                                        $str = Html::encode(implode(', ', $rowItems));
                                        if (strlen($str) > $strLen) {
                                            $str = mb_substr($str, 0, $strLen) . '...';
                                        }
                                        echo ' - ' . $str;
                                    }
                                    echo '</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <?php
                    }
                    $j++;
                }
                ?>
                <?php
                echo LinkPager::widget([
                    'pagination' => $pages,
                    'prevPageLabel' => '&laquo; Previous',
                    'nextPageLabel' => 'Next &raquo;'
                ]);
                ?>
            </div>
        </div>
    </div>    <!--container-->
</div>
