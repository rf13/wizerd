<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Query;
use yii\imagine\Image;
use yii\web\UploadedFile;
use Imagine\Imagick\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * This is the model class for table "staff".
 *
 * @property integer $id
 * @property integer $bus_id
 * @property string $name
 * @property string $role
 * @property string $description
 * @property string $url
 * @property integer $sort
 *
 * @property Business $bus
 */
class Staff extends ActiveRecord
{
    const SCENARIO_ADD = 'addNew';
    const SCENARIO_EDIT = 'editOld';
    const SCENARIO_NEW = 'createNewName';
    const SCENARIO_PHOTO='only_photo';
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $imageFiles;
    private $_main_path = false;
    private $_thumb_path = false;
    //public $crop_params=[];
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
        return 'staff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bus_id', 'name'], 'required','on'=>self::SCENARIO_NEW, 'message' => '{attribute} can’t be blank.'],
            //[['bus_id'], 'required','on'=>self::SCENARIO_],
            ['crop_params','required','on'=>self::SCENARIO_EDIT],
            [['bus_id', 'sort'], 'integer'],
            [['name', 'role', 'description', 'url'], 'string', 'max' => 500],
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
                'extensions' => 'png, jpg, jpeg',
                'maxFiles' => 1,
                'on' => self::SCENARIO_PHOTO
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
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->sort = 1;
                $this->resortAllUp();
                //$this->sort = $this->getMaxSort() + 1;
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->deletePhoto();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bus_id' => 'Business',
            'name' => 'Name',
            'role' => 'Insert staff role',
            'description' => 'Insert profile bio characters',
            'url' => 'Photo url',
            'sort' => 'Sort',
            'imageFile' => 'Employee photo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBus()
    {
        return $this->hasOne(Business::className(), ['id' => 'bus_id']);
    }

    /**
     * Finds staff by ID
     *
     * @param  int $id
     * @return null|Staff
     */
    public static function findStaff($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds staff by Business ID
     *
     * @param  int $bus_id
     * @return null|Staff
     */
    public static function findByBusId($bus_id)
    {
        return static::find()->where(['bus_id' => $bus_id])->orderBy('sort')->all();
    }

    /**
     * Finds staff count by Business ID
     *
     * @param  int $bus_id
     * @return null|Staff
     */
    public static function findCountByBusId($bus_id)
    {
        return static::find()->where(['bus_id' => $bus_id])->count();
    }

    /**
     * Init sort field value
     */

    public function getMaxSort()
    {
        return (new Query())->from(self::tableName())->where(['bus_id' => $this->bus_id])->max('sort');
    }

    public function getMinSort()
    {
        return (new Query())->from(self::tableName())->where(['bus_id' => $this->bus_id])->min('sort');
    }

    /**
     * Delete old staff photo and thumb
     */
    public function deletePhoto()
    {
        if ($this->url) {

            $photo = 'uploads/bus' . $this->bus_id . '/staff/' . $this->url;
            if (file_exists($photo)) unlink($photo);
            $thumb = 'thumb/bus' . $this->bus_id . '/staff/' . $this->url;
            if (file_exists($thumb)) unlink($thumb);
        }
    }

    /**
     * @return string
     */
    public function getWebPath()
    {
        if ($this->url) {
            // return '@web/web/thumb/bus' . $this->bus_id . '/staff/' . $this->url;
            return '@web/thumb/bus' . $this->bus_id . '/staff/' . $this->url;
        } else {
            //return '@web/web/images/user_default.png';
            return '@web/images/user_default.png';
        }
    }

    /**
     * @return string|bool
     */
    public function getMainPath()
    {
        if (!$this->_main_path) {
            $main_path = 'uploads/bus' . $this->bus_id . '/staff/';
            if (!is_dir($main_path)) mkdir($main_path, 0777, true);
            $this->_main_path = $main_path;
        }
        return $this->_main_path;
    }


    public function getBigPath()
    {
        if ($this->url) {
            // return '@web/web/thumb/bus' . $this->bus_id . '/staff/' . $this->url;
            return '@web/uploads/bus' . $this->bus_id . '/staff/' . $this->url;
        } else {

            return '@web/images/user_default.png';
        }

    }

    /**
     * @return string|bool
     */
    public function getThumbPath()
    {
        if (!$this->_thumb_path) {
            $thumb_path = 'thumb/bus' . $this->bus_id . '/staff/';
            if (!is_dir($thumb_path)) mkdir($thumb_path, 0777, true);
            $this->_thumb_path = $thumb_path;
        }
        return $this->_thumb_path;
    }

    /**
     * @return string|boolean
     *//*
    protected function generateThumb()
    {
        if(strlen($this->crop_params)>0){
            $cropArray=explode(',',$this->crop_params);
        }
        $path = Yii::$app->basePath . '/web/';
        $main = $this->getMainPath();
        do {
            $file = rand(0, 9999) . '_' . time() . '.' . $this->imageFile->extension;
        } while (file_exists($main . $file));
        if (!$this->imageFile->saveAs($main . $file)) return false;


        Image::thumbnail($path . $main . $file, 220, 220)
            ->save($path . $this->getThumbPath() . $file, ['quality' => 80]);

        return $file;
    }
*/

    public function regenerateThumb(){

        $file=$this->url;
        $path = Yii::$app->basePath . '/web/';
        $main = $this->getMainPath();

$delta=1;

        $imagine = Image::getImagine();
        $image = $imagine->open($path . $main . $file);
    /*
        print_r($this->crop_params);
        print_r($image->getSize()->getHeight());
        print_r($image->getSize()->getWidth());
exit;
*/
        $height = $image->getSize()->getHeight();
        $width = $image->getSize()->getWidth();
        $crop_width = Yii::$app->params['staff_thmb_width'];
        $crop_height = Yii::$app->params['staff_thmb_height'];

        //if (($crop_height - $height < 0) || ($crop_width - $width < 0))
        {
            if ($height / $crop_height > $width / $crop_width) {
                $delta=$height / $crop_height;
                $crop_width = floor($width / $delta);
            } else if ($height / $crop_height < $width / $crop_width) {
                $delta=$width / $crop_width;
                $crop_height = floor($height / $delta);
            }

        }



        if (($this->crop_params['width'] != 0) && ($this->crop_params['height'] != 0)) {
            $image->rotate($this->crop_params['rotate'])
                ->crop(new Point($this->crop_params['x'], $this->crop_params['y']), new Box($this->crop_params['width'], $this->crop_params['height']))
                //->crop(new Point($this->crop_params['x']*$delta, $this->crop_params['y']*$delta), new Box($this->crop_params['width']*$delta, $this->crop_params['height']*$delta))
                ->save($path . $main . $file);
        }



        Image::thumbnail($path . $main . $file, $crop_width, $crop_height)
            ->save($path . $this->getThumbPath() . $file, ['quality' => 80]);

    }


    protected function generateThumb()
    {
        //if($this->crop_params['width']>0){


        $path = Yii::$app->basePath . '/web/';
        $main = $this->getMainPath();
        do {
            $file = rand(0, 9999) . '_' . time() . '.' . $this->imageFile->extension;
        } while (file_exists($main . $file));
        if (!$this->imageFile->saveAs($main . $file)) return false;



/*
        Image::crop($path . $main . $file,$this->crop_params['width'],$this->crop_params['height'],[$this->crop_params['x'],$this->crop_params['y']])
            ->save($path . $main . $file);

        Image::thumbnail($path . $main . $file, Yii::$app->params['staff_thmb_width'], Yii::$app->params['staff_thmb_height'])
            ->save($path . $this->getThumbPath() . $file, ['quality' => 80]);
*/

        $imagine = Image::getImagine();
        $image = $imagine->open($path . $main . $file);
        if (($this->crop_params['width'] != 0) && ($this->crop_params['height'] != 0)) {
            $image->rotate($this->crop_params['rotate'])
                ->crop(new Point($this->crop_params['x'], $this->crop_params['y']), new Box($this->crop_params['width'], $this->crop_params['height']))
                ->save($path . $main . $file);
        }

        $height = $image->getSize()->getHeight();
        $width = $image->getSize()->getWidth();
        $crop_width = Yii::$app->params['staff_thmb_width'];
        $crop_height = Yii::$app->params['staff_thmb_height'];

        if (($crop_height - $height < 0) || ($crop_width - $width < 0)) {
            if ($height / $crop_height > $width / $crop_width) {
                $crop_width = floor($width / ($height / $crop_height));
            } else if ($height / $crop_height < $width / $crop_width) {
                $crop_height = floor($height / ($width / $crop_width));
            }

        }

        Image::thumbnail($path . $main . $file, $crop_width, $crop_height)
            ->save($path . $this->getThumbPath() . $file, ['quality' => 80]);







        return $file;
    }

    /**
     * @return boolean whether the staff was saved
     */
    public function addEmployee()
    {
        if ($this->validate()) {
            $file = $this->generateThumb();
            if (!$file) return false;
            $this->url = $file;
            if ($this->save(false)) return true;
        }
        print_r($this->errors);
        return false;
    }

    /**
     * @param  \yii\web\UploadedFile|null $photo
     * @return boolean whether the staff was saved
     */
    public function updateEmployee($photo)
    {
        if ($this->validate()) {
            if ($photo != null) {
                $this->imageFile = $photo;
                $file = $this->generateThumb();
                if (!$file) return false;
                $this->deletePhoto();
                $this->url = $file;
            }
            if (($photo===null)&&($this->url!=='')){
                $file =  $this->regenerateThumb();
                if (!$file) return false;
            }
            if ($this->save(false)) return true;
        } //else return $this->errors;
        return false;
    }

    /**
     * @param  boolean $up
     * @return boolean whether sortable were changed
     */
    public function changeSort($up)
    {
        $sort = $this->sort;
        if ($up) {
            $new_sort = (new Query())->select('MAX(sort) as new_sort')->from('staff')
                ->where(['<', 'sort', $this->sort])
                ->andWhere('bus_id=:bus_id')->addParams([':bus_id' => $this->bus_id]);
        } else {
            $new_sort = (new Query())->select('MIN(sort) as new_sort')->from('staff')
                ->where(['>', 'sort', $this->sort])
                ->andWhere('bus_id=:bus_id')->addParams([':bus_id' => $this->bus_id]);
        }
        /* @var Staff $emp */
        $employee = Staff::findOne(['bus_id' => $this->bus_id, 'sort' => $new_sort]);
        if (empty($employee)) return false;
        $new_sort = $employee->sort;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $employee->sort = $sort;
            if ($employee->save(false)) {
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
/*
    public function uploadTempPhoto()
    {
        if ($this->validate())
        {

            Staff::removePrevUploadTempPhoto($this->bus_id);

            //Photo::removeMain($this->bus_id);
            $model = new Photo();
            $model->main=10;//only Main Photo
            $model->saved=1;
            $model->bus_id = $this->bus_id;
            $model->imageFile = $this->imageFile;
            $model->addPhoto(true);

            return true;
        }
        else
        {
            return false;
        }
    }

    public static function removePrevUploadTempPhoto($bus_id){



    }
    */


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
}