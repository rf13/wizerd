<?php

use yii\db\Migration;

class m151005_150607_change_custom_service_table extends Migration
{
    public function up()
    {
        $this->addColumn('custom_service', 'type', $this->integer(1)->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('custom_service', 'type');
    }
}
