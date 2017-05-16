<?php

use yii\db\Schema;
use yii\db\Migration;

class m150821_092051_industry_set_default_data extends Migration
{
    public function safeUp()
    {

        $this->batchInsert('industry', ['title', 'description', 'display'], [
            ['Haircut', null, 1],
            ['Massage', null, 1],
            ['Waxing', null, 1]
        ]);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_business_ind_id', 'business');
        $this->dropForeignKey('fk_category_ind_id', 'category');
        $this->dropForeignKey('fk_location_ind_id', 'location');
        $this->truncateTable('industry');
        $this->addForeignKey('fk_location_ind_id', 'location', 'ind_id', 'industry', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_category_ind_id', 'category', 'ind_id', 'industry', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_business_ind_id', 'business', 'main_ind_id', 'industry', 'id', 'CASCADE', 'RESTRICT');
    }
}
