<?php

namespace backend\modules\translate\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "zet_message_js".
 *
 * @property integer $id
 * @property string $message
 */
class MessageJs extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zet_message_js';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string', 'max' => 255],
            [['kz', 'ru', 'en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'message' => Yii::t('app', 'Message'),
        ];
    }
}
