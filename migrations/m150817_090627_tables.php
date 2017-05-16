<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_090627_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('attribute', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING
        ], $tableOptions);

        $this->createTable('business', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'address' => Schema::TYPE_STRING . ' NOT NULL',
            'suite' => Schema::TYPE_STRING,
            'city' => Schema::TYPE_STRING . ' NOT NULL',
            'state' => Schema::TYPE_STRING . ' NOT NULL',
            'phone' => Schema::TYPE_STRING . ' NOT NULL',
            'website' => Schema::TYPE_STRING,
            'contact_email' => Schema::TYPE_STRING,
            'description' => Schema::TYPE_TEXT
        ], $tableOptions);

        $this->createTable('category', [
            'id' => Schema::TYPE_PK,
            'ind_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING,
            'display' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE'
        ], $tableOptions);

        $this->createTable('composition', [
            'id' => Schema::TYPE_PK,
            'serv_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'attr_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);

        $this->createTable('consumer', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'nickname' => Schema::TYPE_STRING . ' NOT NULL',
            'first_name' => Schema::TYPE_STRING . ' NOT NULL',
            'last_name' => Schema::TYPE_STRING . ' NOT NULL'
        ], $tableOptions);

        $this->createTable('industry', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING,
            'display' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE'
        ], $tableOptions);

        $this->createTable('location', [
            'id' => Schema::TYPE_PK,
            'zip_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'ind_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);

        $this->createTable('menu', [
            'id' => Schema::TYPE_PK,
            'bus_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'serv_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'price' => Schema::TYPE_FLOAT . ' NOT NULL',
            'description' => Schema::TYPE_TEXT
        ], $tableOptions);

        $this->createTable('operation', [
            'id' => Schema::TYPE_PK,
            'bus_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'day' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'open' => Schema::TYPE_TIME . ' NOT NULL',
            'end' => Schema::TYPE_TIME . ' NOT NULL',
            'active' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE'
        ], $tableOptions);

        $this->createTable('photo', [
            'id' => Schema::TYPE_PK,
            'bus_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'url' => Schema::TYPE_STRING . ' NOT NULL',
            'title' => Schema::TYPE_STRING,
            'main' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE'
        ], $tableOptions);

        $this->createTable('promo', [
            'id' => Schema::TYPE_PK,
            'serv_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'price' => Schema::TYPE_FLOAT,
            'discount' => Schema::TYPE_SMALLINT,
            'nco' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
            'combine' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
            'start' => Schema::TYPE_DATE . ' NOT NULL',
            'end' => Schema::TYPE_DATE . ' NOT NULL',
            'terms' => Schema::TYPE_STRING,
            'active' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE'
        ], $tableOptions);

        $this->createTable('role', [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING,
            'active' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE'
        ], $tableOptions);

        $this->createTable('service', [
            'id' => Schema::TYPE_PK,
            'cat_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING
        ], $tableOptions);

        $this->createTable('staff', [
            'id' => Schema::TYPE_PK,
            'bus_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING . ' NOT NULL',
            'url' => Schema::TYPE_STRING . ' NOT NULL',
            'sort' => Schema::TYPE_INTEGER
        ], $tableOptions);

        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'role_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'zip_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'password' => Schema::TYPE_STRING . ' NOT NULL'
        ], $tableOptions);

        $this->createTable('video', [
            'id' => Schema::TYPE_PK,
            'bus_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'url' => Schema::TYPE_STRING . ' NOT NULL',
            'title' => Schema::TYPE_STRING,
            'main' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE'
        ], $tableOptions);

        $this->createTable('wish_list', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'promo_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'added' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT NOW()'
        ], $tableOptions);

        $this->createTable('zip_code', [
            'id' => Schema::TYPE_PK,
            'zip' => Schema::TYPE_INTEGER . ' NOT NULL',
            'city' => Schema::TYPE_STRING,
            'state' => Schema::TYPE_STRING,
            'state_code' => Schema::TYPE_STRING,
            'country' => Schema::TYPE_STRING,
            'latitude' => Schema::TYPE_FLOAT,
            'longitude' => Schema::TYPE_FLOAT,
            'active' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('attribute');
        $this->dropTable('business');
        $this->dropTable('category');
        $this->dropTable('composition');
        $this->dropTable('consumer');
        $this->dropTable('industry');
        $this->dropTable('location');
        $this->dropTable('menu');
        $this->dropTable('operation');
        $this->dropTable('photo');
        $this->dropTable('promo');
        $this->dropTable('role');
        $this->dropTable('service');
        $this->dropTable('staff');
        $this->dropTable('user');
        $this->dropTable('video');
        $this->dropTable('wish_list');
        $this->dropTable('zip_code');
    }
}
