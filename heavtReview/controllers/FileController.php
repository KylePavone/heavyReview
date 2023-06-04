<?php

namespace app\controllers;

use app\models\FileModel;
use yii\web\UploadedFile;
use Yii;


class FileController extends ApiController {
    
    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action); 
    }

    public function actionUpload() {
        $trx = Yii::$app->db->beginTransaction();
        $model = new FileModel();
        $model->imageFile = $_FILES;
        $model->imageFile = UploadedFile::getInstanceByName('imageFile');
        $filename = FileModel::genUniqueFileName($model->imageFile->name, 10);
        $model->data = ['filename'=> $filename];
        if ($model->validate()) {
            if ($model->upload($filename)) {
                $model->save(false);
                $trx->commit();
                return ['status' => 'success'];
            } else {
                $trx->rollBack();
            }
        }
    }
}

?>