<?php

use yii\db\Schema;
use yii\db\Migration;

class m150605_064333_tbl_subscribe extends Migration
{
    public function up()
    {

        $this->execute("
        CREATE TABLE IF NOT EXISTS `subscribe` (
  `idsubscribe` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `date_subscribe` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idsubscribe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ");

    }

    public function down()
    {
       $this->dropTable("subscribe");

        return false;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
