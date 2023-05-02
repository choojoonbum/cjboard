<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardAdminModel extends Model
{
    protected $table      = 'board_admin';
    protected $primaryKey = 'bam_id';

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

