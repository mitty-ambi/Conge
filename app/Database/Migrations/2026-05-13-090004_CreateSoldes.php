<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSoldes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'employe_id' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
            ],
            'type_conge_id' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
            ],
            'annee' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
            ],
            'jours_attribues' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
            ],
            'jours_pris' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'default'  => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['employe_id', 'type_conge_id', 'annee']);
        $this->forge->addForeignKey('employe_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('soldes', true);
    }

    public function down()
    {
        $this->forge->dropTable('soldes', true);
    }
}
