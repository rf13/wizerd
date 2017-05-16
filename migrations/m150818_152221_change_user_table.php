<?php

use yii\db\Schema;
use yii\db\Migration;

class m150818_152221_change_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'is_email_verified', $this->smallInteger(1)->notNull()->defaultValue(0));
        $this->addColumn('user', 'email_verification_code', $this->string());
        $this->addColumn('user', 'auth_key', $this->string());

        $this->dropForeignKey('fk_user_zip_id', 'user');
        $this->dropIndex('user_zip_id_key', 'user');
        $this->dropColumn('user', 'zip_id');

        $this->addColumn('business', 'zip_id', $this->integer()->notNull());
        $this->createIndex('business_zip_id_key', 'business', 'zip_id');
        $this->addForeignKey('fk_business_zip_id', 'business', 'zip_id', 'zip_code', 'id', 'CASCADE', 'RESTRICT');

        $this->addColumn('business', 'main_ind_id', $this->integer()->notNull());
        $this->createIndex('business_ind_id_key', 'business', 'main_ind_id');
        $this->addForeignKey('fk_business_ind_id', 'business', 'main_ind_id', 'industry', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_business_ind_id', 'business');
        $this->dropIndex('business_ind_id_key', 'business');
        $this->dropColumn('business', 'main_ind_id');

        $this->dropForeignKey('fk_business_zip_id', 'business');
        $this->dropIndex('business_zip_id_key', 'business');
        $this->dropColumn('business', 'zip_id');

        $this->addColumn('user', 'zip_id', $this->integer()->notNull());
        $this->createIndex('user_zip_id_key', 'user', 'zip_id');
        $this->addForeignKey('fk_user_zip_id', 'user', 'zip_id', 'zip_code', 'id', 'CASCADE', 'RESTRICT');

        $this->dropColumn('user', 'auth_key');
        $this->dropColumn('user', 'is_email_verified');
        $this->dropColumn('user', 'email_verification_code');
    }
}