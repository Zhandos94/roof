<?php

use yii\db\Migration;

/**
 * Handles the creation of table `int_information`.
 */
class m161121_111507_create_int_information_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('int_settings', [
			'id' => $this->primaryKey(),
			'hash' => $this->string(),
			'system' => $this->string(),
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('int_settings');
	}
}
