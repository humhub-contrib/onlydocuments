<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\onlydocuments\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Url;
use humhub\modules\file\models\File;
use humhub\modules\file\libs\FileHelper;
use humhub\components\Controller;

class BackendController extends Controller
{

    /**
     * @var File
     */
    public $file;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        $key = Yii::$app->request->get('key');
        $this->file = File::findOne(['onlydocuments_key' => $key]);

        if ($this->file == null) {
            throw new HttpException(404, Yii::t('FileModule.base', 'Could not find requested file!'));
        }

        return parent::beforeAction($action);
    }

    /**
     * Download function for the Only server
     */
    public function actionDownload()
    {
        //Yii::warning("Downloading file guid: " . $this->file->guid, 'onlydocuments');
        return Yii::$app->response->sendFile($this->file->store->get(), $this->file->file_name);
    }

    public function actionTrack()
    {
        Yii::$app->response->format = 'json';

        $_trackerStatus = array(
            0 => 'NotFound',
            1 => 'Editing',
            2 => 'MustSave',
            3 => 'Corrupted',
            4 => 'Closed',
            6 => 'ForceSave'
        );

        $result = [];
        $result['status'] = 'success';
        $result["error"] = 0;

        if (($body_stream = file_get_contents('php://input')) === FALSE) {
            $result["error"] = "Bad Request";
            Yii::error('Bad tracker request', 'onlydocuments');
            return $result;
        }

        $data = json_decode($body_stream, TRUE); //json_decode - PHP 5 >= 5.2.0
        if ($data === NULL) {
            Yii::error('Got bad tracking response from documentserver!', 'onlydocuments');
            $result["error"] = "Bad Response";
            return $result;
        }

        //Yii::warning('Tracking request for file ' . $this->file->guid . ' - data: ' . print_r($data, 1), 'onlydocuments');

        $status = $_trackerStatus[$data["status"]];
        switch ($status) {
            case "MustSave":
            case "Corrupted":
            case "ForceSave":

                $newData = file_get_contents($data["url"]);

                if (!empty($newData)) {
                    $this->file->getStore()->setContent($newData);

                    if ($status != 'ForceSave') {
                        $this->file->updateAttributes(['onlydocuments_key' => new \yii\db\Expression('NULL')]);
                        //Yii::warning('Dosaved', 'onlydocuments');
                    } else {
                        //Yii::warning('ForceSaved', 'onlydocuments');
                    }
                    $saved = 1;
                } else {
                    Yii::error('Could not save onlyoffice document: ' . $data["url"], 'onlydocuments');
                    $saved = 0;
                }

                $result["c"] = "saved";
                $result["status"] = $saved;
                break;
        }

        //Yii::warning("Return: " . print_r($result, 1), 'onlydocuments');
        return $result;
    }

}
