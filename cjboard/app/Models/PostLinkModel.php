<?php

namespace App\Models;

class PostLinkModel extends BaseModel
{
    protected $table      = 'post_link';
    protected $primaryKey = 'pln_id';

    protected $allowedFields = ['post_id', 'brd_id','pln_url'];

}

