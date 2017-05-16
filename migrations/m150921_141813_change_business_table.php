<?php

use yii\db\Migration;

class m150921_141813_change_business_table extends Migration
{
    public function up()
    {
        $this->alterColumn('business', 'name', $this->string());
    }

    public function down()
    {
        $this->alterColumn('business', 'name', $this->string()->notNull());
    }
}
