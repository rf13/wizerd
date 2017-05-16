<?php

namespace app\models;

use Yii;
use app\components\ActiveRecord;
use yii\db\Query;
use yii\bootstrap\Html;
use yii\console\Exception;

/**
 * This is the model class for table "business".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $address
 * @property string $suite
 * @property string $phone
 * @property string $website
 * @property string $contact_email
 * @property string $description
 * @property integer $zip_id
 * @property string $zip_notice
 * @property integer $ind_id
 * @property integer $is_home
 * @property string $yelp_url
 * @property string $latitude
 * @property string $longitude
 * @property string $vanity_name
 * @property integer vanity_changed
 * @property integer parent_bus_id
 *
 * @property Industry $mainInd
 * @property User $user
 * @property ZipCode $zip
 * @property Menu[] $menus
 * @property Operation[] $operations
 * @property Photo[] $photos
 * @property Staff[] $staff
 * @property Video[] $videos
 */
class Business extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['user_id'],
                'required'
            ],
            [
                [
                    'user_id',
                    'zip_id',
                    'is_home',
                    'vanity_changed',
                    'parent_bus_id',
                    'ind_id'
                ],
                'integer'
            ],
            [
                ['description'],
                'string'
            ],
            [
                [
                    'name',
                    'address',
                    'suite',
                    'phone',
                    'website',
                    'contact_email',
                    'zip_notice',
                    'vanity_name'
                ],
                'string',
                'max' => 255
            ],
            [
                'yelp_url',
                'url'
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],
            [
                ['zip_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ZipCode::className(),
                'targetAttribute' => ['zip_id' => 'id']
            ],
            [
                'ind_id',
                'safe'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'name' => 'Name',
            'address' => 'Address',
            'suite' => 'Suite',
            'phone' => 'Phone',
            'website' => 'Website',
            'contact_email' => 'Contact email',
            'description' => 'Description',
            'zip_id' => 'Zip code',
            'zip_notice' => 'Zip Notice',
            'is_home' => 'Home based business',
            'yelp_url' => 'Yelp profile URL example',
            'latitude' => 'Business Latitude',
            'longitude' => 'Business Longitude',
            'vanity_name' => 'Vanity Name',
            'vanity_changed' => 'Vanity name Changed',
            'parent_bus_id' => 'Parent Business'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->website = str_replace('http://', '', $this->website);
            if (isset($this->yelp_url)) {
                $this->yelp_url = str_replace('http://', '', $this->yelp_url);
            }

            return true;
        }

        return false;
    }

    public function beforeDelete()
    {
        $this->deleteFolders();

        return parent::beforeDelete();
    }

    protected function deleteFolders()
    {
        $list = [
            Yii::$app->basePath . '/web/uploads/bus' . $this->id,
            Yii::$app->basePath . '/web/thumb/bus' . $this->id,
        ];
        array_walk($list, function ($dir) {
            if (file_exists($dir)) {
                $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($files as $file) {
                    if ($file->isDir()) {
                        rmdir($file->getRealPath());
                    } else {
                        unlink($file->getRealPath());
                    }
                }
                rmdir($dir);
            }
        });
    }

    /**
     * Finds business by User id
     *
     * @param  string $user_id
     * @return null|Business
     */
    public static function findByUserId($user_id)
    {
        return static::findOne(['user_id' => $user_id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustry()
    {
        return $this->hasOne(Industry::className(), ['id' => 'ind_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZipCode()
    {
        return $this->hasOne(ZipCode::className(), ['id' => 'zip_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'zip_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromo()
    {
        return $this->hasMany(Promo::className(), ['bus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromoMenu()
    {
//        return $this->hasMany(CustomCategory::className(), ['id' => 'cat_id'])
//            ->viaTable(Promo::tableName(), ['cat_id' => 'id'])
//            ->viaTable(PromoGroup::tableName(), ['promo_id' => 'id']);
        return $this->getPromo()
            ->where('active=1 and promo.start <= date(now()) and promo.end >= date(now())')
            ->with('category');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['bus_id' => 'id'])
            ->alias('menus')
            ->orderBy(['menus.sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperations()
    {
        return $this->hasMany(Operation::className(), ['bus_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['bus_id' => 'id'])
            ->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffs()
    {
        return $this->hasMany(Staff::className(), ['bus_id' => 'id'])
            ->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['bus_id' => 'id']);
    }

    /**
     * @return null|ZipCode
     */
    public function getCurrentZipCode()
    {
        if ($this->zip_id) {
            return ZipCode::findOne(['id' => $this->zip_id]);
        } else {
            if ($this->zip_notice) {
                return ZipCode::findOne(['id' => $this->zip_notice]);
            }
        }

        return null;
    }

    /**
     * @return array|null
     */
    public function getAccountComplete()
    {
        $hourQuery = (new Query())->select('COUNT(*)')
            ->from('operation')
            ->where(['bus_id' => $this->id]);
        $menuQuery = (new Query())->select('COUNT(*)')
            ->from('menu')
            ->where(['bus_id' => $this->id]);
        $photoQuery = (new Query())->select('COUNT(*)')
            ->from('photo')
            ->where(['bus_id' => $this->id])
            ->andWhere('main>0 and main<=2');
//        $staffQuery = (new Query())->select('COUNT(*)')
//            ->from('staff')
//            ->where(['bus_id' => $this->id]);
        $photoDifQuery = (new Query())->select('COUNT(*)')
            ->from('photo')
            ->where(['bus_id' => $this->id])
            ->andWhere('main=0');

        return (new Query())->select([
            'phone',
            'yelp_url',
            'vanity_name',
            'operation' => $hourQuery,
            'menu' => $menuQuery,
            'photo' => $photoQuery,
            //            'staff' => $staffQuery,
            'photo_dif' => $photoDifQuery,
        ])
            ->from('business')
            ->where('id=:bus_id')
            ->addParams([':bus_id' => $this->id])
            ->one();
    }


    public function isFilled()
    {
        $query = (new Query())->select([
            'max(if ((business.name is not null
                            and business.name!=""
                            and business.address is not null
                            and business.address!=""
                            and business.phone is not null
                            and business.phone !=""
                            and business.vanity_name is not null
                            and business.vanity_name!=""
                            and business.description is not null
                            and business.description!=""
                            and (select count(id) from operation where bus_id=business.id)=7
                            and (select count(id) from photo where bus_id=business.id and main>0 and main<=2)>0
                            and (select count(id) from menu where bus_id=business.id)>0
                            ),1,0))'
        ])
            ->from('business')
            ->leftJoin('menu', 'menu.bus_id=business.id')
            ->where('business.id=:id', ['id' => $this->id])
            ->scalar();

        return $query;
    }

    /**
     * @return null|array
     */
    public function getIndustries()
    {
        $sub = (new Query())->select('ind_id')
            ->from(Menu::tableName())
            ->where('bus_id=:bus_id')
            ->addParams([':bus_id' => $this->id]);

        return Industry::find()
            ->select([
                'id',
                'title'
            ])
            ->where([
                'in',
                'id',
                $sub
            ])
            ->asArray()
            ->all();
    }

    /**
     * @return null|array
     */
    public function getFreeIndustries()
    {
        $sub = (new Query())->select('ind_id')
            ->from(Menu::tableName())
            ->where('bus_id=:bus_id')
            ->addParams([':bus_id' => $this->id]);

        return Industry::find()
            ->select([
                'id',
                'title'
            ])
            ->where([
                'not in',
                'id',
                $sub
            ])
            ->asArray()
            ->all();
    }

    /**
     * Getting All menus of current Business
     *
     * @return null|array
     */
    public function getAddedMenus()
    {
        return (new Query())->select([
            'menu.id AS id',
            'menu.title as title'
        ])
            ->from(Menu::tableName())
            ->where('bus_id=:bus_id')
            ->addParams([':bus_id' => $this->id])
            ->all();
    }

    /**
     * @return array
     */
    public function getMenuForPromo()
    {
        $menus = $this->getAddedMenu();
        $menus = $menus->menus;
        $options = [];
        foreach ($menus as $menu) {
            if ($menu->countFilledTiers() > 0) {
                $categories = $menu->categories;
                foreach ($categories as $cat) {
                    if ($cat->countFilledTiers() > 0) {
                        $options[$menu->title][$cat->id] = $cat->title . ' services';
                    }
                }
            }
        }

        return $options;
    }

    /**
     * @return null|mixed

    public function getAddedMenu() {
     * return static::find()->with('menus.industry')->with('menus.categories.services')
     * ->where(['id' => $this->id])->one();
     * }
     */
    public function getAddedMenu()
    {
        return static::find()
            ->with('menus.categories.services')
            ->where(['id' => $this->id])
            ->one();
    }

    /**
     * returning the current menu by session parameter
     * @return type
     */
    public function getCurrentMenu()
    {
        return static::find()
            ->with('menus.industry')
            ->with('menus.categories.services')
            ->where(['id' => Yii::$app->session->get('current-menu')])
            ->one();
    }


    /**
     * @return bool (Need one minimum)
     */
    public function checkMenus()
    {
        $result = (new Query())->select('count(*)')
            ->from('business')
            ->leftJoin('menu', 'menu.bus_id=business.id')
            ->where(['business.id' => $this->id])
            ->scalar();

        /*$result = Business::find()->joinWith('menus', false, 'INNER JOIN')
                ->where(['business.id' => $this->id])
                ->count();*/

        return $result > 0;
    }

    /**
     *  Check is All fields, That need to get in search are filled
     *
     */
    /*
    public function isFilled()
    {
        $query = (new Query())
            ->select(['max(if ((business.name is not null
                            and business.address is not null
                            and business.phone is not null 
                            and business.description is not null
                            and (select count(id) from operation where bus_id=business.id)=7
                            and (select main from photo where bus_id=business.id) is not null
                            and ((custom_service.type>0) and (custom_service.fill_status=1))
                            ),1,0))'])
            ->from('business')
            ->leftJoin('menu', 'menu.bus_id=business.id')
            ->leftJoin('custom_category', 'custom_category.menu_id=menu.id')
            ->leftJoin('custom_service', 'custom_service.cat_id=custom_category.id')
            ->where('business.id=:id', ['id' => $this->id])
            ->scalar();
        return $query;
    }
    */
    /**
     * @return bool
     */
    public function checkOperations()
    {
        $result = Business::find()
            ->joinWith('operations', false, 'INNER JOIN')
            ->where(['business.id' => $this->id])
            ->count();

        return $result > 0;
    }

    /**
     * @return bool (Need one minimum)
     */
    public function checkPhotos()
    {
        $result = Business::find()
            ->joinWith('photos', false, 'INNER JOIN')
            ->where(['business.id' => $this->id])
            ->count();

        return $result > 0;
    }

    public function checkProfilePhoto()
    {
        $result = Business::find()
            ->joinWith('photos', false, 'INNER JOIN')
            ->where('business.id=:bus_id and main>0 and main<10 and saved=1 and croped=1', ['bus_id' => $this->id])
            ->count();

        return $result > 0;
    }

    public function checkStaff()
    {
        $result = Business::find()
            ->joinWith('staffs', false, 'INNER JOIN')
            ->where(['business.id' => $this->id])
            ->count();

        return $result > 0;
    }

    public static function createVanityName($name)
    {
        $str = preg_replace('/[^0-9A-Za-z\s-]/', '', strtolower($name));
        $str = preg_replace('/(\s)/', '-', $str);

        return $str;
    }

    public static function searchByVanityName($name)
    {
        // echo "=".$name."=";
        // return false;
        return self::find()
            ->where('vanity_name=:name', ['name' => $name])
            ->one();
    }


    public static function searchByZipcode($zip)
    {
        $zip = ZipCode::find()
            ->where('zip = :zip ')
            ->addParams([':zip' => $zip])
            ->one();
        if ($zip !== null) {
            $latitude = $zip->latitude;
            $longitude = $zip->longitude;
            $result = Business::find()
                ->where("
                  	business.name is not null
                  and business.address is not null
                  and business.phone is not null
                  and business.description is not null
                  and business.vanity_name is not null
                  and (select count(id) from operation where bus_id=business.id)=7
                  and (select count(main) from photo where bus_id=business.id and main>0 and main<=2)>0
                  and (select count(id) from menu where bus_id=business.id)>0
                ")
                ->andWhere('sqrt((business.latitude- :f_latitude)*(business.latitude- :f_latitude)+(business.longitude- :f_longitude)*(business.longitude- :f_longitude))<:delta',
                    [
                        'f_latitude' => $latitude,
                        'f_longitude' => $longitude,
                        'delta' => ZipCode::BASE_DIST_DELTA
                    ])
                ->all();


            return $result;
        }

        return false;
    }

    public function getMainPhoto()
    {
        /*
        $photo = Photo::find()
                ->where('bus_id=:bus_id', ['bus_id' => $this->id])
                ->andWhere('main=1')
                ->one();
        return $photo;
        */
        $photo = Photo::find()
            ->where('bus_id=:bus_id and main>0 and main<10 and saved=1 and croped=1', ['bus_id' => $this->id])
            ->one();

        return $photo;
    }

    /**
     *  Returning true if bisiness have Active Promos
     * @return boolean
     */
    public function haveActivePromos()
    {
        $promos = Promo::find()
            ->select(['count(*)'])
            ->where('bus_id=:bus_id and active=:active and promo.start <= date(now()) and promo.end >= date(now())', [
                'bus_id' => $this->id,
                'active' => Promo::STATUS_ACTIVE
            ])
            ->scalar();
        if ($promos > 0) {
            return true;
        }

        return false;
    }

    public function getGeoByAddress()
    {
        //33.45641327, -86.80190277
        $addr = str_replace(' ', '+', $this->address);
        $addr .= "+" . $this->zipCode->city->name . "+" . $this->zipCode->city->state->code;
        $place_la = round($this->zipCode->latitude, 1);
        $place_lo = round($this->zipCode->longitude, 1);
        $link = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' . Html::encode($addr) . '.json?proximity='
            . $place_lo . ',' . $place_la . '&access_token=' . Yii::$app->params['mapboxApiToken'];
        $cont = [];

        try {

            $ch = curl_init($link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $data = curl_exec($ch);
            curl_close($ch);

            if ($data) {
                $cont = \GuzzleHttp\json_decode($data);
            } else {
                $cont = [];
            }
        } catch (Exception $ex) {
            $cont = [];
        }


        if (count($cont) > 0) {

            if ($cont->features[0]->relevance > 0.95) { // we meah that it is correct place
                $this->latitude = $cont->features[0]->center[1];
                $this->longitude = $cont->features[0]->center[0];
            } else {
                $this->latitude = $this->zipCode->latitude;
                $this->longitude = $this->zipCode->longitude;
            }
        } else {
            $this->latitude = $this->zipCode->latitude;
            $this->longitude = $this->zipCode->longitude;
        }


        return true;
    }

    public function getYelpObject()
    {

        if (isset($this->yelp_url)) {
            try {
                //$business_id = 'le-petit-paris-los-angeles-3';
                $business_id = substr($this->yelp_url,
                    strpos($this->yelp_url, 'yelp.com/biz/') + strlen('yelp.com/biz/'));

                $unsigned_url = Yii::$app->params['yelp_api_url'] . Yii::$app->params['yelp_biz_path'] . $business_id;

                // Token object built using the OAuth library
                $token = new \OAuthToken(Yii::$app->params['yelp_token'], Yii::$app->params['yelp_token_secret']);

                // Consumer object built using the OAuth library
                $consumer = new \OAuthConsumer(Yii::$app->params['yelp_consume_key'],
                    Yii::$app->params['yelp_consume_secret']);

                // Yelp uses HMAC SHA1 encoding
                $signature_method = new \OAuthSignatureMethod_HMAC_SHA1();

                $oauthrequest = \OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);

                // Sign the request
                $oauthrequest->sign_request($signature_method, $consumer, $token);

                // Get the signed URL
                $signed_url = $oauthrequest->to_url();

                // Send Yelp API Call
                $ch = curl_init($signed_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $data = curl_exec($ch);
                curl_close($ch);
                $r = json_decode($data);
                if (!isset($r->rating) || !isset($r->review_count)) {
                    return false;
                }

                return $r;
            } catch (Exception $exc) {
                return false;
            }
        }

        return false;
    }

    public function makeBusinessLink()
    {
        return Yii::$app->urlManager->createAbsoluteUrl('') . $this->vanity_name;
    }

    public function isContractor()
    {
        if ($this->parent_bus_id > 0) {
            return true;
        } else {
            return false;
        }

    }

    public static function getBusinessesByUrlOrAddress($url = null, $zip = null, $address = null)
    {

        $business = [];
        if ($url !== "") {
            $array = explode('/', $url);
            $name = $array[count($array) - 1];
            $byVanity = static::searchByVanityName($name);
            if ($byVanity !== null) {
                $business[] = $byVanity;
            }
        }
        if ((count($business) == 0) && ((strlen(trim($zip)) == 5) && (strlen(trim($address)) > 3))) {
            $business = static::searchAsAgency($zip, $address);
        }

        return $business;
    }

    public static function searchAsAgency($zip, $address)
    {

        $zip = (int)trim($zip);
        $address = trim(str_replace("  ", ' ', $address));
        $result = [];
        $business = static::find()
            ->leftJoin('zip_code', 'zip_code.id=business.zip_id')
            ->where('zip_code.zip=:zip', ['zip' => $zip])
            ->andWhere('parent_bus_id=0')
            ->andWhere('is_home=0')
            ->andFilterWhere([
                'LIKE',
                'business.address',
                $address
            ])
            ->limit(20)
            ->all();

        foreach ($business as $biz) {
            if ($biz->isFilled()) {
                $result[] = $biz;
            }
        }

        return $result;
    }

    public function getAgency()
    {
        return static::find()
            ->where('id=:id', ['id' => $this->parent_bus_id])
            ->one();
    }

    public function makeAddressForDropdown()
    {
        return $this->name . " " . $this->address . ', ' . $this->zipCode->city->name . ', '
        . $this->zipCode->city->state->code . ' ' . $this->zipCode->zip;
    }

    public function hasVanityName()
    {

        if ($this->vanity_name) {
            return true;
        }

        return false;
    }

    /**
     * returning all Photos by Biz
     * that can be displayed( except Uploaded Main Photo)
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPhotoForShow()
    {
        return Photo::find()
            ->where('bus_id=:bus_id', ['bus_id' => $this->id])
            ->andWhere('main!=:main_uploaded', ['main_uploaded' => Photo::MAIN_UPLOADED])
            ->orderBy(['sort' => 'SORT_ASC'])
            ->all();


    }

    public function deletePhotos()
    {
        foreach ($this->getPhotos()
            ->all() as $photo
        ) {
            $photo->delete();
        }
    }

    public function getWebsite($crop = false)
    {
        $value = $this->website;
        if ($crop) {
            $value = $this->crop($value);
        }

        return $value;
    }

    public function getEmail($crop = false)
    {
        $value = $this->contact_email;
        if ($crop) {
            $value = $this->crop($value);
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id',
            'name',
            'address',
            'suite',
            'phone',
            'website',
            'contact_email',
            'description',
            'is_home',
            'yelp_url',
            'latitude',
            'longitude',
            'vanity_name',
        ];
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'menus',
            'promos' => function ($model) {
                $result = $model->getPromoMenu()
                    ->joinWith('category.services.tiers')
                    ->all();
                return $result;
            }
        ];
    }
}
