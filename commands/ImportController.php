<?php

namespace app\commands;

use \Yii;
use app\modules\admin\models\BusinessBulkUpload;
use yii\console\Controller;

class ImportController extends Controller
{
    public function actionIndex()
    {
        $bulkUpload = new BusinessBulkUpload([
            'dataPath' => Yii::$app->params['admin']['bulkUploadPath']['data'],
            'photosPath' => Yii::$app->params['admin']['bulkUploadPath']['photos']
        ]);
        $bulkUpload->Remove();
        $bulkUpload->Import();
    }
}
