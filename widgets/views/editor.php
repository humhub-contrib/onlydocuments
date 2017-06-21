<?php

use yii\helpers\Url;
use humhub\libs\Html;
use humhub\modules\onlydocuments\Module;

\humhub\modules\onlydocuments\assets\Assets::register($this);


$headerTitle = '';
$headerBackgroundColor = '';

if ($documentType === Module::DOCUMENT_TYPE_SPREADSHEET) {
    $headerBackgroundColor = '#8CA946';
    $headerTitle = 'Spreadsheet Editor';
} elseif ($documentType === Module::DOCUMENT_TYPE_TEXT) {
    $headerBackgroundColor = '#5A7DC9';
    $headerTitle = 'Document Editor';
} elseif ($documentType === Module::DOCUMENT_TYPE_PRESENTATION) {
    $headerBackgroundColor = '#DD682B';
    $headerTitle = 'Presentation Editor';
}
?>

<?= Html::beginTag('div', $options) ?>
<div style = "height:50px; border-radius: 8px 8px 0px 0px; background-color:<?= $headerBackgroundColor; ?>; padding-top:6px; padding-right:12px">
    <div class = "pull-right" style = "margin-top:8px;margin-right:12px">
        <?php if ($mode === Module::OPEN_MODE_EDIT && !Yii::$app->user->isGuest): ?>
            <?= humhub\libs\Html::a('Share', '#', ['class' => 'btn btn btn-default', 'data-action-click' => 'share', 'data-action-block' => 'sync', 'data-action-url' => Url::to(['/onlydocuments/share', 'guid' => $file->guid, 'mode' => $mode])]); ?>
        <?php endif; ?>
        <?= humhub\libs\Html::a('Close', '#', ['class' => 'btn btn btn-default', 'data-ui-loader' => '', 'data-action-click' => 'close', 'data-action-block' => 'manual']); ?>
    </div>
    <div style="color:white;padding-left:12px;font-size:24px;padding-top:3px;padding-left:18px"><?= $headerTitle; ?></div>
</div>
<div id="iframeContainer"></div>
<?= Html::endTag('div'); ?>
