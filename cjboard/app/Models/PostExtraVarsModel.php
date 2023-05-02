<?php

namespace App\Models;

use CodeIgniter\Model;

class PostExtraVarsModel extends Model
{
    protected $table      = 'post_extra_vars';
    protected $primaryKey = 'post_id';

    public $meta_key = 'pev_key';
    public $meta_value = 'pev_value';
    public $cache_prefix= 'post_extra_vars/post-extra-vars-model-get-'; // 캐시 사용시 프리픽스

    protected $allowedFields = [];

    protected function initialize()
    {
        checkCacheDir('post_extra_vars');
    }


}
