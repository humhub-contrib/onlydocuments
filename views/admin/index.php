<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="panel panel-default">

    <div class="panel-heading"><?= Yii::t('OnlydocumentsModule.base', '<strong>OnlyOffice - DocumentServer</strong> module configuration'); ?></div>

    <div class="panel-body">

        <?php if (!empty($version)): ?>
            <div class="alert alert-success" role="alert"><?= Yii::t('OnlydocumentsModule.base', '<strong>DocumentServer</strong> successfully connected! - Installed version: {version}', ['version' => $version]); ?></div>
        <?php elseif (empty($model->serverUrl)): ?>
            <div class="alert alert-warning" role="alert"><?= Yii::t('OnlydocumentsModule.base', '<strong>DocumentServer</strong> not configured yet.'); ?></div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert"><?= Yii::t('OnlydocumentsModule.base', '<strong>DocumentServer</strong> not accessible.'); ?></div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin(['id' => 'configure-form']); ?>
        <div class="form-group">
            <?= $form->field($model, 'serverUrl'); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
