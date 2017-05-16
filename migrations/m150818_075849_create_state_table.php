<?php

use yii\db\Schema;
use yii\db\Migration;

class m150818_075849_create_state_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('state', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer()->notNull(),
            'code' => $this->string(3)->notNull(),
            'name' => $this->string()->notNull()
        ], $tableOptions);

        $this->createIndex('state_country_id_key', 'state', 'country_id');
        $this->addForeignKey('fk_state_country_id', 'state', 'country_id', 'country', 'id', 'CASCADE', 'RESTRICT');

        $this->batchInsert('state', ['country_id', 'code', 'name'], [
            [223, 'AL', 'Alabama'],
            [223, 'AK', 'Alaska'],
            [223, 'AS', 'American Samoa'],
            [223, 'AZ', 'Arizona'],
            [223, 'AR', 'Arkansas'],
            [223, 'AF', 'Armed Forces Africa'],
            [223, 'AA', 'Armed Forces Americas'],
            [223, 'AC', 'Armed Forces Canada'],
            [223, 'AE', 'Armed Forces Europe'],
            [223, 'AM', 'Armed Forces Middle East'],
            [223, 'AP', 'Armed Forces Pacific'],
            [223, 'CA', 'California'],
            [223, 'CO', 'Colorado'],
            [223, 'CT', 'Connecticut'],
            [223, 'DE', 'Delaware'],
            [223, 'DC', 'District of Columbia'],
            [223, 'FM', 'Federated States Of Micronesia'],
            [223, 'FL', 'Florida'],
            [223, 'GA', 'Georgia'],
            [223, 'GU', 'Guam'],
            [223, 'HI', 'Hawaii'],
            [223, 'ID', 'Idaho'],
            [223, 'IL', 'Illinois'],
            [223, 'IN', 'Indiana'],
            [223, 'IA', 'Iowa'],
            [223, 'KS', 'Kansas'],
            [223, 'KY', 'Kentucky'],
            [223, 'LA', 'Louisiana'],
            [223, 'ME', 'Maine'],
            [223, 'MH', 'Marshall Islands'],
            [223, 'MD', 'Maryland'],
            [223, 'MA', 'Massachusetts'],
            [223, 'MI', 'Michigan'],
            [223, 'MN', 'Minnesota'],
            [223, 'MS', 'Mississippi'],
            [223, 'MO', 'Missouri'],
            [223, 'MT', 'Montana'],
            [223, 'NE', 'Nebraska'],
            [223, 'NV', 'Nevada'],
            [223, 'NH', 'New Hampshire'],
            [223, 'NJ', 'New Jersey'],
            [223, 'NM', 'New Mexico'],
            [223, 'NY', 'New York'],
            [223, 'NC', 'North Carolina'],
            [223, 'ND', 'North Dakota'],
            [223, 'MP', 'Northern Mariana Islands'],
            [223, 'OH', 'Ohio'],
            [223, 'OK', 'Oklahoma'],
            [223, 'OR', 'Oregon'],
            [223, 'PW', 'Palau'],
            [223, 'PA', 'Pennsylvania'],
            [223, 'PR', 'Puerto Rico'],
            [223, 'RI', 'Rhode Island'],
            [223, 'SC', 'South Carolina'],
            [223, 'SD', 'South Dakota'],
            [223, 'TN', 'Tennessee'],
            [223, 'TX', 'Texas'],
            [223, 'UT', 'Utah'],
            [223, 'VT', 'Vermont'],
            [223, 'VI', 'Virgin Islands'],
            [223, 'VA', 'Virginia'],
            [223, 'WA', 'Washington'],
            [223, 'WV', 'West Virginia'],
            [223, 'WI', 'Wisconsin'],
            [223, 'WY', 'Wyoming']
        ]);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_state_country_id', 'state');
        $this->dropIndex('state_country_id_key', 'state');

        $this->dropTable('state');
    }
}
