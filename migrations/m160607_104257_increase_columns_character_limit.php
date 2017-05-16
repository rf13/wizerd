<?php

use yii\db\Migration;

class m160607_104257_increase_columns_character_limit extends Migration
{
    public function up()
    {
        $this->alterColumn('menu', 'title', 'varchar(100)');
        $this->alterColumn('custom_service', 'title', 'varchar(90)');
        $this->alterColumn('custom_category', 'title', 'varchar(100)');
        $this->alterColumn('custom_category', 'description', 'varchar(1000)');
        $this->alterColumn('custom_category', 'disclaimer', 'varchar(1000)');
    }

    public function down()
    {
        echo "m160607_104257_increase_columns_charachter_limit cannot be reverted.\n";

        return true;
    }
}
