<?php

namespace humhub\modules\onlydocuments\models;

use Yii;

/**
 * ConfigureForm defines the configurable fields.

 */
class ConfigureForm extends \yii\base\Model
{

    public $serverUrl;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['serverUrl', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'serverUrl' => Yii::t('OnlydocumentsModule.base', 'Hostname'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'serverUrl' => Yii::t('OnlydocumentsModule.base', 'e.g. http://documentserver'),
        ];
    }

    public function loadSettings()
    {
        $this->serverUrl = Yii::$app->getModule('onlydocuments')->settings->get('serverUrl');

        return true;
    }

    public function save()
    {
        Yii::$app->getModule('onlydocuments')->settings->set('serverUrl', $this->serverUrl);

        return true;
    }

}
