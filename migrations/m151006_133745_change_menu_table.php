<?php

use yii\db\Migration;

class m151006_133745_change_menu_table extends Migration
{
    public function up()
    {
        $this->addColumn('industry', 'price', $this->integer(1)->defaultValue(0));
        $this->addColumn('industry', 'time', $this->integer(1)->defaultValue(0));
        $this->addColumn('industry', 'srv_title', $this->integer(1)->defaultValue(0));
        $this->addColumn('industry', 'srv_desc', $this->integer(1)->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('industry', 'price');
        $this->dropColumn('industry', 'time');
        $this->dropColumn('industry', 'srv_title');
        $this->dropColumn('industry', 'srv_desc');
    }
}
