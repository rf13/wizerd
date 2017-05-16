<?php

use yii\db\Migration;

class m151001_125552_change_category_table extends Migration
{
    public function up()
    {
        $this->addColumn('category', 'disclaimer', $this->string());
    }

    public function down()
    {
        $this->dropColumn('category', 'disclaimer');
    }
}
