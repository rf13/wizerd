<?php

use yii\db\Schema;
use yii\db\Migration;

class m150821_083929_change_zip_code_table extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('fk_business_zip_id', 'business');
        $this->dropIndex('business_zip_id_key', 'business');
        $this->dropForeignKey('fk_location_zip_id', 'location');
        $this->dropIndex('location_zip_id_key', 'location');

        $this->truncateTable('zip_code');

        $this->dropColumn('business', 'city');
        $this->dropColumn('business', 'state');
        $this->alterColumn('business', 'address', $this->string());
        $this->alterColumn('business', 'phone', $this->string());
        $this->alterColumn('business', 'zip_id', $this->integer());
        $this->addColumn('business', 'zip_notice', $this->string());

        $this->createIndex('business_zip_id_key', 'business', 'zip_id');
        $this->addForeignKey('fk_business_zip_id', 'business', 'zip_id', 'zip_code', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('location_zip_id_key', 'location', 'zip_id');
        $this->addForeignKey('fk_location_zip_id', 'location', 'zip_id', 'zip_code', 'id', 'CASCADE', 'RESTRICT');

        $this->alterColumn('zip_code', 'zip', $this->string()->notNull());
        $this->batchInsert('zip_code', ['zip', 'city_id', 'active'], [
            ['00601', 1, 1],  ['00602', 2, 1],  ['00603', 3, 1],  ['00604', 3, 1],  ['00605', 3, 1],
            ['00606', 4, 1],  ['00610', 5, 1],  ['00611', 6, 1],  ['00612', 7, 1],  ['00613', 7, 1],
            ['00614', 7, 1],  ['00616', 8, 1],  ['00617', 9, 1],  ['00622', 10, 1], ['00623', 11, 1],
            ['00624', 12, 1], ['00627', 13, 1], ['00631', 14, 1], ['00636', 15, 1], ['00637', 16, 1],
            ['00638', 17, 1], ['00641', 18, 1], ['00646', 19, 1], ['00647', 20, 1], ['00650', 21, 1],
            ['00652', 22, 1], ['00653', 23, 1], ['00656', 24, 1], ['00659', 25, 1], ['00660', 26, 1],
            ['00662', 27, 1], ['00664', 28, 1], ['00667', 29, 1], ['00669', 30, 1], ['00670', 31, 1],
            ['00674', 32, 1], ['00676', 33, 1], ['00677', 34, 1], ['00678', 35, 1], ['00680', 36, 1],
            ['00681', 36, 1], ['00682', 36, 1], ['00683', 37, 1], ['00685', 38, 1], ['00687', 39, 1],
            ['00688', 40, 1], ['00690', 41, 1], ['00692', 42, 1], ['00693', 43, 1], ['00694', 43, 1],
            ['00698', 44, 1], ['00703', 45, 1], ['00704', 46, 1], ['00705', 47, 1], ['00707', 48, 1],
            ['00714', 49, 1], ['00715', 50, 1], ['00716', 51, 1], ['00717', 51, 1], ['00718', 52, 1],
            ['00719', 53, 1], ['00720', 54, 1], ['00721', 55, 1], ['00723', 56, 1], ['00725', 57, 1],
            ['00726', 57, 1], ['00727', 57, 1], ['00728', 51, 1], ['00729', 58, 1], ['00730', 51, 1],
            ['00731', 51, 1], ['00732', 51, 1], ['00733', 51, 1], ['00734', 51, 1], ['00735', 59, 1],
            ['00736', 60, 1], ['00737', 60, 1], ['00738', 61, 1], ['00739', 62, 1], ['00740', 63, 1],
            ['00741', 64, 1], ['00742', 65, 1], ['00744', 66, 1], ['00745', 67, 1], ['00751', 68, 1],
            ['00754', 69, 1], ['00757', 70, 1], ['00765', 71, 1], ['00766', 72, 1], ['00767', 73, 1],
            ['00769', 74, 1], ['00771', 75, 1], ['00772', 76, 1], ['00773', 77, 1], ['00775', 78, 1],
            ['00777', 79, 1], ['00778', 80, 1], ['00780', 81, 1], ['00782', 82, 1], ['00783', 83, 1],
            ['00784', 84, 1], ['00785', 84, 1], ['00786', 85, 1], ['00791', 86, 1], ['00792', 86, 1],
            ['00794', 87, 1], ['00795', 88, 1]
        ]);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_business_zip_id', 'business');
        $this->dropIndex('business_zip_id_key', 'business');
        $this->dropForeignKey('fk_location_zip_id', 'location');
        $this->dropIndex('location_zip_id_key', 'location');

        $this->truncateTable('zip_code');

        $this->addColumn('business', 'city', $this->string()->notNull());
        $this->addColumn('business', 'state', $this->string()->notNull());
        $this->alterColumn('business', 'address', $this->string()->notNull());
        $this->alterColumn('business', 'phone', $this->string()->notNull());
        $this->alterColumn('business', 'zip_id', $this->integer()->notNull());
        $this->dropColumn('business', 'zip_notice');

        $this->createIndex('business_zip_id_key', 'business', 'zip_id');
        $this->addForeignKey('fk_business_zip_id', 'business', 'zip_id', 'zip_code', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('location_zip_id_key', 'location', 'zip_id');
        $this->addForeignKey('fk_location_zip_id', 'location', 'zip_id', 'zip_code', 'id', 'CASCADE', 'RESTRICT');

        $this->alterColumn('zip_code', 'zip', $this->integer()->notNull());
    }
}