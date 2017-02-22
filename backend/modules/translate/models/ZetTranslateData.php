<?php

namespace backend\modules\translate\models;

use yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "zet_translate_data".
 *
 * @property integer $id
 * @property integer $user
 * @property string $created_at
 * @property integer $version
 * @property integer $created_by
 * @property integer $reverted
 */
class ZetTranslateData extends ActiveRecord
{
    const REVERTED = 1;
    const NOT_REVERTED = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zet_translate_data';
    }

    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'updatedByAttribute' => null,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['version'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user' => Yii::t('app', 'User'),
            'created_at' => Yii::t('app', 'Created At'),
            'version' => Yii::t('app', 'Version'),
        ];
    }
    
    public static function getLastVersion(){
        return ZetTranslateData::find()->where(['not', ['reverted' => 1]])->max('version');
    }
}
