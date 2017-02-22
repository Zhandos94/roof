<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        if(!file_exists('@Zelenin/yii/modules/I18n/migrations')){
            echo 'php composer.phar require zelenin/yii2-i18n-module "dev-master"';
        }
        Yii::$app->runAction('migrate',['migrationPath' => '@Zelenin/yii/modules/I18n/migrations', 'interactive' => false]);
        Yii::$app->runAction('migrate',['migrationPath' => '@yii/rbac/migrations', 'interactive' => false]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}