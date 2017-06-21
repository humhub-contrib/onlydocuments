<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\onlydocuments;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use humhub\modules\file\libs\FileHelper;
use humhub\libs\CURLHelper;

/**
 * File Module
 *
 * @since 0.5
 */
class Module extends \humhub\components\Module
{

    public $resourcesPath = 'resources';

    /**
     * Open modes
     */
    const OPEN_MODE_VIEW = 'view';
    const OPEN_MODE_EDIT = 'edit';

    /**
     * Only document types
     */
    const DOCUMENT_TYPE_TEXT = 'text';
    const DOCUMENT_TYPE_PRESENTATION = 'presentation';
    const DOCUMENT_TYPE_SPREADSHEET = 'spreadsheet';

    /**
     * @var string[] allowed spreadsheet extensions 
     */
    public $spreadsheetExtensions = ['xls', 'xlsx', 'ods', 'csv'];

    /**
     * @var string[] allowed presentation extensions 
     */
    public $presentationExtensions = ['ppsx', 'pps', 'ppt', 'pptx', 'odp'];

    /**
     * @var string[] allowed text extensions 
     */
    public $textExtensions = ['docx', 'doc', 'odt', 'rtf', 'txt', 'html', 'htm', 'mht', 'pdf', 'djvu', 'fb2', 'epub', 'xps'];

    
    
    
    public function getServerUrl()
    {
        return $this->settings->get('serverUrl');
    }

    /**
     * 
     * @return type
     */
    public function getServerApiUrl()
    {
        return $this->getServerUrl() . '/web-apps/apps/api/documents/api.js';
    }

    public function getDocumentType($file)
    {
        $fileExtension = FileHelper::getExtension($file);

        if (in_array($fileExtension, $this->spreadsheetExtensions)) {
            return self::DOCUMENT_TYPE_SPREADSHEET;
        } elseif (in_array($fileExtension, $this->presentationExtensions)) {
            return self::DOCUMENT_TYPE_PRESENTATION;
        } elseif (in_array($fileExtension, $this->textExtensions)) {
            return self::DOCUMENT_TYPE_TEXT;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to([
                    '/onlydocuments/admin'
        ]);
    }

    public function commandService($data)
    {
        $url = $this->getServerUrl() . '/coauthoring/CommandService.ashx';

        try {
            $http = new \Zend\Http\Client($url, [
                'adapter' => '\Zend\Http\Client\Adapter\Curl',
                'curloptions' => CURLHelper::getOptions(),
                'timeout' => 10
            ]);
            $http->setMethod('POST');
            $http->setRawBody(Json::encode($data));
            $response = $http->send();
            $json = $response->getBody();
        } catch (\Exception $ex) {
            Yii::error('Could not get document server response! ' . $ex->getMessage());
            return [];
        }

        try {
            return Json::decode($json);
        } catch (\yii\base\InvalidParamException $ex) {
            Yii::error('Could not get document server response! ' . $ex->getMessage());
            return [];
        }
    }

}
