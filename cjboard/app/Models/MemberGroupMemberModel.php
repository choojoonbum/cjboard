<?php

namespace App\Models;

class MemberGroupMemberModel extends BaseModel
{
    protected $table      = 'member_group_member';
    protected $primaryKey = 'mgm_id';

    protected $allowedFields = ['mgr_id','mem_id','mgm_datetime'];

}
