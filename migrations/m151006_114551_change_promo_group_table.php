<?php

use yii\db\Migration;

class m151006_114551_change_promo_group_table extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_promo_group_cat_id', 'promo_group');
        $this->addForeignKey(
            'fk_promo_group_cat_id', 'promo_group', 'cat_id',
            'custom_category', 'id', 'CASCADE', 'RESTRICT'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_promo_group_cat_id', 'promo_group');
        $this->addForeignKey(
            'fk_promo_group_cat_id', 'promo_group', 'cat_id',
            'category', 'id', 'CASCADE', 'RESTRICT'
        );
    }
}
