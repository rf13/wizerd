<?php
namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Photo;
use yii\web\UploadedFile;

class PhotoAddForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;
    public $imageFile;
    public $titles;
    public $descriptions;
    public $bus_id;

    const  SCENARIO_ONE='one';
    const  SCENARIO_MANY='many';

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 20,  'on' => self::SCENARIO_MANY],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 1,  'on' => self::SCENARIO_ONE],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $model = new Photo();
                $model->bus_id = $this->bus_id;
                $model->imageFile = $file;
                $model->addPhoto();
            }
            return true;
        } else {
            return false;
        }
    }
    /*
    public function uploadProfilePhoto()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                Photo::removeMain($this->bus_id);
                $model = new Photo();
                $model->main=2;//only Main Photo
                $model->saved=1;
                $model->bus_id = $this->bus_id;
                $model->imageFile = $file;
                $model->addPhoto();
            }
            return true;
        } else {
            return false;
        }
    }
*/
    public function uploadProfilePhoto()
    {
        if ($this->validate())
        {

                Photo::removePrevUploadNewMain($this->bus_id);

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
    /*
    public function upload2()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {

            return false;
        }
    }
*/
}


?>