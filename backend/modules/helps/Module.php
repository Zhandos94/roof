<?php

namespace backend\modules\helps;

/**
 * help module definition class
 */
class Module extends \yii\base\Module
{
	/**
	 * @inheritdoc
	 */
	public $controllerNamespace = 'backend\modules\helps\controllers';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
	  parent::init();
	  \Yii::configure($this, require(__DIR__ . '/config.php'));
	}
}
