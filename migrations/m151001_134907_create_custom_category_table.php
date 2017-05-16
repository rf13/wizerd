<?php

use yii\db\Migration;

class m151001_134907_create_custom_category_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('custom_category', [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->string(),
            'disclaimer' => $this->string(),
            'type' => $this->integer(1)->defaultValue(0),
            'sort' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex('custom_category_menu_id_key', 'custom_category', 'menu_id');
        $this->addForeignKey(
            'fk_custom_category_menu_id', 'custom_category', 'menu_id',
            'menu', 'id', 'CASCADE', 'RESTRICT'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_custom_category_menu_id', 'custom_category');
        $this->dropIndex('custom_category_menu_id_key', 'custom_category');

        $this->dropTable('custom_category');
    }
}
