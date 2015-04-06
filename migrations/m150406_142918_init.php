<?php

use yii\db\Schema;
use yii\db\Migration;

class m150406_142918_init extends Migration
{
    public function up()
    {
        $sql = "
            CREATE TABLE `image` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `path` VARCHAR(300) NOT NULL,
                `description` VARCHAR(1024) NOT NULL DEFAULT '',
                PRIMARY KEY (`id`),
                INDEX `user_id` (`user_id`),
                CONSTRAINT `FK_image_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ";
        $this->execute($sql);
        
        
        $sql = "
            CREATE TABLE `image_comment` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `image_id` INT(11) NOT NULL,
                `rating` INT(11) NOT NULL,
                `user_id` INT(11) NULL DEFAULT NULL COMMENT 'Для зарегистрированных пользователей',
                `user_name` VARCHAR(1024) NULL DEFAULT '' COMMENT 'Для незарегистрированных пользователей',
                `user_email` VARCHAR(100) NULL DEFAULT '' COMMENT 'Для незарегистрированных пользователей',
                `text` VARCHAR(2048) NOT NULL DEFAULT '',
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                INDEX `image_id` (`image_id`),
                INDEX `user_id` (`user_id`),
                CONSTRAINT `FK_image_comment_image` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`),
                CONSTRAINT `FK_image_comment_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DROP TABLE `image_comment`
        ";
        $this->execute($sql);

        $sql = "
            DROP TABLE `image`
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
