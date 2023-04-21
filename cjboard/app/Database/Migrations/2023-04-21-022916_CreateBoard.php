<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBoard extends Migration
{
    public function up()
    {
        $this->forge->addField(array(
            'brd_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'bgr_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'brd_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'brd_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'brd_mobile_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'brd_order' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => '0',
            ),
            'brd_search' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
        ));
        $this->forge->addKey('brd_id', true);
        $this->forge->addKey('bgr_id');
        if ($this->forge->createTable('board', true) === false) {
            return false;
        }
        $this->db->query('ALTER TABLE ' . $this->db->getPrefix() . 'board ADD UNIQUE KEY `brd_key` (`brd_key`)');

        $this->forge->addField(array(
            'bam_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'brd_id' => array(
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
        ));
        $this->forge->addKey('bam_id', true);
        $this->forge->addKey('brd_id');
        if ($this->forge->createTable('board_admin', true) === false) {
            return false;
        }

        $this->forge->addField(array(
            'bca_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'brd_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'bca_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'bca_value' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'bca_parent' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'bca_order' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
        ));
        $this->forge->addKey('bca_id', true);
        $this->forge->addKey('brd_id');
        if ($this->forge->createTable('board_category', true) === false) {
            return false;
        }

        $this->forge->addField(array(
            'bgr_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'bgr_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'bgr_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'bgr_order' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => '0',
            ),
        ));
        $this->forge->addKey('bgr_id', true);
        $this->forge->addKey('bgr_order');
        if ($this->forge->createTable('board_group', true) === false) {
            return false;
        }
        $this->db->query('ALTER TABLE ' . $this->db->getPrefix() . 'board_group ADD UNIQUE KEY `bgr_key` (`bgr_key`)');

        $this->forge->addField(array(
            'bga_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'bgr_id' => array(
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
        ));
        $this->forge->addKey('bga_id', true);
        $this->forge->addKey('bgr_id');
        if ($this->forge->createTable('board_group_admin', true) === false) {
            return false;
        }

        $this->forge->addField(array(
            'bgr_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'bgm_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'bgm_value' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
        ));
        if ($this->forge->createTable('board_group_meta', true) === false) {
            return false;
        }
        $this->db->query('ALTER TABLE ' . $this->db->getPrefix() . 'board_group_meta ADD UNIQUE KEY `bgr_id_bgm_key` (`bgr_id`, `bgm_key`)');

        $this->forge->addField(array(
            'brd_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'bmt_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'bmt_value' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
        ));
        if ($this->forge->createTable('board_meta', true) === false) {
            return false;
        }
        $this->db->query('ALTER TABLE ' . $this->db->getPrefix() . 'board_meta ADD UNIQUE KEY `brd_id_bmt_key` (`brd_id`, `bmt_key`)');

        $this->forge->addField(array(
            'cmt_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'post_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'brd_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'cmt_num' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => '0',
            ),
            'cmt_reply' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => '',
            ),
            'cmt_html' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'cmt_secret' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'cmt_content' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
            'mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => '0',
            ),
            'cmt_password' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'cmt_userid' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'cmt_username' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'cmt_nickname' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'cmt_email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'cmt_homepage' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
            'cmt_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'cmt_updated_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'cmt_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '',
            ),
            'cmt_like' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'cmt_dislike' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'cmt_blame' => array(
                'type' => 'MEDIUMINT',
                'constraint' => 6,
                'unsigned' => true,
                'default' => '0',
            ),
            'cmt_device' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'default' => '',
            ),
            'cmt_del' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
        ));
        $this->forge->addKey('cmt_id', true);
        $this->forge->addKey(array('post_id', 'cmt_num', 'cmt_reply'));
        if ($this->forge->createTable('comment', true) === false) {
            return false;
        }

        $this->forge->addField(array(
            'cmt_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'cme_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'cme_value' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
        ));
        if ($this->forge->createTable('comment_meta', true) === false) {
            return false;
        }
        $this->db->query('ALTER TABLE ' . $this->db->getPrefix() . 'comment_meta ADD UNIQUE KEY `cmt_id_cme_key` (`cmt_id`, `cme_key`)');

        $this->forge->addField(array(
            'scb_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'scb_date' => array(
                'type' => 'DATE',
            ),
            'brd_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'scb_count' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
        ));
        $this->forge->addKey('scb_id', true);
        $this->forge->addKey(array('scb_date', 'brd_id'));
        if ($this->forge->createTable('stat_count_board', true) === false) {
            return false;
        }

    }

    public function down()
    {
        $this->forge->dropTable('board', true);
        $this->forge->dropTable('board_admin', true);
        $this->forge->dropTable('board_category', true);
        $this->forge->dropTable('board_group', true);
        $this->forge->dropTable('board_group_admin', true);
        $this->forge->dropTable('board_group_meta', true);
        $this->forge->dropTable('board_meta', true);
        $this->forge->dropTable('comment', true);
        $this->forge->dropTable('comment_meta', true);
        $this->forge->dropTable('stat_count_board', true);
    }
}
