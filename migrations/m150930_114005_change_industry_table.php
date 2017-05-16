<?php

use yii\db\Migration;

class m150930_114005_change_industry_table extends Migration
{
    public function up()
    {
        $this->addColumn('industry', 'disclaimer', $this->string());
    }

    public function down()
    {
        $this->dropColumn('industry', 'disclaimer');
    }
}
