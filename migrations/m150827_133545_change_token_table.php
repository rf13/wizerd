<?php

use yii\db\Schema;
use yii\db\Migration;

class m150827_133545_change_token_table extends Migration
{
    public function up()
    {
        $this->alterColumn('token', 'created_at', $this->integer()->notNull());
        $this->alterColumn('token', 'updated_at', $this->integer());
    }

    public function down()
    {
        $this->alterColumn('token', 'created_at', $this->datetime()->notNull());
        $this->alterColumn('token', 'updated_at', $this->datetime());
    }
}
