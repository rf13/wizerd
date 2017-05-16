<?php

use yii\db\Migration;

class m151005_081322_change_custom_service_table extends Migration
{
    public function up()
    {
        $this->addColumn('custom_service', 'sort', $this->integer()->notNull());

        $this->dropForeignKey('fk_custom_service_bis_id', 'custom_service');
        $this->dropIndex('custom_service_bis_id_key', 'custom_service');
        $this->dropColumn('custom_service', 'bus_id');

        $this->dropForeignKey('fk_custom_service_cat_id', 'custom_service');
        $this->dropIndex('custom_service_cat_id_key', 'custom_service');
        $this->createIndex('custom_service_cat_id_key', 'custom_service', 'cat_id');
        $this->addForeignKey(
            'fk_custom_service_cat_id', 'custom_service', 'cat_id',
            'custom_category', 'id', 'CASCADE', 'RESTRICT'
        );

        $this->addColumn('custom_category', 'updated_at', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('custom_category', 'updated_at');

        $this->dropForeignKey('fk_custom_service_cat_id', 'custom_service');
        $this->dropIndex('custom_service_cat_id_key', 'custom_service');
        $this->createIndex('custom_service_cat_id_key', 'custom_service', 'cat_id');
        $this->addForeignKey(
            'fk_custom_service_cat_id', 'custom_service', 'cat_id',
            'category', 'id', 'CASCADE', 'RESTRICT'
        );

        $this->addColumn('custom_service', 'bus_id', $this->integer()->notNull());
        $this->createIndex('custom_service_bis_id_key', 'custom_service', 'bus_id');
        $this->addForeignKey(
            'fk_custom_service_bis_id', 'custom_service', 'bus_id',
            'business', 'id', 'CASCADE', 'RESTRICT'
        );

        $this->dropColumn('custom_service', 'sort');
    }
}
