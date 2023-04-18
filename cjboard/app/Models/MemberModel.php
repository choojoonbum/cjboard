<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table      = 'member';
    protected $primaryKey = 'mem_id';

    protected $allowedFields = ['mem_userid','mem_email','mem_password','mem_username','mem_nickname','mem_level','mem_point','mem_homepage','mem_phone',
        'mem_birthday','mem_sex','mem_zipcode','mem_address1','mem_address2','mem_address3','mem_address4','mem_receive_email','mem_use_note','mem_receive_sms',
        'mem_open_profile','mem_denied','mem_email_cert','mem_register_datetime','mem_register_ip','mem_lastlogin_datetime','mem_lastlogin_ip','mem_is_admin',
        'mem_profile_content','mem_adminmemo','mem_following','mem_followed','mem_icon','mem_photo'];

    public function getByUserid($userid) {
        return $this->where(['mem_userid' => $userid])->first();
    }

}