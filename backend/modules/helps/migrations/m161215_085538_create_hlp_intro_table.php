<?php
namespace backend\modules\helps\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `hlp_intro`.
 */
class m161215_085538_create_hlp_intro_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('hlp_intro', [
			'id' => $this->primaryKey(),
			'page_id' => $this->string(50),
			'element_id' => $this->string(50),
			'body' => $this->integer(),
			//'body' => $this->string(100),
			'description' => $this->string(100),
			'position' => $this->string(20),
			'variant_two' => $this->string(20),
			'is_guest' => $this->integer(),
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('hlp_intro');
	}
}
