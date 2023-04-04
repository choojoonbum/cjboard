<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMember extends Migration
{
    public function up()
    {
        $this->forge->addField(array(
            'mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'mem_userid' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'mem_email' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'mem_password' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mem_username' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'mem_nickname' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'mem_level' => array(
                'type' => 'MEDIUMINT',
                'constraint' => 6,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_point' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => '0',
            ),
            'mem_homepage' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
            'mem_phone' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mem_birthday' => array(
                'type' => 'CHAR',
                'constraint' => '10',
                'default' => '',
            ),
            'mem_sex' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_zipcode' => array(
                'type' => 'VARCHAR',
                'constraint' => '7',
                'default' => '',
            ),
            'mem_address1' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mem_address2' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mem_address3' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mem_address4' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mem_receive_email' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_use_note' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_receive_sms' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_open_profile' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_denied' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_email_cert' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_register_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'mem_register_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '',
            ),
            'mem_lastlogin_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'mem_lastlogin_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '',
            ),
            'mem_is_admin' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_profile_content' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
            'mem_adminmemo' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
            'mem_following' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_followed' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_icon' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mem_photo' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
        ));
        $this->forge->addKey('mem_id', true);
        $this->forge->addKey('mem_email');
        $this->forge->addKey('mem_lastlogin_datetime');
        $this->forge->addKey('mem_register_datetime');
        if ($this->forge->createTable('member', true) === false) {
            return false;
        }
        $this->db->query('ALTER TABLE ' . $this->db->getPrefix() . 'member ADD UNIQUE KEY `mem_userid` (`mem_userid`)');


        // member_group table
        $this->forge->addField(array(
            'mgr_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'mgr_title' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mgr_is_default' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'mgr_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'mgr_order' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => '0',
            ),
            'mgr_description' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
        ));
        $this->forge->addKey('mgr_id', true);
        $this->forge->addKey('mgr_order');
        if ($this->forge->createTable('member_group', true) === false) {
            return false;
        }


        // member_group_member table
        $this->forge->addField(array(
            'mgm_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'mgr_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'mgm_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
        ));
        $this->forge->addKey('mgm_id', true);
        $this->forge->addKey('mgr_id');
        $this->forge->addKey('mem_id');
        if ($this->forge->createTable('member_group_member', true) === false) {
            return false;
        }

        // member_login_log table
        $this->forge->addField(array(
            'mll_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'mll_success' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'mll_userid' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mll_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'mll_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '',
            ),
            'mll_reason' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mll_useragent' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mll_url' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
            'mll_referer' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
        ));
        $this->forge->addKey('mll_id', true);
        $this->forge->addKey('mll_success');
        $this->forge->addKey('mem_id');
        if ($this->forge->createTable('member_login_log', true) === false) {
            return false;
        }

    }

    public function down()
    {
        $this->forge->dropTable('member', true);
        $this->forge->dropTable('member_group', true);
        $this->forge->dropTable('member_group_member', true);
        $this->forge->dropTable('member_login_log', true);
    }
}
