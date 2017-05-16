<?php

use yii\db\Schema;
use yii\db\Migration;

class m150827_135557_change_user_table extends Migration
{
    public function up()
    {
        $this->renameColumn('user', 'email_verification_code', 'verification_time');
        $this->alterColumn('user', 'verification_time', $this->integer(11));
    }

    public function down()
    {
        $this->renameColumn('user', 'verification_time', 'email_verification_code');
        $this->alterColumn('user', 'email_verification_code', $this->string());
    }
}
