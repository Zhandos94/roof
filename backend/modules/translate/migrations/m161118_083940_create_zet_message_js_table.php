<?php

namespace backend\modules\translate\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `zet_message_js`.
 */
class m161118_083940_create_zet_message_js_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('zet_message_js', [
            'id' => $this->primaryKey(),
            'message' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('zet_message_js');
    }
}
