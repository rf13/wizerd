<?php

use yii\db\Migration;

class m160523_120332_change_industry_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('industry', 'sort_order', $this->smallInteger()->notNull()->defaultValue(0));
        $this->createIndex('industry_sort_order', 'industry', 'sort_order');
    }

    public function safeDown()
    {
        echo "m160523_120332_change_industry_table cannot be reverted.\n";
        $this->dropColumn('industry', 'sort_order');
    }
}
