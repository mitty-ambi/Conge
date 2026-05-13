<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypesConge extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'libelle' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'jours_annuels' => [
                'type'       => 'INTEGER',
                'unsigned'   => true,
            ],
            'deductible' => [
                'type'       => 'BOOLEAN',
                'default'    => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('types_conge', true);
    }

    public function down()
    {
        $this->forge->dropTable('types_conge', true);
    }
}
