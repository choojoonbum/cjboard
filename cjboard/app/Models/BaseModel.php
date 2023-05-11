<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    public function update_plus($primary_value = '', $field = '', $count = '')
    {
        if (empty($primary_value) OR empty($field) OR empty($count)) {
            return false;
        }

        $this->where($this->primaryKey, $primary_value);
        if ($count > 0) {
            $this->set($field, $field . '+' . $count, false);
        } else {
            $this->set($field, $field . $count, false);
        }

        $result = $this->update();

        return $result;
    }

    public function get($primary_value = '', $select = '', $where = '', $limit = '', $offset = 0, $findex = '', $forder = '')
    {

        $result = $this->_get($primary_value, $select, $where, $limit, $offset, $findex, $forder);
        return $result;
    }

    public function _get($primary_value = '', $select = '', $where = '', $limit = '', $offset = 0, $findex = '', $forder = '')
    {
        if ($select) {
            $this->select($select);
        }
        if ($primary_value) {
            $this->where($this->primary_key, $primary_value);
        }
        if ($where) {
            $this->where($where);
        }
        if ($findex) {
            if (strtoupper($forder) === 'RANDOM') {
                $forder = 'RANDOM';
            } elseif (strtoupper($forder) === 'DESC') {
                $forder = 'DESC';
            } else {
                $forder = 'ASC';
            }
            $this->orderBy($findex, $forder);
        }
        if (is_numeric($limit) && is_numeric($offset)) {
            $this->limit($limit, $offset);
        }
        $result = $this->find();

        return $result;
    }

    public function getOne($primaryValue = '', $select = '', $where = '')
    {
        if ($primaryValue) {
            $this->where($this->primaryKey, $primaryValue);
        }
        if ($where) {
            $this->where($where);
        }
        $result = $this->find()[0];

        return $result;
    }

}
