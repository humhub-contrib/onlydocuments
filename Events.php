<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\onlydocuments;

use Yii;
use humhub\modules\file\handler\FileHandlerCollection;

/**
 * @author luke
 */
class Events extends \yii\base\Object
{

    public static function onFileHandlerCollection($event)
    {
        /* @var $collection FileHandlerCollection */
        $collection = $event->sender;

        if ($collection->type === FileHandlerCollection::TYPE_CREATE) {
            $collection->register(new filehandler\CreateFileHandler());
            return;
        }

        /* @var $module \humhub\modules\onlydocuments\Module */
        $module = Yii::$app->getModule('onlydocuments');

        if ($module->getDocumentType($event->sender->file) !== null) {
            if ($collection->type == FileHandlerCollection::TYPE_EDIT) {
                $collection->register(new filehandler\EditFileHandler());
            } elseif ($collection->type === FileHandlerCollection::TYPE_VIEW) {
                $collection->register(new filehandler\ViewFileHandler());
            }
        }
    }

}
