<?php

namespace App\Models;

class PostModel extends BaseModel
{
    protected $table      = 'post';
    protected $primaryKey = 'post_id';

    protected $allowedFields = ['post_num','post_reply','post_title','post_content','post_html','post_datetime','post_updated_datetime','post_ip','brd_id',
        'post_nickname','post_email','post_homepage','post_password','post_device','post_hit','post_link_count','post_image','post_file'];

    public $allow_order = array('post_num, post_reply', 'post_datetime desc', 'post_datetime asc', 'post_hit desc', 'post_hit asc', 'post_comment_count desc', 'post_comment_count asc', 'post_comment_updated_datetime desc', 'post_comment_updated_datetime asc', 'post_like desc', 'post_like asc', 'post_id desc');

    public function next_post_num()
    {
        $this->selectMin('post_num');
        $row = $this->first();
        $row['post_num'] = (isset($row['post_num'])) ? $row['post_num'] : 0;
        $post_num = $row['post_num'] - 1;
        return $post_num;
    }

    public function get_prev_next_post($post_id = 0, $post_num = 0, $type = '', $where = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $post_id = (int) $post_id;
        if (empty($post_id) OR $post_id < 1) {
            return false;
        }

        $sop = (strtoupper($sop) === 'AND') ? 'AND' : 'OR';
        if (empty($sfield)) {
            $sfield = array('post_title', 'post_content');
        }

        $search_where = array();
        $search_like = array();
        $search_or_like = array();
        if ($sfield && is_array($sfield)) {
            foreach ($sfield as $skey => $sval) {
                $ssf = $sval;
                if ($skeyword && $ssf && in_array($ssf, $this->allow_search_field)) {
                    if (in_array($ssf, $this->search_field_equal)) {
                        $search_where[$ssf] = $skeyword;
                    } else {
                        $swordarray = explode(' ', $skeyword);
                        foreach ($swordarray as $str) {
                            if (empty($ssf)) {
                                continue;
                            }
                            if ($sop === 'AND') {
                                $search_like[] = array($ssf => $str);
                            } else {
                                $search_or_like[] = array($ssf => $str);
                            }
                        }
                    }
                }
            }
        } else {
            $ssf = $sfield;
            if ($skeyword && $ssf && in_array($ssf, $this->allow_search_field)) {
                if (in_array($ssf, $this->search_field_equal)) {
                    $search_where[$ssf] = $skeyword;
                } else {
                    $swordarray = explode(' ', $skeyword);
                    foreach ($swordarray as $str) {
                        if (empty($ssf)) {
                            continue;
                        }
                        if ($sop === 'AND') {
                            $search_like[] = array($ssf => $str);
                        } else {
                            $search_or_like[] = array($ssf => $str);
                        }
                    }
                }
            }
        }

        $this->select('post.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_icon, member.mem_photo, member.mem_point');
        $this->join('member', 'post.mem_id = member.mem_id', 'left');

        if ($type === 'next') {
            $where['post_num >'] = $post_num;
        } else {
            $where['post_num <'] = $post_num;
        }

        if ($where) {
            $this->where($where);
        }
        if ($search_where) {
            $this->where($search_where);
        }
        if ($search_like) {
            foreach ($search_like as $item) {
                foreach ($item as $skey => $sval) {
                    $this->like($skey, $sval);
                }
            }
        }
        if ($search_or_like) {
            $this->groupStart();
            foreach ($search_or_like as $item) {
                foreach ($item as $skey => $sval) {
                    $this->orLike($skey, $sval);
                }
            }
            $this->groupEnd();
        }

        $orderby = $type === 'next'
            ? 'post_num, post_reply' : 'post_num desc, post_reply desc';


        $this->orderBy($orderby);
        $this->limit(1);
        $result = $this->find();

        if (empty($result)){
            return false;
        } else {
            return $result[0];
        }

    }


