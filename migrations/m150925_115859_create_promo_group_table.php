<?php

use yii\db\Migration;

class m150925_115859_create_promo_group_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('promo_group', [
            'id' => $this->primaryKey(),
            'promo_id' => $this->integer()->notNull(),
            'menu_id' => $this->integer()->notNull(),
            'cat_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex('promo_group_promo_id_key', 'promo_group', 'promo_id');
        $this->addForeignKey(
            'fk_promo_group_promo_id', 'promo_group', 'promo_id',
            'promo', 'id', 'CASCADE', 'RESTRICT'
        );

        $this->createIndex('promo_group_menu_id_key', 'promo_group', 'menu_id');
        $this->addForeignKey(
            'fk_promo_group_menu_id', 'promo_group', 'menu_id',
            'menu', 'id', 'CASCADE', 'RESTRICT'
        );

        $this->createIndex('promo_group_cat_id_key', 'promo_group', 'cat_id');
        $this->addForeignKey(
            'fk_promo_group_cat_id', 'promo_group', 'cat_id',
            'category', 'id', 'CASCADE', 'RESTRICT'
        );

        $this->dropForeignKey('fk_promo_serv_id', 'promo');
        $this->dropIndex('promo_serv_id_key', 'promo');
        $this->dropColumn('promo', 'serv_id');
        $this->dropColumn('promo', 'price');

        $this->addColumn('promo', 'round', $this->smallInteger(1)->notNull());
    }

    public function down()
    {
        $this->dropForeignKey('fk_promo_group_cat_id', 'promo_group');
        $this->dropIndex('promo_group_cat_id_key', 'promo_group');

        $this->dropForeignKey('fk_promo_group_menu_id', 'promo_group');
        $this->dropIndex('promo_group_menu_id_key', 'promo_group');

        $this->dropForeignKey('fk_promo_group_promo_id', 'promo_group');
        $this->dropIndex('promo_group_promo_id_key', 'promo_group');

        $this->dropTable('promo_group');

        $this->dropColumn('promo', 'round');
        $this->addColumn('promo', 'price', $this->float());
        $this->addColumn('promo', 'serv_id', $this->integer()->notNull());
        $this->createIndex('promo_serv_id_key', 'promo', 'serv_id');
        $this->addForeignKey('fk_promo_serv_id', 'promo', 'serv_id', 'service', 'id', 'CASCADE', 'RESTRICT');
    }
}
