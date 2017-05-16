<?php
namespace app\modules\admin\models;

use \Yii;
use app\forms\PhotoAddForm;
use app\models\CustomCategory;
use app\models\CustomService;
use app\models\Field;
use app\models\FieldValue;
use app\models\Photo;
use app\models\Tier;
use app\models\Menu;
use app\models\Operation;
use app\models\Business;
use app\models\User;
use app\models\ZipCode;
use yii\base\Model;
use app\components\UploadedFile;

class BusinessBulkUpload extends Model
{
    const LOCK_NAME = 'BusinessBulkUpload';

    public $checkExtensionByMimeType = false;
    public $logFile;
    /**
     * @var UploadedFile
     */
    public $dataFile;
    private $dataPath;
    private $jsonFile;
    private $photosPath;
    private $progressFile;
    private $registrationData;

    public function __construct($config = [])
    {
        $this->dataPath = $config['dataPath'];
        $this->logFile = $this->dataPath . 'log.txt';
        $this->jsonFile = $this->dataPath . 'business.json';
        $this->photosPath = $config['photosPath'];
        $this->progressFile = $this->dataPath . 'progress.txt';
        unset($config['dataPath']);
        unset($config['photosPath']);
        parent::__construct($config);
    }

    public function Import()
    {
        $hasError = false;
        if (Yii::$app->mutex->acquire(self::LOCK_NAME, 60 * 60 * 10)) {
            if (!$this->checkData()) {
                return false;
            }
            $c = 0;
            $total = count($this->registrationData);
            $this->setProgress(0);
            foreach ($this->registrationData as $item) {
                $c++;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $user = new User();
                    $user->setAttributes($item['user']);
                    $user->is_email_verified = 1;
                    $user->verification_time = time();
                    $user->generatePassword($item['user']['password']);
                    if (!$user->save()) {
                        $transaction->rollBack();
                        $this->writeLog($user->errors);
                        continue;
                    }
                    $business = new Business();
                    $business->user_id = $user->id;
                    $business->setAttributes($item['business']);
                    $zip = new ZipCode();
                    $zip_code = $zip->getZipCode($item['business']['zip_code']);
                    if ($zip_code) {
                        $business->zip_id = $zip_code->getAttribute('id');
                    } else {
                        $business->zip_notice = $item['business']['zip_code'];
                    }
                    $business->latitude = $zip_code->latitude;
                    $business->longitude = $zip_code->longitude;
                    if (!$business->save()) {
                        $transaction->rollBack();
                        $this->writeLog($business->errors);
                        continue;
                    }

                    if (array_key_exists('operation', $item)) {
                        foreach ($item['operation'] as $operationItem) {
                            $operation = new Operation();
                            $operation->bus_id = $business->id;
                            $operation->setAttributes($operationItem);
                            if (!$operation->save()) {
                                $transaction->rollBack();
                                $this->writeLog($operation->errors);
                                continue 2;
                            }
                        }
                    }

                    // profile photo
                    if (array_key_exists('profile_photo', $item)) {
                        $photoPath = $this->photosPath . $item['profile_photo'];
                        if (!$this->uploadPhoto($business->id, $item['profile_photo'], $photoPath)) {
                            $transaction->rollBack();
                            continue;
                        }
                    }

                    // photos
                    if (array_key_exists('photos', $item)) {
                        foreach ($item['photos'] as $photo) {
                            $photoPath = $this->photosPath . $photo['file_name'];
                            if (!$this->uploadPhoto($business->id, $photo['file_name'], $photoPath, false,
                                $photo['title'], $photo['description'])
                            ) {
                                $transaction->rollBack();
                                continue 2;
                            }
                        }
                    }

                    // menu
                    if (array_key_exists('menu', $item)) {
                        foreach ($item['menu'] as $menuItem) {
                            $menu = new Menu();
                            $menu->bus_id = $business->id;
                            $menu->setAttributes($menuItem);
                            if (!$menu->save()) {
                                $transaction->rollBack();
                                $this->writeLog($menu->errors);
                                continue 2;
                            }
                            if (array_key_exists('category', $menuItem)) {
                                foreach ($menuItem['category'] as $categoryItem) {
                                    $category = new CustomCategory();
                                    $category->menu_id = $menu->id;
                                    $category->srv_title = CustomCategory::SRV_TITLE;
                                    $category->price_title = CustomCategory::PRICE_TITLE;
                                    $category->setAttributes($categoryItem);
                                    if (!$category->save()) {
                                        $transaction->rollBack();
                                        $this->writeLog($category->errors);
                                        continue 3;
                                    }
                                    if (array_key_exists('service', $categoryItem)) {
                                        foreach ($categoryItem['service'] as $serviceItem) {
                                            $service = new CustomService();
                                            $service->cat_id = $category->id;
                                            $serviceTitle = $serviceItem['title'];
                                            unset($serviceItem['title']);
                                            if (!$service->save()) {
                                                $transaction->rollBack();
                                                $this->writeLog($service->errors);
                                                continue 4;
                                            }
                                            if (!$service->createTier()) {
                                                $transaction->rollBack();
                                                $this->writeLog($service->errors);
                                                continue 4;
                                            }
                                            $tier = Tier::findOne(['srv_id' => $service->id]);
                                            $tier->price = $serviceItem['price'];
                                            if (!$tier->save()) {
                                                $transaction->rollBack();
                                                $this->writeLog($tier->errors);
                                                continue 4;
                                            }
                                            unset($serviceItem['price']);

                                            foreach ($serviceItem as $fieldKey => $fieldValue) {
                                                if (!($fieldModel = Field::findOne([
                                                    'cat_id' => $category->id,
                                                    'title' => $fieldKey
                                                ]))
                                                ) {
                                                    $fieldModel = new Field();
                                                    $fieldModel->visible = 1;
                                                    $fieldModel->cat_id = $category->id;
                                                    $fieldModel->title = $fieldKey;
                                                    if (!$fieldModel->save()) {
                                                        $transaction->rollBack();
                                                        $this->writeLog($fieldModel->errors);
                                                        continue 5;
                                                    }
                                                }
                                                $fieldValueModel = new FieldValue();
                                                $fieldValueModel->tier_id = $tier->id;
                                                $fieldValueModel->field_id = $fieldModel->id;
                                                $fieldValueModel->value = $fieldValue;
                                                if (!$fieldValueModel->save()) {
                                                    $transaction->rollBack();
                                                    $this->writeLog($fieldValueModel->errors);
                                                    continue 5;
                                                }
                                            }

                                            $service->title = $serviceTitle;
                                            if (!$service->save()) {
                                                $transaction->rollBack();
                                                $this->writeLog($service->errors);
                                                continue 4;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $transaction->commit();
                } catch (\Exception $ex) {
                    $hasError = true;
                    $transaction->rollBack();
                    $this->writeLog($ex->getMessage() . ' (' . $ex->getLine() . ')');
                    continue;
                }
                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole(User::ROLE_BUSN);
                $auth->assign($authorRole, $user->id);
                $this->setProgress(intval(100 / $total * $c));
            }
            Yii::$app->mutex->release(self::LOCK_NAME);
        }

        return $hasError;
    }

    public function Remove()
    {
        $list = [
            'business@gmail.com',
            'business2@gmail.com',
            'business3@gmail.com',
        ];
        array_walk($list, function ($email) {
            $user = User::findOne(['email' => $email]);
            if ($user) {
                $business = Business::findOne(['user_id' => $user->id]);
                $business->delete();
                $user->delete();
            }
        });
    }

    protected function uploadPhoto($businessId, $fileName, $photoPath, $isMain = true, $title = null, $description = null)
    {
        $_FILES = [
            'PhotoAddForm' => [
                'name' => [
                    'imageFile' => $fileName
                ],
                'type' => [
                    'imageFile' => 'image/png'
                ],
                'tmp_name' => [
                    'imageFile' => $photoPath
                ],
                'error' => [
                    'imageFile' => 0
                ],
                'size' => [
                    'imageFile' => filesize($photoPath)
                ],

            ]
        ];

        $photoAddFormModel = new PhotoAddForm();
        if ($isMain) {
            $photoAddFormModel->bus_id = $businessId;
            $photoAddFormModel->imageFile = \app\components\UploadedFile::getInstance($photoAddFormModel, 'imageFile');
            $photoAddFormModel->scenario = PhotoAddForm::SCENARIO_ONE;
            $photoAddFormModel->uploadProfilePhoto();
            $photo = Photo::find()
                ->where('main=10 and bus_id=:bus_id', ['bus_id' => $businessId])
                ->one();
            $photo->croped = 1;
            $photo->main = 2;
            Photo::removeMain($businessId, $photo->id);
            $photo->generateProfileThumb();
        } else {
            $photo = new Photo();
            $photo->bus_id = $businessId;
            $photo->title = $title;
            $photo->description = $description;
            $photo->main = 0;
            $photo->saved = 1;
            $photo->imageFile = \app\components\UploadedFile::getInstance($photoAddFormModel, 'imageFile');
            $photo->addPhoto();
        }
        \app\components\UploadedFile::reset();
        if (!$photo->save()) {
            if (!$photo->save()) {
                $this->writeLog($photo->errors);

                return false;
            }
        }

        return true;
    }

    protected function writeLog($data)
    {
        error_log('[' . date('Y-m-d H:i:s', time()) . '] ' . trim(var_export($data, true), '\'') . PHP_EOL, 3,
            $this->logFile);
    }

    public function getLog()
    {
        return file_get_contents($this->logFile);
    }

    private function setProgress($value)
    {
        file_put_contents($this->progressFile, $value);
        chmod($this->progressFile, 0666);
    }

    public function checkData()
    {
        $result = true;
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
        if (!file_exists($this->jsonFile)) {
            $this->writeLog('DataFile not uploaded');

            return false;
        }
        $json = file_get_contents($this->jsonFile);
        $this->registrationData = json_decode($json, true);
        foreach ($this->registrationData as $item) {
            if (User::findOne(['email' => $item['user']['email']])) {
                $result = false;
                $this->writeLog('User with email already exists - ' . $item['user']['email']);
            }
            if (Business::findOne(['contact_email' => $item['business']['contact_email']])) {
                $result = false;
                $this->writeLog('Business with contact email already exists - ' . $item['business']['contact_email']);
            }
            $files = [];
            if (array_key_exists('profile_photo', $item)) {
                $files[$item['profile_photo']] = $this->photosPath . $item['profile_photo'];
            } else {
                $result = false;
                $this->writeLog('Profile Photo is absent - ' . $item['business']['name']);
            }
            if (array_key_exists('photos', $item) && count($item['photos'])) {
                foreach ($item['photos'] as $photo) {
                    $files[$photo['file_name']] = $this->photosPath . $photo['file_name'];
                }
            } else {
                $result = false;
                $this->writeLog('Photos is absent - ' . $item['business']['name']);
            }
            foreach ($files as $fileName => $fullName) {
                if (!file_exists($fullName)) {
                    $result = false;
                    $this->writeLog('File not exists - ' . $fileName);
                }
            }
        }

        return $result;
    }

    public function rules()
    {
        return [
            [
                ['dataFile'],
                'file',
                'checkExtensionByMimeType' => false,
                'skipOnEmpty' => false,
                'extensions' => 'json',
                'maxFiles' => 1
            ],
        ];
    }

    public function uploadDataFile()
    {
        if ($this->validate()) {
            if (($result = $this->dataFile->saveAs($this->jsonFile))) {
                chmod($this->jsonFile, 0666);
            }

            return $result;
        } else {
            return false;
        }
    }
}
