<?php

namespace backend\modules\translate\models;

use Yii;

/**
 * This is the model class for table "zet_source_message_arch".
 *
 * @property integer $id
 * @property integer $version
 * @property string $category
 * @property string $message
 *
 * @property ZetMessageArch[] $zetMessageArches
 */
class ZetSourceMessageArch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zet_source_message_arch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['id'],'integer'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Category'),
            'message' => Yii::t('app', 'Message'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZetMessageArches()
    {
        return $this->hasMany(ZetMessageArch::className(), ['id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ZetSourceMessageArchQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ZetSourceMessageArchQuery(get_called_class());
    }
}
