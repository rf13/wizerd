<?php

use yii\db\Migration;

class m150918_142300_create_custom_service_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('custom_service', [
            'id' => $this->primaryKey(),
            'bus_id' => $this->integer()->notNull(),
            'cat_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->string()
        ], $tableOptions);

        $this->createIndex('custom_service_bis_id_key', 'custom_service', 'bus_id');
        $this->addForeignKey(
            'fk_custom_service_bis_id', 'custom_service', 'bus_id',
            'business', 'id', 'CASCADE', 'RESTRICT'
        );

        $this->createIndex('custom_service_cat_id_key', 'custom_service', 'cat_id');
        $this->addForeignKey(
            'fk_custom_service_cat_id', 'custom_service', 'cat_id',
            'category', 'id', 'CASCADE', 'RESTRICT'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_custom_service_cat_id', 'custom_service');
        $this->dropIndex('custom_service_cat_id_key', 'custom_service');

        $this->dropForeignKey('fk_custom_service_bis_id', 'custom_service');
        $this->dropIndex('custom_service_bis_id_key', 'custom_service');

        $this->dropTable('custom_service');
    }
}
