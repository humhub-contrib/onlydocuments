<?php

namespace humhub\modules\onlydocuments\models;

use Yii;
use yii\helpers\Url;
use humhub\modules\file\models\File;

/**
 * This is the model class for table "onlydocuments_share".
 *
 * @property integer $id
 * @property integer $file_id
 * @property string $secret
 * @property $mode $mode
 *
 * @property File $file
 */
class Share extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'onlydocuments_share';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::className(), ['id' => 'file_id']);
    }

    public static function getShareLink($file, $generateNew = false, $mode)
    {
        $share = self::findOne(['file_id' => $file->id, 'mode' => $mode]);
        if ($share === null) {
            if ($generateNew === false) {
                return null;
            }
            $share = self::generateShareLink($file, $mode);
        }

        return Url::to(['/onlydocuments/open', 'share' => $share->secret], true);
    }

    public static function generateShareLink($file, $mode)
    {
        $share = new self;
        $share->file_id = $file->id;
        $share->mode = $mode;
        $share->secret = bin2hex(Yii::$app->security->generateRandomKey(25));
        $share->save();

        return $share;
    }

}
