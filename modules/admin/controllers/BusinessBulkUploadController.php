<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\BusinessBulkUpload;
use yii\web\Controller;
use yii\web\UploadedFile;

class BusinessBulkUploadController extends Controller
{
    public function actionIndex()
    {
        $checkErrors = '';
        $isUpload = false;
        $ready = false;
        $bulkUploadModel = new BusinessBulkUpload([
            'dataPath' => Yii::$app->params['admin']['bulkUploadPath']['data'],
            'photosPath' => Yii::$app->params['admin']['bulkUploadPath']['photos']
        ]);
        if (Yii::$app->request->isPost) {
            $isUpload = true;
            $bulkUploadModel->dataFile = UploadedFile::getInstance($bulkUploadModel, 'dataFile');
            if ($bulkUploadModel->uploadDataFile()) {
                if ($bulkUploadModel->checkData()) {
                    $ready = true;
                    Yii::$app->session->set('ready', true);
                } else {
                    $checkErrors = $bulkUploadModel->getLog();
                    $ready = false;
                }
            }
        }
        if (!($isLocked = $this->isLocked())) {
            //free
        }

        return $this->render('index', [
            'isLocked' => $isLocked,
            'bulkUploadModel' => $bulkUploadModel,
            'checkErrors' => $checkErrors,
            'isUpload' => $isUpload,
            'ready' => $ready,
        ]);
    }

    public function actionGetProgress()
    {
        $progress = $this->getProgress();
        echo json_encode(['progress' => $progress]);

        return;
    }

    public function actionStart()
    {
        if (Yii::$app->session->get('ready', false)) {
            Yii::$app->session->set('ready', false);
            exec(Yii::getAlias('@app') . '/yii import > /dev/null &');
            sleep(3);
        }
        $this->redirect('index');
    }

    public function actionHelp()
    {
        return $this->render('help');
    }

    protected function isLocked()
    {
        $isLocked = true;
        if (Yii::$app->mutex->acquire(BusinessBulkUpload::LOCK_NAME)) {
            Yii::$app->mutex->release(BusinessBulkUpload::LOCK_NAME);
            $isLocked = false;
        }

        return $isLocked;
    }

    protected function getProgress()
    {
        $result = 0;
        if (file_exists($progressFile = Yii::$app->params['admin']['bulkUploadPath']['data'] . 'progress.txt')) {
            $result = (int)file_get_contents($progressFile);
        }

        return $result;
    }
}
