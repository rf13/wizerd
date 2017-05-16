<?php

use yii\db\Migration;

class m151006_161726_create_pricing_template_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('template', [
            'id' => $this->primaryKey(),
            'count' => $this->integer()->notNull(),
            'desc_type' => $this->integer(1)->notNull()->defaultValue(0)
        ], $tableOptions);
        $this->batchInsert('template', ['count', 'desc_type'], [
            [1, 0], [2, 0], [2, 1], [3, 0], [3, 1]
        ]);

        $this->addColumn('custom_service', 'temp_id', $this->integer()->notNull());
        $this->dropColumn('custom_service', 'description');
        $this->createIndex('custom_service_temp_id_key', 'custom_service', 'temp_id');
        $this->addForeignKey(
            'fk_custom_service_temp_id', 'custom_service', 'temp_id',
            'template', 'id', 'CASCADE', 'RESTRICT'
        );

        $this->createTable('additional_attribute', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()
        ], $tableOptions);
        $this->batchInsert('additional_attribute', ['title'], [
            ['Tier'], ['Time']
        ]);

        $this->createTable('attribute_value', [
            'id' => $this->primaryKey(),
            'attr_id' => $this->integer()->notNull(),
            'value' => $this->string()->notNull()
        ], $tableOptions);
        $this->createIndex('attribute_value_attr_id_key', 'attribute_value', 'attr_id');
        $this->addForeignKey(
            'fk_attribute_value_attr_id', 'attribute_value', 'attr_id',
            'additional_attribute', 'id', 'CASCADE', 'RESTRICT'
        );
        $this->batchInsert('attribute_value', ['attr_id', 'value'], [
            [1, 'Regular'], [1, 'Upgrade'], [1, 'Premium'],
            [2, '40 minutes'], [2, '75 minutes'], [2, '100 minutes']
        ]);

        $this->createTable('pricing', [
            'id' => $this->primaryKey(),
            'ser_id' => $this->integer()->notNull(),
            'id_attr_val' => $this->integer()->notNull(),
            'price' => $this->money(null, 2)->notNull(),
            'description' => $this->string()
        ], $tableOptions);
        $this->createIndex('pricing_ser_id_key', 'pricing', 'ser_id');
        $this->addForeignKey(
            'fk_pricing_ser_id', 'pricing', 'ser_id',
            'custom_service', 'id', 'CASCADE', 'RESTRICT'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_pricing_ser_id', 'pricing');
        $this->dropIndex('pricing_ser_id_key', 'pricing');
        $this->dropTable('pricing');

        $this->dropForeignKey('fk_attribute_value_attr_id', 'attribute_value');
        $this->dropIndex('attribute_value_attr_id_key', 'attribute_value');
        $this->dropTable('attribute_value');

        $this->dropTable('additional_attribute');

        $this->dropForeignKey('fk_custom_service_temp_id', 'custom_service');
        $this->dropIndex('custom_service_temp_id_key', 'custom_service');
        $this->dropColumn('custom_service', 'temp_id');
        $this->addColumn('custom_service', 'description', $this->string());
        $this->dropTable('template');
    }
}
