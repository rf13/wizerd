<?php

namespace app\models;

use Imagine\Imagick\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\imagine\Image;


use yii\web\UploadedFile;
use yii\db\Query;

/**
 * This is the model class for table "photo".
 *
 * @property integer $id
 * @property integer $bus_id
 * @property string $url
 * @property string $title
 * @property integer $main
 * @property string $description
 * @property integer $tag_id // for taging photo to menu-service
 * @property integer $sort
 * @property integer $saved
 * @property integer $croped //flag of Crop operation done
 * @property Business $bus
 */
class Photo extends ActiveRecord
{

    const SCENARIO_ADD = 'addNew';
    const SCENARIO_EDIT = 'editOld';

    const MAIN_UPLOADED=2;
    /**
     * @var UploadedFile
     */
    public $imageFile;
    private $_main_path = false;
    private $_small_path = false;
    private $_thumb_path = false;

    public $crop_params = [
        'x' => 0,
        'y' => 0,
        'width' => 0,
        'height' => 0,
        'rotate' => 0
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bus_id'], 'required'],
            [['bus_id', 'main', 'sort', 'saved', 'croped'], 'integer'],
            [['url', 'title'], 'string', 'max' => 255],
            ['description', 'string', 'max' => 2550],

            [
                ['bus_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Business::className(),
                'targetAttribute' => ['bus_id' => 'id']
            ],
            [
                ['imageFile'], 'file',
                'extensions' => 'png, jpg, jpeg',
                'maxFiles' => 1,
                'on' => self::SCENARIO_EDIT
            ],
            [
                ['imageFile'], 'file',
                'skipOnEmpty' => false,
                'extensions' => 'png, jpg, jpeg',
                'maxFiles' => 1,
                'on' => self::SCENARIO_ADD
                //,'checkExtensionByMimeType'=>false
            ],
            ['imageFile', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bus_id' => 'Business',
            'url' => 'URL',
            'title' => 'Title',
            'main' => 'Is main',
            'description' => 'Description',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {

            if ($this->main == 1) {
                $this->sort = 0;
            } else {
                if ($this->sort == 0)
                    //$this->sort = $this->getMaxSort() + 1;
                    $this->sort = 1;
                $this->resortAllUp();
            }


        }


        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $main = Yii::$app->basePath . '/web/uploads/bus' . $this->bus_id . '/photo/' . $this->url;
        $small = Yii::$app->basePath . '/web/uploads/bus' . $this->bus_id . '/small/' . $this->url;
        $smallOrigin = Yii::$app->basePath . '/web/uploads/bus' . $this->bus_id . '/small/origin_' . $this->url;
        $thumb = Yii::$app->basePath . '/web/thumb/bus' . $this->bus_id . '/photo/' . $this->url;
        if (file_exists($main)) unlink($main);
        if (file_exists($small)) {
            unlink($small);
        }
        if (file_exists($smallOrigin)) {
            unlink($smallOrigin);
        }
        if (file_exists($thumb)) {
            unlink($thumb);
        }
        // self::needMain($this->bus_id);
    }

    public function getMaxSort()
    {
        return (new Query())->from(self::tableName())->where(['bus_id' => $this->bus_id])->andWhere('sort>0')->max('sort');
    }

    public function getMinSort()
    {
        return (new Query())->from(self::tableName())->where(['bus_id' => $this->bus_id])->andWhere('sort>0')->min('sort');
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBus()
    {
        return $this->hasOne(Business::className(), ['id' => 'bus_id']);
    }

    /**
     * Finds photo by ID
     *
     * @param  int $id
     * @return null|Photo
     */
    public static function findPhoto($id)
    {
        $id = intval($id);
        return static::findOne($id);
    }

    public static function findPhotoOfBiz($id, $bus_id)
    {
        $id = intval($id);
        return static::find()->where('id=:id and bus_id=:bus_id', ['id' => $id, 'bus_id' => $bus_id])->one();
    }

    /**
     * Finds staff by Business ID
     *
     * @param  int $bus_id
     * @return null|array of Photo
     */
    public static function findByBusId($bus_id)
    {
        return static::find()->where('main<2 and bus_id=:bus_id', ['bus_id' => $bus_id])->orderBy(['sort' => SORT_ASC])->all();
    }

    /**
     * @param int $bus_id
     */
    public static function needMain($bus_id)
    {
        $photos = self::findByBusId($bus_id);
        if (count($photos) == 1) {
            $photo = $photos[0];
            /* @var $photo Photo */
            if (!$photo->main) {
                $photo->makeMain();
            }
        }
    }

    /**
     * @return string
     */
    public function getWebPath()
    {
        // return '@web/web/thumb/bus' . $this->bus_id . '/photo/' . $this->url;
        return '@web/thumb/bus' . $this->bus_id . '/photo/' . $this->url;
    }
    public function getWebPathClear()
    {
        // return '@web/web/thumb/bus' . $this->bus_id . '/photo/' . $this->url;
        return 'thumb/bus' . $this->bus_id . '/photo/' . $this->url;
    }

    /**
     * @return string
     */
    public function getWebPathBigImage()
    {
        // return '@web/web/uploads/bus' . $this->bus_id . '/photo/' . $this->url;
        return '@web/uploads/bus' . $this->bus_id . '/photo/' . $this->url;
    }
    public function getWebPathBigImageClear()
    {
        // return '@web/web/uploads/bus' . $this->bus_id . '/photo/' . $this->url;
        return 'uploads/bus' . $this->bus_id . '/photo/' . $this->url;
    }

    public function getWebPathSmallImage()
    {
        // return '@web/web/uploads/bus' . $this->bus_id . '/photo/' . $this->url;
        return '@web/uploads/bus' . $this->bus_id . '/small/' . $this->url;
    }
    public function getWebPathSmallImageClear()
    {
        // return '@web/web/uploads/bus' . $this->bus_id . '/photo/' . $this->url;
        return 'uploads/bus' . $this->bus_id . '/small/' . $this->url;
    }

    public function getWebPathProfileSmallOriginImage()
    {
        return '@web/uploads/bus' . $this->bus_id . '/small/' . 'origin_' . $this->url;

    }

    /**
     * @return string|bool
     */
    public function getMainPath()
    {
        if (!$this->_main_path) {
            $main_path = Yii::$app->basePath . '/web/uploads/bus' . $this->bus_id . '/photo/';
            if (!is_dir($main_path)) mkdir($main_path, 0777, true);
            $this->_main_path = $main_path;
        }
        return $this->_main_path;
    }

    public function getSmallPath()
    {
        if (!$this->_small_path) {
            $small_path = Yii::$app->basePath . '/web/uploads/bus' . $this->bus_id . '/small/';
            if (!is_dir($small_path)) mkdir($small_path, 0777, true);
            $this->_small_path = $small_path;
        }
        return $this->_small_path;
    }

    /**
     * @return string|bool
     */
    public function getThumbPath()
    {
        if (!$this->_thumb_path) {
            $thumb_path = Yii::$app->basePath . '/web/thumb/bus' . $this->bus_id . '/photo/';
            if (!is_dir($thumb_path)) mkdir($thumb_path, 0777, true);
            $this->_thumb_path = $thumb_path;
        }
        return $this->_thumb_path;
    }

    public function generateProfileThumb()
    {
        $small = $this->getSmallPath();
        $imagine = Image::getImagine();
        $image = $imagine->open($small . 'origin_' . $this->url);

        if (($this->crop_params['width'] != 0) && ($this->crop_params['height'] != 0)) {
            $image->rotate($this->crop_params['rotate'])
                ->crop(new Point($this->crop_params['x'], $this->crop_params['y']), new Box($this->crop_params['width'], $this->crop_params['height']))
                ->save($small . $this->url);
        }

        $crop_height = $height = $image->getSize()->getHeight();
        $crop_width = $width = $image->getSize()->getWidth();

        Image::thumbnail($small . $this->url, $crop_width, $crop_height)
            ->save($this->getThumbPath() . $this->url, ['quality' => 80]);


    }

    public function generateNewThumb()
    {
        $small = $this->getSmallPath();
        $imagine = Image::getImagine();
        $image = $imagine->open($small . $this->url);
        if (($this->crop_params['width'] != 0) && ($this->crop_params['height'] != 0)) {
            $image->rotate($this->crop_params['rotate'])
                ->crop(new Point($this->crop_params['x'], $this->crop_params['y']), new Box($this->crop_params['width'], $this->crop_params['height']))
                ->save($small . $this->url);
        }


        $height = $image->getSize()->getHeight();
        $width = $image->getSize()->getWidth();
        $crop_width = Yii::$app->params['photo_thmb_width'];
        $crop_height = Yii::$app->params['photo_thmb_height'];

        if (($crop_height - $height < 0) || ($crop_width - $width < 0)) {
            if ($height / $crop_height > $width / $crop_width) {
                $crop_width = floor($width / ($height / $crop_height));
            } else if ($height / $crop_height < $width / $crop_width) {
                $crop_height = floor($height / ($width / $crop_width));
            }

        }

        Image::thumbnail($small . $this->url, $crop_width, $crop_height)
            ->save($this->getThumbPath() . $this->url, ['quality' => 80]);


    }

    /**
     * @return string|boolean
     */
    protected function generateThumb($withProfile = false)
    {
        $main = $this->getMainPath();
        do {
            $file = rand(0, 9999) . '_' . time() . '.' . $this->imageFile->extension;
        } while (file_exists($main . $file));
        if (!$this->imageFile->saveAs($main . $file)) {
            return false;
        }
        $imagine = Image::getImagine();
        $image = $imagine->open($main . $file);
        $height = $image->getSize()->getHeight();
        $width = $image->getSize()->getWidth();
        $crop_width = Yii::$app->params['photo_thmb_width'];
        $crop_height = Yii::$app->params['photo_thmb_height'];

        if (($crop_height - $height < 0) || ($crop_width - $width < 0)) {
            if ($height / $crop_height > $width / $crop_width) {
                $crop_width = floor($width / ($height / $crop_height));
            } else if ($height / $crop_height < $width / $crop_width) {
                $crop_height = floor($height / ($width / $crop_width));
            }

        }

        Image::thumbnail($main . $file, $crop_width, $crop_height)
            ->save($this->getThumbPath() . $file, ['quality' => 80]);


        Image::thumbnail($main . $file, 1280, 1024)
            ->save($this->getSmallPath() . $file, ['quality' => 90])
            ->save($this->getSmallPath() . 'origin_' . $file, ['quality' => 100]);


        return $file;
    }

    /**
     * @return boolean whether the photo was saved
     */
    /*
    public function addPhoto()
    {
        if ($this->validate()) {
            $file = $this->generateThumb();
            if (!$file) return false;
            $this->url = $file;
            if (!$this->save(false)) return false;
            if ($this->main) $this->makeMain();
            else self::needMain($this->bus_id);
            // print_r($this);
            return true;
        }
        return false;
    }
*/

    public function resavePhoto()
    {
        if ($this->validate()) {
            $file = $this->generateThumb();
            if (!$file) return false;
            $this->url = $file;

            if (!$this->save(false)) return false;

            return true;
        }
        return false;

    }

    public function addPhoto($withProfile = false)
    {
        if ($this->validate()) {
            $file = $this->generateThumb($withProfile);
            if (!$file) return false;
            $this->url = $file;

            if (!$this->save(false)) return false;

            return true;
        }
        return false;
    }


    public function changeSort($up)
    {
        $sort = $this->sort;
        if ($up) {
            $new_sort = (new Query())->select('MAX(sort) as new_sort')->from('photo')
                ->where(['<', 'sort', $this->sort])
                ->andWhere('bus_id=:bus_id')->addParams([':bus_id' => $this->bus_id]);
        } else {
            $new_sort = (new Query())->select('MIN(sort) as new_sort')->from('photo')
                ->where(['>', 'sort', $this->sort])
                ->andWhere('bus_id=:bus_id')->addParams([':bus_id' => $this->bus_id]);
        }
        /* @var photo photo */
        $photo = Photo::findOne(['bus_id' => $this->bus_id, 'sort' => $new_sort]);
        if (empty($photo)) return false;
        $new_sort = $photo->sort;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $photo->sort = $sort;
            if ($photo->save(false)) {
                $this->sort = $new_sort;
                if ($this->save(false)) {
                    $transaction->commit();
                    return true;
                }
            }
        } catch (Exception $e) {
            $transaction->rollback();
        }
        return false;
    }

    /**
     * Set main photo
     * @return bool
     */
    public function makeMain()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->db->createCommand()
                ->update('photo', ['main' => 0, 'sort' => $this->getMaxSort() + 1], 'bus_id=:bus_id and main=1', [':bus_id' => $this->bus_id])
                ->execute();
            Yii::$app->db->createCommand()
                ->update('photo', ['main' => 1, 'sort' => 0], 'id=:id', [':id' => $this->id])
                ->execute();
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public function resortAllUp()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            static::updateAllCounters(['sort' => 1], 'bus_id=:bus_id and sort>0', [':bus_id' => $this->bus_id]);

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }


    public static function hasUnsaved($bus_id)
    {
        return static::find()->where('bus_id=:bus_id and saved=0', ['bus_id' => $bus_id])->orderBy(['id' => SORT_ASC])->all();

    }

    public static function removeMain($bus_id, $except = -1)
    {
        $curMain = Photo::find()->where('main>0 and main<10 and bus_id=:bus_id and id!=:except', ['bus_id' => $bus_id, 'except' => $except])->all();
        if (count($curMain) > 0) {
            foreach ($curMain as $photo) {
                if ($photo->main == 2) {
                    $photo->delete();
                } else {
                    $photo->main = 0;
                    $photo->sort = $photo->getMaxSort() + 1;
                    $photo->save();
                }


            }
        }
    }

    public static function removePrevUploadNewMain($bus_id, $except = -1)
    {
        $curMain = Photo::find()->where('main=10 and bus_id=:bus_id and id!=:except', ['bus_id' => $bus_id, 'except' => $except])->all();
        if (count($curMain) > 0) {
            foreach ($curMain as $photo) {
                $photo->delete();
            }
        }
    }



}
