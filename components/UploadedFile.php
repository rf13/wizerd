<?php

namespace app\components;

class UploadedFile extends \yii\web\UploadedFile
{
    public function saveAs($file)
    {
        return copy($this->tempName, $file);
    }
}
