<?php

use yii\db\Migration;

class m150902_093127_change_business_table extends Migration
{
    public function up()
    {
        $this->addColumn('business', 'is_home', $this->smallInteger(1)->notNull()->defaultValue(0));
        $this->addColumn('business', 'yelp_url', $this->string());
    }

    public function down()
    {
        $this->dropColumn('business', 'is_home');
        $this->dropColumn('business', 'yelp_url');
    }
}
