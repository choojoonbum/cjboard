<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberGroupMemberModel extends Model
{
    protected $table      = 'member_group_member';
    protected $primaryKey = 'mgm_id';

    protected $allowedFields = ['mgr_id','mem_id','mgm_datetime'];

}
