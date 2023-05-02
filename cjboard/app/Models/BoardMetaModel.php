<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardMetaModel extends Model
{
    protected $table      = 'board_meta';
    protected $primaryKey = 'brd_id';

    public $metaKey = 'bmt_key';

    public $metaValue = 'bmt_value';

    public $cachePrefix= 'board_meta/board-meta-model-get-'; // 캐시 사용시 프리픽스

    protected function initialize()
    {
        checkCacheDir('board_meta');
    }

    public function getAllMeta($brdId = 0)
    {
        if (empty($brdId)) {
            return false;
        }

        $cachename = $this->cachePrefix . $brdId;
        $data = array();
        if ( ! $data = cache()->get($cachename)) {
            $result = array();
            $res = $this->where([$this->primaryKey => $brdId])->findAll();
            if ($res && is_array($res)) {
                foreach ($res as $val) {
                    $result[$val[$this->metaKey]] = $val[$this->metaValue];
                }
            }
            $data['result'] = $result;
            $data['cached'] = '1';
            cache()->save($cachename, $data);
        }
        return isset($data['result']) ? $data['result'] : false;
    }

}