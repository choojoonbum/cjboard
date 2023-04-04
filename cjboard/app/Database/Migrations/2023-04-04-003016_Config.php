<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Config extends Migration
{
    public function up()
    {
        // config table
        $this->forge->addField(array(
            'cfg_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'cfg_value' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
        ));
        if ($this->forge->createTable('config', true) === false) {
            return false;
        }
        $this->db->query('ALTER TABLE ' . $this->db->getPrefix() . 'config ADD UNIQUE KEY `cfg_key` (`cfg_key`)');
    }

    public function down()
    {
        $this->forge->dropTable('config', true);
    }
}
