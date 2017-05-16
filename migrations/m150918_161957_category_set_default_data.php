<?php

use yii\db\Migration;

class m150918_161957_category_set_default_data extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('category', ['ind_id', 'title', 'description', 'display'], [
            [1, 'Female', null, 1],
            [1, 'Male', null, 1],
            [2, 'Deep Tissue', null, 1],
            [2, 'Swedish', null, 1],
            [2, 'Thai', null, 1],
            [2, 'Hot Stone', null, 1],
            [3, 'Cold wax', null, 1],
            [3, 'Warm wax', null, 1],
            [3, 'Hot wax', null, 1]
        ]);
        $this->batchInsert('service', ['cat_id', 'title', 'description'], [
            [1, 'Cut', null],
            [1, 'Color', null],
            [1, 'Highlights', null],
            [1, 'Keratin', null],
            [1, 'Blowout', null],
            [2, 'Cut', null],
            [2, 'Wash and Dry', null],
            [3, '30 minutes', null],
            [3, '60 minutes', null],
            [3, '90 minutes', null],
            [3, '120 minutes', null],
            [7, 'Choco-Milka', null],
            [7, 'Wax Bar', null],
            [7, 'Epil', null],
            [7, 'Beauty Image', null],
            [8, 'Byly Depil', null],
            [8, 'Lyconproducts', null],
            [8, 'Amie', null],
            [8, 'New Style', null],
            [8, 'View Topic', null],
            [9, 'Depileve', null],
            [9, 'Perron Rigot', null],
            [9, 'Yabb', null],
            [9, 'Ehu', null],
            [9, 'Irisk Professional', null]
        ]);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_category_ind_id', 'category');
        $this->dropForeignKey('fk_service_cat_id', 'service');
        $this->dropForeignKey('fk_promo_serv_id', 'promo');
        $this->dropForeignKey('fk_custom_service_cat_id', 'custom_service');

        $this->truncateTable('category');
        $this->truncateTable('service');

        $this->addForeignKey(
            'fk_category_ind_id', 'category', 'ind_id', 'industry', 'id', 'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey(
            'fk_service_cat_id', 'service', 'cat_id', 'category', 'id', 'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey(
            'fk_promo_serv_id', 'promo', 'serv_id', 'service', 'id', 'CASCADE', 'RESTRICT'
        );
        $this->addForeignKey(
            'fk_custom_service_cat_id', 'custom_service', 'cat_id', 'category', 'id', 'CASCADE', 'RESTRICT'
        );
    }
}
