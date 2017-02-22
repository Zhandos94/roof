<?php

namespace backend\modules\translate\models;

use yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 * 
 * @property SourceMessage $sourceMessage
 */
class Message extends yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            /** @noinspection PhpUndefinedFieldInspection */
            $_username = Yii::$app->user->identity->username;
            if ($insert) {
                $msg_to_log = [
                    'creating message',
                    'user' => $_username,
                    'message' => $this->sourceMessage->message,
                    'id' => $this->id,
                    'language' => $this->language,
                    'translate' => $this->translation,
                ];
                Yii::info($msg_to_log, 'translate_log');
            } else {
                if(empty(trim($this->oldAttributes['translation'])) && !empty($this->translation)){
                    $msg_to_log = [
                        'fill empty translation',
                        'user' => $_username,
                        'message' => $this->sourceMessage->message,
                        'id' => $this->id,
                        'language' => $this->language,
                        'new translation' => $this->translation,
                    ];
                    Yii::info($msg_to_log, 'translate_log');
                } elseif(trim($this->oldAttributes['translation']) != trim($this->translation)) {
                    $msg_to_log = [
                        'update translation',
                        'user' => $_username,
                        'message' => $this->sourceMessage->message,
                        'id' => $this->id,
                        'language' => $this->language,
                        'old translation' => $this->oldAttributes['translation'],
                        'new translation' => $this->translation,
                    ];
                    Yii::info($msg_to_log, 'translate_log');
                }
            }
            return true;
        }
        return false;
    }

    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }
}
