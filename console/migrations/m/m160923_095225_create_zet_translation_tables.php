<?php

use yii\db\Migration;
use yii\db\Schema;

class m160923_095225_create_zet_translation_tables extends Migration
{
    public function up()
    {

        $this->createTable('zet_source_message_arch', [
            'id' => Schema::TYPE_PK,
            'category' => Schema::TYPE_STRING,
            'message' => Schema::TYPE_TEXT,
            'version' => Schema::TYPE_INTEGER,
        ]);

        $this->createTable('zet_message_arch', [
            'id' => Schema::TYPE_INTEGER,
            'language' => Schema::TYPE_STRING,
            'translation' => Schema::TYPE_TEXT,
            'version' => Schema::TYPE_INTEGER,
        ]);

        $this->createTable('zet_translate_data', [
            'id' => Schema::TYPE_PK,
            'created_by' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_TIMESTAMP,
            'version' => Schema::TYPE_INTEGER,
            'reverted' => $this->smallInteger()->defaultValue(0),
        ]);

        $this->addPrimaryKey('id', 'zet_message_arch', ['id', 'language', 'version']);
        $this->addForeignKey('fk_zet_source_message_message', 'zet_message_arch', 'id', 'zet_source_message_arch', 'id', 'cascade', 'restrict');
    }

    public function safeDown()
    {

        $this->dropTable('zet_source_message_arch');
        $this->dropTable('zet_message_arch');
        $this->dropTable('zet_translate_data');

        return true;
    }
}
