<?php

use yii\db\Migration;

class m150928_162654_change_promo_group_table extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_promo_group_menu_id', 'promo_group');
        $this->dropIndex('promo_group_menu_id_key', 'promo_group');
        $this->dropColumn('promo_group', 'menu_id');

        $this->addColumn('promo', 'bus_id', $this->integer()->notNull());
        $this->createIndex('promo_bus_id_key', 'promo', 'bus_id');
        $this->addForeignKey('fk_promo_bus_id', 'promo', 'bus_id', 'business', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->addColumn('promo_group', 'menu_id', $this->integer()->notNull());
        $this->createIndex('promo_group_menu_id_key', 'promo_group', 'menu_id');
        $this->addForeignKey(
            'fk_promo_group_menu_id', 'promo_group', 'menu_id',
            'menu', 'id', 'CASCADE', 'RESTRICT'
        );

        $this->dropForeignKey('fk_promo_bus_id', 'promo');
        $this->dropIndex('promo_bus_id_key', 'promo');
        $this->dropColumn('promo', 'bus_id');
    }
}
