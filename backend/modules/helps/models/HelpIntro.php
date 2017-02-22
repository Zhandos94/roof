<?php

namespace backend\modules\helps\models;

use Yii;
use backend\modules\translate\models\SourceMessage;
use backend\modules\translate\models\Message;

/**
 * This is the model class for table "hlp_intro".
 *
 * @property integer $id
 * @property string $page_id
 * @property string $element_id
 * @property integer $body
 * @property string $description
 * @property string $position
 */
class HelpIntro extends \yii\db\ActiveRecord
{
	public $message;
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'hlp_intro';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			//[['body'], 'integer'],
			[['body', 'page_id', 'element_id'], 'required'],
			[['page_id', 'element_id', 'description', 'position', 'message'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'page_id' => 'Page ID',
			'element_id' => 'Element ID',
			'body' => 'Body',
			'message' => 'Message',
			'description' => 'Description',
			'position' => 'Position',
			'category' => 'Category',
		];
	}

	/**
	 * @return ActiveQuery
	*/
	public function getSourceMessage()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'body']);
	}

	public function getMessageModel()
	{
		return Message::find()->where(['id' => $this->body, 'language' => Yii::$app->language]);
	}

}
