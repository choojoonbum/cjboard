<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePost extends Migration
{
    public function up()
    {
        // post table
        $this->forge->addField(array(
            'post_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'post_num' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => '0',
            ),
            'post_reply' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'default' => '',
            ),
            'brd_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_title' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'post_content' => array(
                'type' => 'MEDIUMTEXT',
                'null' => true,
            ),
            'post_category' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => '0',
            ),
            'post_userid' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'post_username' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'post_nickname' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'post_email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'post_homepage' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
            'post_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'post_password' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'post_updated_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'post_update_mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_comment_count' => array(
                'type' => 'MEDIUMINT',
                'constraint' => 6,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_comment_updated_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'post_link_count' => array(
                'type' => 'MEDIUMINT',
                'constraint' => 6,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_secret' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_html' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_hide_comment' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_notice' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_receive_email' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_hit' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_like' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_dislike' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '',
            ),
            'post_blame' => array(
                'type' => 'MEDIUMINT',
                'constraint' => 6,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_device' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'default' => '',
            ),
            'post_file' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_image' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'post_del' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
        ));
        $this->forge->addKey('post_id', true);
        $this->forge->addKey(array('post_num', 'post_reply'));
        $this->forge->addKey('brd_id');
        $this->forge->addKey('post_datetime');
        $this->forge->addKey('post_updated_datetime');
        $this->forge->addKey('post_comment_updated_datetime');
        if ($this->forge->createTable('post', true) === false) {
            return false;
        }


// post_extra_vars tables
        $this->forge->addField(array(
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
            'pev_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'pev_value' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
        ));
        if ($this->forge->createTable('post_extra_vars', true) === false) {
            return false;
        }
        $this->db->query('ALTER TABLE ' . $this->db->getPrefix() . 'post_extra_vars ADD UNIQUE KEY `post_id_pev_key` (`post_id`, `pev_key`)');


// post_file table
        $this->forge->addField(array(
            'pfi_id' => array(
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
            'mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'pfi_originname' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'pfi_filename' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'pfi_download' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'pfi_filesize' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'pfi_width' => array(
                'type' => 'MEDIUMINT',
                'constraint' => 6,
                'unsigned' => true,
                'default' => '0',
            ),
            'pfi_height' => array(
                'type' => 'MEDIUMINT',
                'constraint' => 6,
                'unsigned' => true,
                'default' => '0',
            ),
            'pfi_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'default' => '',
            ),
            'pfi_is_image' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'pfi_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'pfi_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '',
            ),
        ));
        $this->forge->addKey('pfi_id', true);
        $this->forge->addKey('post_id');
        $this->forge->addKey('mem_id');
        if ($this->forge->createTable('post_file', true) === false) {
            return false;
        }


// post_file_download_log table
        $this->forge->addField(array(
            'pfd_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'pfi_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
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
            'mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'pfd_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'pfd_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '',
            ),
            'pfd_useragent' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
        ));
        $this->forge->addKey('pfd_id', true);
        $this->forge->addKey('pfi_id');
        $this->forge->addKey('post_id');
        $this->forge->addKey('mem_id');
        if ($this->forge->createTable('post_file_download_log', true) === false) {
            return false;
        }


// post_history table
        $this->forge->addField(array(
            'phi_id' => array(
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
            'mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'phi_title' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
            'phi_content' => array(
                'type' => 'MEDIUMTEXT',
                'null' => true,
            ),
            'phi_content_html_type' => array(
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => '0',
            ),
            'phi_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '',
            ),
            'phi_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
        ));
        $this->forge->addKey('phi_id', true);
        $this->forge->addKey('post_id');
        $this->forge->addKey('mem_id');
        if ($this->forge->createTable('post_history', true) === false) {
            return false;
        }


// post_link table
        $this->forge->addField(array(
            'pln_id' => array(
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
            'pln_url' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
            'pln_hit' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
        ));
        $this->forge->addKey('pln_id', true);
        $this->forge->addKey('post_id');
        if ($this->forge->createTable('post_link', true) === false) {
            return false;
        }


// post_link_click_log table
        $this->forge->addField(array(
            'plc_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'pln_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
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
            'mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'plc_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'plc_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => '',
            ),
            'plc_useragent' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ),
        ));
        $this->forge->addKey('plc_id', true);
        $this->forge->addKey('pln_id');
        $this->forge->addKey('post_id');
        if ($this->forge->createTable('post_link_click_log', true) === false) {
            return false;
        }


// post_meta table
        $this->forge->addField(array(
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
            'pmt_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
            'pmt_value' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
        ));
        if ($this->forge->createTable('post_meta', true) === false) {
            return false;
        }
        $this->db->query('ALTER TABLE ' . $this->db->getPrefix() . 'post_meta ADD UNIQUE KEY `post_id_pmt_key` (`post_id`, `pmt_key`)');


// post_naver_syndi_log table
        $this->forge->addField(array(
            'pns_id' => array(
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
            'mem_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => '0',
            ),
            'pns_status' => array(
                'type' => 'varchar',
                'constraint' => '255',
                'default' => '',
            ),
            'pns_return_code' => array(
                'type' => 'varchar',
                'constraint' => '255',
                'default' => '',
            ),
            'pns_return_message' => array(
                'type' => 'varchar',
                'constraint' => '255',
                'default' => '',
            ),
            'pns_receipt_number' => array(
                'type' => 'varchar',
                'constraint' => '255',
                'default' => '',
            ),
            'pns_datetime' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
        ));
        $this->forge->addKey('pns_id', true);
        $this->forge->addKey('post_id');
        $this->forge->addKey('mem_id');
        if ($this->forge->createTable('post_naver_syndi_log', true) === false) {
            return false;
        }


//post_tag table
        $this->forge->addField(array(
            'pta_id' => array(
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
            'pta_tag' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => '',
            ),
        ));
        $this->forge->addKey('pta_id', true);
        $this->forge->addKey('post_id');
        $this->forge->addKey('pta_tag');
        if ($this->forge->createTable('post_tag', true) === false) {
            return false;
        }
    }

    public function down()
    {
         $this->forge->dropTable('post', true);
         $this->forge->dropTable('post_extra_vars', true);
         $this->forge->dropTable('post_file', true);
         $this->forge->dropTable('post_file_download_log', true);
         $this->forge->dropTable('post_history', true);
         $this->forge->dropTable('post_link', true);
         $this->forge->dropTable('post_link_click_log', true);
         $this->forge->dropTable('post_meta', true);
         $this->forge->dropTable('post_naver_syndi_log', true);
         $this->forge->dropTable('post_tag', true);
    }
}
