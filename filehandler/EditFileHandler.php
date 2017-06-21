<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\onlydocuments\filehandler;

use Yii;
use humhub\libs\Html;
use humhub\modules\onlydocuments\Module;
use humhub\modules\file\handler\BaseFileHandler;
use yii\helpers\Url;

/**
 * Description of ViewHandler
 *
 * @author Luke
 */
class EditFileHandler extends BaseFileHandler
{

    /**
     * @inheritdoc
     */
    public function getLinkAttributes()
    {
        return [
            'label' => Yii::t('FileModule.base', 'Edit document'),
            'data-action-url' => Url::to(['/onlydocuments/open', 'guid' => $this->file->guid, 'mode' => Module::OPEN_MODE_EDIT]),
            'data-action-click' => 'ui.modal.load',
            'data-modal-id' => 'onlydocuments-modal',
            'data-modal-close' => ''
        ];
    }

}