    public function get_post_list($limit = '', $offset = '', $where = '', $category_id = '', $orderby = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        if ( ! in_array(strtolower($orderby), $this->allow_order)) {
            $orderby = 'post_num, post_reply';
        }

        $sop = (strtoupper($sop) === 'AND') ? 'AND' : 'OR';
        if (empty($sfield)) {
            $sfield = array('post_title', 'post_content');
        }

        $search_where = array();
        $search_like = array();
        $search_or_like = array();
        if ($sfield && is_array($sfield)) {
            foreach ($sfield as $skey => $sval) {
                $ssf = $sval;
                if ($skeyword && $ssf && in_array($ssf, $this->allow_search_field)) {
                    if (in_array($ssf, $this->search_field_equal)) {
                        $search_where[$ssf] = $skeyword;
                    } else {
                        $swordarray = explode(' ', $skeyword);
                        foreach ($swordarray as $str) {
                            if (empty($ssf)) {
                                continue;
                            }
                            if ($sop === 'AND') {
                                $search_like[] = array($ssf => $str);
                            } else {
                                $search_or_like[] = array($ssf => $str);
                            }
                        }
                    }
                }
            }
        } else {
            $ssf = $sfield;
            if ($skeyword && $ssf && in_array($ssf, $this->allow_search_field)) {
                if (in_array($ssf, $this->search_field_equal)) {
                    $search_where[$ssf] = $skeyword;
                } else {
                    $swordarray = explode(' ', $skeyword);
                    foreach ($swordarray as $str) {
                        if (empty($ssf)) {
                            continue;
                        }
                        if ($sop === 'AND') {
                            $search_like[] = array($ssf => $str);
                        } else {
                            $search_or_like[] = array($ssf => $str);
                        }
                    }
                }
            }
        }
     
        $this->select('post.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_icon, member.mem_photo, member.mem_point');
        $this->join('member', 'post.mem_id = member.mem_id', 'left');

        if ($where) {
            $this->where($where);
        }
        if ($search_where) {
            $this->where($search_where);
        }
        if ($category_id) {
            if (strpos($category_id, '.')) {
                $this->like('post_category', $category_id . '', 'after');
            } else {
                $this->groupStart();
                $this->where('post_category', $category_id);
                $this->orLike('post_category', $category_id . '.', 'after');
                $this->groupEnd();
            }
        }
        if ($search_like) {
            foreach ($search_like as $item) {
                foreach ($item as $skey => $sval) {
                    $this->like($skey, $sval);
                }
            }
        }
        if ($search_or_like) {
            $this->groupStart();
            foreach ($search_or_like as $item) {
                foreach ($item as $skey => $sval) {
                    $this->orLike($skey, $sval);
                }
            }
            $this->groupEnd();
        }

        $this->orderBy($orderby);

        if ($limit) {
            $this->limit($limit, $offset);
        }
        $result['list'] = $this->find();

        $this->select('count(*) as rownum');
        $this->join('member', 'post.mem_id = member.mem_id', 'left');
        if ($where) {
            $this->where($where);
        }
        if ($search_where) {
            $this->where($search_where);
        }
        if ($category_id) {
            if (strpos($category_id, '.')) {
                $this->like('post_category', $category_id . '', 'after');
            } else {
                $this->groupStart();
                $this->where('post_category', $category_id);
                $this->orLike('post_category', $category_id . '.', 'after');
                $this->groupEnd();
            }
        }
        if ($search_like) {
            foreach ($search_like as $item) {
                foreach ($item as $skey => $sval) {
                    $this->like($skey, $sval);
                }
            }
        }
        if ($search_or_like) {
            $this->groupStart();
            foreach ($search_or_like as $item) {
                foreach ($item as $skey => $sval) {
                    $this->orLike($skey, $sval);
                }
            }
            $this->groupEnd();
        }

        $rows = $this->first();
        $result['total_rows'] = $rows['rownum'];

        return $result;
    }

}