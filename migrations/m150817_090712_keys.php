<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_090712_keys extends Migration
{
    public function up()
    {
        /* Indexes for table `business` */
        $this->createIndex('business_user_id_key', 'business', 'user_id');
        $this->addForeignKey('fk_business_user_id', 'business', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `category` */
        $this->createIndex('category_ind_id_key', 'category', 'ind_id');
        $this->addForeignKey('fk_category_ind_id', 'category', 'ind_id', 'industry', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `composition` */
        $this->createIndex('composition_serv_id_key', 'composition', 'serv_id');
        $this->createIndex('composition_attr_id_key', 'composition', 'attr_id');
        $this->addForeignKey('fk_composition_attr_id', 'composition', 'attr_id', 'attribute', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_composition_serv_id', 'composition', 'serv_id', 'service', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `consumer` */
        $this->createIndex('consumer_user_id_key', 'consumer', 'user_id');
        $this->addForeignKey('fk_consumer_ind_id', 'consumer', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `location` */
        $this->createIndex('location_zip_id_key', 'location', 'zip_id');
        $this->createIndex('location_ind_id_key', 'location', 'ind_id');
        $this->addForeignKey('fk_location_ind_id', 'location', 'ind_id', 'industry', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_location_zip_id', 'location', 'zip_id', 'zip_code', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `menu` */
        $this->createIndex('menu_bus_id_key', 'menu', 'bus_id');
        $this->createIndex('menu_serv_id_key', 'menu', 'serv_id');
        $this->addForeignKey('fk_menu_bus_id', 'menu', 'bus_id', 'business', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_menu_serv_id', 'menu', 'serv_id', 'service', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `operation` */
        $this->createIndex('operation_bus_id_key', 'operation', 'bus_id');
        $this->addForeignKey('fk_operation_bus_id', 'operation', 'bus_id', 'business', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `photo` */
        $this->createIndex('photo_bus_id_key', 'photo', 'bus_id');
        $this->addForeignKey('fk_photo_bus_id', 'photo', 'bus_id', 'business', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `promo` */
        $this->createIndex('promo_serv_id_key', 'promo', 'serv_id');
        $this->addForeignKey('fk_promo_serv_id', 'promo', 'serv_id', 'service', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `service` */
        $this->createIndex('service_cat_id_key', 'service', 'cat_id');
        $this->addForeignKey('fk_service_cat_id', 'service', 'cat_id', 'category', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `staff` */
        $this->createIndex('staff_bus_id_key', 'staff', 'bus_id');
        $this->addForeignKey('fk_staff_bus_id', 'staff', 'bus_id', 'business', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `user` */
        $this->createIndex('user_zip_id_key', 'user', 'zip_id');
        $this->createIndex('user_role_id_key', 'user', 'role_id');
        $this->addForeignKey('fk_user_role_id', 'user', 'role_id', 'role', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_user_zip_id', 'user', 'zip_id', 'zip_code', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `video` */
        $this->createIndex('video_bus_id_key', 'video', 'bus_id');
        $this->addForeignKey('fk_video_bus_id', 'video', 'bus_id', 'business', 'id', 'CASCADE', 'RESTRICT');
        /* Indexes for table `wish_list` */
        $this->createIndex('wish_promo_id_key', 'wish_list', 'promo_id');
        $this->createIndex('wish_user_id_key', 'wish_list', 'user_id');
        $this->addForeignKey('fk_wish_user_id', 'wish_list', 'user_id', 'consumer', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_wish_promo_id', 'wish_list', 'promo_id', 'promo', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_business_user_id', 'business');
        $this->dropIndex('business_user_id_key', 'business');

        $this->dropForeignKey('fk_category_ind_id', 'category');
        $this->dropIndex('category_ind_id_key', 'category');

        $this->dropForeignKey('fk_composition_attr_id', 'composition');
        $this->dropForeignKey('fk_composition_serv_id', 'composition');
        $this->dropIndex('composition_serv_id_key', 'composition');
        $this->dropIndex('composition_attr_id_key', 'composition');
        
        $this->dropForeignKey('fk_consumer_ind_id', 'consumer');
        $this->dropIndex('consumer_user_id_key', 'consumer');
        
        $this->dropForeignKey('fk_location_ind_id', 'location');
        $this->dropForeignKey('fk_location_zip_id', 'location');
        $this->dropIndex('location_zip_id_key', 'location');
        $this->dropIndex('location_ind_id_key', 'location');
        
        $this->dropForeignKey('fk_menu_bus_id', 'menu');
        $this->dropForeignKey('fk_menu_serv_id', 'menu');
        $this->dropIndex('menu_bus_id_key', 'menu');
        $this->dropIndex('menu_serv_id_key', 'menu');
        
        $this->dropForeignKey('fk_operation_bus_id', 'operation');
        $this->dropIndex('operation_bus_id_key', 'operation');
        
        $this->dropForeignKey('fk_photo_bus_id', 'photo');
        $this->dropIndex('photo_bus_id_key', 'photo');
        
        $this->dropForeignKey('fk_promo_serv_id', 'promo');
        $this->dropIndex('promo_serv_id_key', 'promo');
        
        $this->dropForeignKey('fk_service_cat_id', 'service');
        $this->dropIndex('service_cat_id_key', 'service');
        
        $this->dropForeignKey('fk_staff_bus_id', 'staff');
        $this->dropIndex('staff_bus_id_key', 'staff');
        
        $this->dropForeignKey('fk_user_role_id', 'user');
        $this->dropForeignKey('fk_user_zip_id', 'user');
        $this->dropIndex('user_zip_id_key', 'user');
        $this->dropIndex('user_role_id_key', 'user');
        
        $this->dropForeignKey('fk_video_bus_id', 'video');
        $this->dropIndex('video_bus_id_key', 'video');
        
        $this->dropForeignKey('fk_wish_user_id', 'wish_list');
        $this->dropForeignKey('fk_wish_promo_id', 'wish_list');
        $this->dropIndex('wish_promo_id_key', 'wish_list');
        $this->dropIndex('wish_user_id_key', 'wish_list');
    }
}
