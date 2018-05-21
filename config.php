<?php

use humhub\modules\file\handler\FileHandlerCollection;

return [
    'id' => 'onlydocuments',
    'class' => 'humhub\modules\onlydocuments\Module',
    'namespace' => 'humhub\modules\onlydocuments',
    'events' => [
        [FileHandlerCollection::className(), FileHandlerCollection::EVENT_INIT, ['humhub\modules\onlydocuments\Events', 'onFileHandlerCollection']],
    ]
];
