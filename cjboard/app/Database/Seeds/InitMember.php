<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitMember extends Seeder
{
    public function run()
    {
        $insertdata = array(
            'mem_userid' => 'admin',
            'mem_email' => 'cjb3333@naver.com',
            'mem_password' => password_hash('1234', PASSWORD_BCRYPT),
            'mem_username' => '추준범',
            'mem_nickname' => '추볼루션',
            'mem_level' => 100,
            'mem_receive_email' => 1,
            'mem_use_note' => 1,
            'mem_receive_sms' => 1,
            'mem_open_profile' => 1,
            'mem_email_cert' => 1,
            'mem_register_datetime' => date('Y-m-d H:i:s'),
            'mem_register_ip' => '',
            'mem_lastlogin_datetime' => date('Y-m-d H:i:s'),
            'mem_lastlogin_ip' => '',
            'mem_is_admin' => 1,
        );

        $this->db->table('member')->truncate();
        $this->db->table('member')->insert($insertdata);

        $insertdata = array(
            'mgr_title' => '기본그룹',
            'mgr_is_default' => 1,
            'mgr_datetime' => date('Y-m-d H:i:s'),
            'mgr_order' => 1,
        );
        $this->db->table('member_group')->truncate();
        $this->db->table('member_group')->insert($insertdata);
        $insertdata = array(
            'mgr_title' => '특별그룹',
            'mgr_is_default' => 0,
            'mgr_datetime' => date('Y-m-d H:i:s'),
            'mgr_order' => 2,
        );
        $this->db->table('member_group')->insert($insertdata);
        $insertdata = array(
            'mgr_title' => '우수그룹',
            'mgr_is_default' => 0,
            'mgr_datetime' => date('Y-m-d H:i:s'),
            'mgr_order' => 3,
        );
        $this->db->table('member_group')->insert($insertdata);

    }
}
