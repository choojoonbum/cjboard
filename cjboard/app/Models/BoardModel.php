<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardModel extends Model
{
    protected $table      = 'board';
    protected $primaryKey = 'brd_id';

    protected $cachePrefix = 'board/board-model-get-';

    protected function initialize()
    {
        checkCacheDir('board');
    }

    public function getOne($primaryValue = '', $select = '', $where = '')
    {
        $use_cache = false;
        if ($primaryValue && empty($select) && empty($where)) {
            $use_cache = true;
        }

        if ($use_cache) {
            $cachename = $this->cachePrefix . $primaryValue;
            if ( ! $result = cache()->get($cachename)) {
                $result = $this->find($primaryValue);
                cache()->save($cachename, $result);
            }
        } else {
            if ($primaryValue) {
                $this->where($this->primaryKey, $primaryValue);
            }
            if ($where) {
                $this->where($where);
            }
            $result = $this->find()[0];
        }
        return $result;
    }

}
