<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigModel extends Model
{
    protected $table      = 'config';
    protected $primaryKey = 'cfg_key';

    protected $allowedFields = ['cfg_key', 'cfg_value'];

}
