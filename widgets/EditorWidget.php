<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\onlydocuments\widgets;

use Yii;
use yii\web\HttpException;
use humhub\modules\file\models\File;
use yii\helpers\Url;
use humhub\libs\Html;
use humhub\modules\file\libs\FileHelper;
use humhub\widgets\JsWidget;

/**
 * Description of EditorWidget
 *
 * @author Luke
 */
class EditorWidget extends JsWidget
{

    /**
     * @var File the file
     */
    public $file;

    /**
     * @var string mode (edit or view)
     */
    public $mode;

    /**
     * @inheritdoc
     */
    public $jsWidget = 'onlydocuments.Editor';

    /**
     * @inheritdoc
     */
    public $init = true;

    /**
     * @inheritdoc
     */
    protected $documentType = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $module = Yii::$app->getModule('onlydocuments');
        $this->documentType = $module->getDocumentType($this->file);
        if ($this->documentType === null) {
            throw new HttpException('400', 'Requested file type is not supported!');
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $module = Yii::$app->getModule('onlydocuments');
        $user = Yii::$app->user->getIdentity();
        $key = $this->generateDocumentKey();

        return [
            'file-name' => Html::encode($this->file->fileName),
            'file-extension' => Html::encode(strtolower(FileHelper::getExtension($this->file))),
            'file-key' => $key,
            'created-by' => Html::encode('Creator BY'),
            'created-at' => Html::encode('Creator AT'),
            'document-type' => $this->documentType,
            'user-guid' => ($user) ? Html::encode($user->guid) : '',
            'user-name' => ($user) ? Html::encode($user->displayName) : 'Anonymous',
            'user-first-name' => ($user) ? Html::encode($user->profile->firstname) : 'Anonymous',
            'user-last-name' => ($user) ? Html::encode($user->profile->lastname) : 'User',
            'user-language' => ($user) ? $user->language : 'en',
            'backend-track-url' => Url::to(['/onlydocuments/backend/track', 'key' => $key], true),
            'backend-download-url' => Url::to(['/onlydocuments/backend/download', 'key' => $key], true),
            'edit-mode' => $this->mode,
            'file-info-url' => Url::to(['/onlydocuments/open/get-info', 'guid' => $this->file->guid]),
            'module-configured' => (empty($module->getServerUrl()) ? '0' : '1'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        return [
            'style' => 'height:100%;border-radius: 8px 8px 0px 0px;background-color:#F4F4F4'
        ];
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('editor', [
                    'documentType' => $this->documentType,
                    'file' => $this->file,
                    'mode' => $this->mode,
                    'options' => $this->getOptions(),
        ]);
    }

    /**
     * Generate unique document key
     * 
     * @return string
     */
    protected function generateDocumentKey()
    {
        if (!empty($this->file->onlydocuments_key)) {
            return $this->file->onlydocuments_key;
        }

        $key = substr(strtolower(md5(Yii::$app->security->generateRandomString(20))), 0, 20);
        $this->file->updateAttributes(['onlydocuments_key' => $key]);
        return $key;
    }

}
