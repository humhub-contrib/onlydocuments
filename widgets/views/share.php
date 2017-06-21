<?php

use humhub\libs\Html;
use humhub\widgets\ModalDialog;

if (class_exists('humhub\assets\ClipboardJsAsset')) {
    humhub\assets\ClipboardJsAsset::register($this);
}
?>

<?php $modal = ModalDialog::begin(['header' => Yii::t('OnlydocumentsModule.base', '<strong>Share</strong> document')]) ?>
<?= Html::beginTag('div', $options) ?>

<div class="modal-body">
    <?= Yii::t('OnlydocumentsModule.base', 'You can simply share this document using a direct link. The user does not need an valid user account on the platform.'); ?>
    <br/>
    <br/>

    <div class="checkbox" style="margin-left:-10px;">
        <label>
            <input type="checkbox" class="viewLinkCheckbox"> <?= Yii::t('OnlydocumentsModule.base', 'Link for view only access'); ?>
        </label>
    </div>
    <div class="form-group viewLinkInput" style="margin-top:6px">
        <input type="text" class="form-control" value="<?= $viewLink; ?>">
        <p class="help-block pull-right"><a href="#" onClick="clipboard.copy($('.viewLinkInput').find('input').val())"><i class="fa fa-clipboard" aria-hidden="true"></i> <?= Yii::t('OnlydocumentsModule.base', 'Copy to clipboard'); ?></a></p>
    </div>

    <div class="checkbox" style="margin-left:-10px;padding-top:12px">
        <label>
            <input type="checkbox" class="editLinkCheckbox"> <?= Yii::t('OnlydocumentsModule.base', 'Link with enabled write access'); ?>
        </label>
    </div>
    <div class="form-group editLinkInput"  style="margin-top:6px">
        <input type="text" class="form-control" value="<?= $editLink; ?>">
        <p class="help-block  pull-right"><a href="#" onClick="clipboard.copy($('.editLinkInput').find('input').val())"><i class="fa fa-clipboard" aria-hidden="true"></i> <?= Yii::t('OnlydocumentsModule.base', 'Copy to clipboard'); ?></a></p>
    </div>

</div>

<div class="modal-footer">
    <a href="#" data-modal-close class="btn btn-primary" data-ui-loader><?= Yii::t('OnlydocumentsModule.base', 'Close'); ?></a>
</div>

<?= Html::endTag('div'); ?>
<?php ModalDialog::end(); ?>