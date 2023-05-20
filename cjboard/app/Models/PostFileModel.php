<?php

namespace App\Models;

class PostFileModel extends BaseModel
{
    protected $table      = 'post_file';
    protected $primaryKey = 'pfi_id';

    protected $allowedFields = ['post_id','brd_id','mem_id','pfi_originname','pfi_filename','pfi_filesize','pfi_width','pfi_height','pfi_type','pfi_is_image','pfi_datetime','pfi_ip'];

    public function get_post_file_count($post_id = 0)
    {
        $post_id = (int) $post_id;
        if (empty($post_id) OR $post_id < 1) {
            return false;
        }

        $this->select('count(*) as cnt, pfi_is_image ', false);
        $this->where('post_id', $post_id);
        $this->groupBy('pfi_is_image');
        $result = $this->find();

        return $result;
    }
}

