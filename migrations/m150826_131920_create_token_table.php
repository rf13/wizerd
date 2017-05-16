<?php

use yii\db\Schema;
use yii\db\Migration;

class m150826_131920_create_token_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('token', [
            'user_id' => $this->integer()->notNull(),
            'code' => $this->string(32)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ], $tableOptions);

        $this->createIndex('token_unique', 'token', ['user_id', 'code', 'type'], true);
        $this->addForeignKey('fk_token_user_id', 'token', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_token_user_id', 'token');
        $this->dropIndex('token_unique', 'token');

        $this->dropTable('token');
    }
}
