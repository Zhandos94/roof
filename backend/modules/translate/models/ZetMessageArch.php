<?php

namespace backend\modules\translate\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "zet_message_arch".
 *
 * @property integer $id
 * @property integer $version
 * @property string $language
 * @property string $translation
 *
 * @property ZetSourceMessageArch $id0
 */
class ZetMessageArch extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zet_message_arch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 255],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => ZetSourceMessageArch::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'language' => Yii::t('app', 'Language'),
            'translation' => Yii::t('app', 'Translation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(ZetSourceMessageArch::className(), ['id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ZetMessageArchQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ZetMessageArchQuery(get_called_class());
    }
}
