<?php

use yii\db\Migration;

class m150917_110945_change_location extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_location_ind_id', 'location');
        $this->dropForeignKey('fk_location_zip_id', 'location');
        $this->dropIndex('location_zip_id_key', 'location');
        $this->dropIndex('location_ind_id_key', 'location');
        $this->dropTable('location');

        $this->dropForeignKey('fk_composition_attr_id', 'composition');
        $this->dropForeignKey('fk_composition_serv_id', 'composition');
        $this->dropIndex('composition_serv_id_key', 'composition');
        $this->dropIndex('composition_attr_id_key', 'composition');
        $this->dropTable('composition');

        $this->dropTable('attribute');
    }

    public function down()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('attribute', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->string()
        ], $tableOptions);

        $this->createTable('composition', [
            'id' => $this->primaryKey(),
            'serv_id' => $this->integer()->notNull(),
            'attr_id' => $this->integer()->notNull()
        ], $tableOptions);
        $this->createIndex('composition_serv_id_key', 'composition', 'serv_id');
        $this->createIndex('composition_attr_id_key', 'composition', 'attr_id');
        $this->addForeignKey(
            'fk_composition_attr_id', 'composition', 'attr_id', 'attribute', 'id', 'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey(
            'fk_composition_serv_id', 'composition', 'serv_id', 'service', 'id', 'CASCADE', 'RESTRICT'
        );

        $this->createTable('location', [
            'id' => $this->primaryKey(),
            'zip_id' => $this->integer()->notNull(),
            'ind_id' => $this->integer()->notNull()
        ], $tableOptions);
        $this->createIndex('location_zip_id_key', 'location', 'zip_id');
        $this->createIndex('location_ind_id_key', 'location', 'ind_id');
        $this->addForeignKey(
            'fk_location_ind_id', 'location', 'ind_id', 'industry', 'id', 'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey(
            'fk_location_zip_id', 'location', 'zip_id', 'zip_code', 'id', 'CASCADE', 'RESTRICT'
        );
    }
}
