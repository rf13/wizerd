<?php

use yii\db\Migration;

class m150917_085916_change_industry_table extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_business_ind_id', 'business');
        $this->dropIndex('business_ind_id_key', 'business');
        $this->dropColumn('business', 'main_ind_id');

        $this->dropForeignKey('fk_menu_serv_id', 'menu');
        $this->dropIndex('menu_serv_id_key', 'menu');
        $this->dropColumn('menu', 'serv_id');
        $this->dropColumn('menu', 'price');

        $this->addColumn('menu', 'ind_id', $this->integer()->notNull());
        $this->createIndex('menu_ind_id_key', 'menu', 'ind_id');
        $this->addForeignKey('fk_ind_id_id', 'menu', 'ind_id', 'industry', 'id', 'CASCADE', 'RESTRICT');
        $this->addColumn('menu', 'main', $this->smallInteger(1)->notNull()->defaultValue(0));
        $this->addColumn('menu', 'disclaimer', $this->text());
    }

    public function down()
    {
        $this->dropColumn('menu', 'disclaimer');
        $this->dropColumn('menu', 'main');
        $this->dropForeignKey('fk_ind_id_id', 'menu');
        $this->dropIndex('menu_ind_id_key', 'menu');
        $this->dropColumn('menu', 'ind_id');

        $this->addColumn('business', 'main_ind_id', $this->integer()->notNull());
        $this->createIndex('business_ind_id_key', 'business', 'main_ind_id');
        $this->addForeignKey(
            'fk_business_ind_id', 'business', 'main_ind_id', 'industry', 'id', 'CASCADE', 'RESTRICT'
        );
        $this->addColumn('menu', 'price', $this->float());
        $this->addColumn('menu', 'serv_id', $this->integer()->notNull());
        $this->createIndex('menu_serv_id_key', 'menu', 'serv_id');
        $this->addForeignKey('fk_menu_serv_id', 'menu', 'serv_id', 'service', 'id', 'CASCADE', 'RESTRICT');
    }
}
