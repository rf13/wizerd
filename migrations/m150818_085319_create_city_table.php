<?php

use yii\db\Schema;
use yii\db\Migration;

class m150818_085319_create_city_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('city', [
            'id' => $this->primaryKey(),
            'state_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'latitude' => $this->float(2)->notNull(),
            'longitude' => $this->float(2)->notNull(),
            'active' => $this->smallInteger()->notNull()->defaultValue(0)
        ], $tableOptions);

        $this->createIndex('city_state_id_key', 'city', 'state_id');
        $this->addForeignKey('fk_city_state_id', 'city', 'state_id', 'state', 'id', 'CASCADE', 'RESTRICT');

        $this->batchInsert('city', ['state_id', 'name', 'latitude', 'longitude', 'active'], [
            [52, 'Adjuntas', 18.16, -66.72, 1],
            [52, 'Aguada', 18.38, -67.18, 1],
            [52, 'Aguadilla', 18.43, -67.15, 1],
            [52, 'Maricao', 18.18, -66.98, 1],
            [52, 'Anasco', 18.28, -67.14, 1],
            [52, 'Angeles', 18.28, -66.79, 1],
            [52, 'Arecibo', 18.45, -66.73, 1],
            [52, 'Bajadero', 18.4, -66.66, 1],
            [52, 'Barceloneta', 18.45, -66.53, 1],
            [52, 'Boqueron', 17.99, -67.15, 1],
            [52, 'Cabo Rojo', 18.08, -67.14, 1],
            [52, 'Penuelas', 18.06, -66.72, 1],
            [52, 'Camuy', 18.48, -66.84, 1],
            [52, 'Castaner', 18.19, -66.82, 1],
            [52, 'Rosario', 18.15, -67.06, 1],
            [52, 'Sabana Grande', 18.08, -66.96, 1],
            [52, 'Ciales', 18.33, -66.47, 1],
            [52, 'Utuado', 18.27, -66.7, 1],
            [52, 'Dorado', 18.47, -66.27, 1],
            [52, 'Ensenada', 17.96, -66.94, 1],
            [52, 'Florida', 18.36, -66.56, 1],
            [52, 'Garrochales', 18.45, -66.6, 1],
            [52, 'Guanica', 17.97, -66.93, 1],
            [52, 'Guayanilla', 18.02, -66.79, 1],
            [52, 'Hatillo', 18.48, -66.82, 1],
            [52, 'Hormigueros', 18.14, -67.12, 1],
            [52, 'Isabela', 18.5, -67.02, 1],
            [52, 'Jayuya', 18.22, -66.59, 1],
            [52, 'Lajas', 18.04, -67.06, 1],
            [52, 'Lares', 18.29, -66.88, 1],
            [52, 'Las Marias', 18.27, -67.06, 1],
            [52, 'Manati', 18.43, -66.48, 1],
            [52, 'Moca', 18.39, -67.11, 1],
            [52, 'Rincon', 18.34, -67.25, 1],
            [52, 'Quebradillas', 18.47, -66.93, 1],
            [52, 'Mayaguez', 18.2, -67.14, 1],
            [52, 'San German', 18.08, -67.04, 1],
            [52, 'San Sebastian', 18.33, -66.99, 1],
            [52, 'Morovis', 18.32, -66.4, 1],
            [52, 'Sabana Hoyos', 18.38, -66.62, 1],
            [52, 'San Antonio', 18.49, -67.09, 1],
            [52, 'Vega Alta', 18.41, -66.32, 1],
            [52, 'Vega Baja', 18.44, -66.39, 1],
            [52, 'Yauco', 18.03, -66.86, 1],
            [52, 'Aguas Buenas', 18.25, -66.1, 1],
            [52, 'Aguirre', 17.96, -66.22, 1],
            [52, 'Aibonito', 18.14, -66.26, 1],
            [52, 'Maunabo', 18, -65.9, 1],
            [52, 'Arroyo', 17.97, -66.06, 1],
            [52, 'Mercedita', 18, -66.56, 1],
            [52, 'Ponce', 17.98, -66.6, 1],
            [52, 'Naguabo', 18.21, -65.73, 1],
            [52, 'Naranjito', 18.3, -66.24, 1],
            [52, 'Orocovis', 18.22, -66.39, 1],
            [52, 'Palmer', 18.37, -65.77, 1],
            [52, 'Patillas', 18, -66.01, 1],
            [52, 'Caguas', 18.23, -66.03, 1],
            [52, 'Canovanas', 18.37, -65.9, 1],
            [52, 'Ceiba', 18.26, -65.64, 1],
            [52, 'Cayey', 18.11, -66.16, 1],
            [52, 'Fajardo', 18.33, -65.65, 1],
            [52, 'Cidra', 18.17, -66.15, 1],
            [52, 'Puerto Real', 18.33, -65.63, 1],
            [52, 'Punta Santiago', 18.15, -65.76, 1],
            [52, 'Roosevelt Roads', 18.27, -65.65, 1],
            [52, 'Rio Blanco', 18.22, -65.79, 1],
            [52, 'Rio Grande', 18.38, -65.83, 1],
            [52, 'Salinas', 17.97, -66.29, 1],
            [52, 'San Lorenzo', 18.19, -65.96, 1],
            [52, 'Santa Isabel', 17.97, -66.4, 1],
            [52, 'Vieques', 18.42, -65.83, 1],
            [52, 'Villalba', 18.13, -66.48, 1],
            [52, 'Yabucoa', 18.04, -65.87, 1],
            [52, 'Coamo', 18.08, -66.36, 1],
            [52, 'Las Piedras', 18.18, -65.86, 1],
            [52, 'Loiza', 18.43, -65.88, 1],
            [52, 'Luquillo', 18.37, -65.71, 1],
            [52, 'Culebra', 18.31, -65.3, 1],
            [52, 'Juncos', 18.22, -65.91, 1],
            [52, 'Gurabo', 18.25, -65.97, 1],
            [52, 'Coto Laurel', 18.09, -66.57, 1],
            [52, 'Comerio', 18.22, -66.22, 1],
            [52, 'Corozal', 18.34, -66.31, 1],
            [52, 'Guayama', 17.97, -66.11, 1],
            [52, 'La Plata', 18.16, -66.23, 1],
            [52, 'Humacao', 18.15, -65.81, 1],
            [52, 'Barranquitas', 18.18, -66.3, 1],
            [52, 'Juana Diaz', 18.05, -66.5, 1]
        ]);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_city_state_id', 'city');
        $this->dropIndex('city_state_id_key', 'city');
        
        $this->dropTable('city');
    }
}