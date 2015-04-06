<?php

use yii\db\Schema;
use yii\db\Migration;

class m150406_143523_init extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE `user` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `email` VARCHAR(100) NOT NULL,
                `password_hash` VARCHAR(100) NOT NULL DEFAULT '',
                `name` VARCHAR(1024) NOT NULL,
                `registration_code` VARCHAR(20) NOT NULL DEFAULT '',
                PRIMARY KEY (`id`),
                UNIQUE INDEX `email` (`email`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DROP TABLE `user`
        ";
        $this->execute($sql);
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
