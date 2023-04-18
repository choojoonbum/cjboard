<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberLoginLogModel extends Model
{
    protected $table      = 'member_login_log';
    protected $primaryKey = 'mll_id';

    protected $allowedFields = ['mll_success','mem_id','mll_userid','mll_datetime','mll_ip','mll_reason','mll_useragent','mll_url','mll_referer'];

}
