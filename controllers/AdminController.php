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
use humhub\modules\onlydocuments\models\ConfigureForm;
use humhub\modules\admin\components\Controller;

class AdminController extends Controller
{

    public function actionIndex()
    {
        $model = new ConfigureForm();
        $model->loadSettings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->view->saved();
        }

        $version = $this->getDocumentServerVersion();
        
        return $this->render('index', ['model' => $model, 'version' => $version]);
    }

    private function getDocumentServerVersion()
    {
        $module = Yii::$app->getModule('onlydocuments');
        $response = $module->commandService(['c' => 'version']);
        
        if (isset($response['version'])) {
            return $response['version'];
        }
        
        return null;
    }

}
