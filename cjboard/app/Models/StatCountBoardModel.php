<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigModel extends Model
{
    protected $table      = 'stat_count_board';
    protected $primaryKey = 'scb_id';

    protected $allowedFields = ['cfg_key', 'cfg_value'];

    public function get_board_count($type = 'd', $start_date = '', $end_date = '', $brd_id = 0, $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';

        if ($type === 'y' OR $type === 'm') {
            $this->db->select_sum('scb_count');
            $this->db->select('left(scb_date, ' . $left . ') as day, brd_id ', false);
        } else {
            $this->db->select_sum('scb_count');
            $this->db->select('scb_date as day, brd_id ', false);
        }
        $this->db->where('scb_date >=', $start_date);
        $this->db->where('scb_date <=', $end_date);

        $brd_id = (int) $brd_id;
        if ($brd_id) {
            $this->db->where('brd_id', $brd_id);
            $this->db->group_by(array('day'));
        } else {
            $this->db->group_by(array('day', 'brd_id'));
        }
        $this->db->order_by('scb_id', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }


    public function add_visit_board($brd_id = 0)
    {
        $brd_id = (int) $brd_id;
        if (empty($brd_id) OR $brd_id < 1) {
            return false;
        }

        $sql = 'INSERT INTO ' . $this->db->prefixTable($this->table);
        $sql .= " (scb_date, brd_id, scb_count) VALUES ('" . date('Y-m-d') . "', '" . $brd_id . "', 1) ";
        $sql .= " ON DUPLICATE KEY UPDATE scb_count= scb_count + 1 ";

        return $this->db->query($sql);

    }

}
