<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardGroupAdminModel extends Model
{
    protected $table      = 'board_group_admin';
    protected $primaryKey = 'bga_id';

    public function countBy($where = '', $like = '')
    {
        if ($where) {
            $this->where($where);
        }
        if ($like) {
            $this->like($like);
        }
        return $this->countAllResults();
    }

}
