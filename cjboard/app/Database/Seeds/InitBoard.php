<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitBoard extends Seeder
{
    public function boardGroupMetaConvert($bgr_id) {
        $metadata = array(
            'header_content' => '',
            'footer_content' => '',
            'mobile_header_content' => '',
            'mobile_footer_content' => '',
        );
        $boardGroupMeta = [];
        foreach ($metadata as $column => $value) {
            $boardGroupMeta[] = [
                'bgr_id' => $bgr_id,
                'bgm_key' => $column,
                'bgm_value' => $value,
            ];
        }
        return $boardGroupMeta;
    }

    public function boardMetaConvert($brd_id) {
        $metadata = array(
            'header_content' => '',
            'footer_content' => '',
            'mobile_header_content' => '',
            'mobile_footer_content' => '',
            'order_by_field' => 'post_num, post_reply',
            'list_count' => '20',
            'mobile_list_count' => '10',
            'page_count' => '5',
            'mobile_page_count' => '3',
            'show_list_from_view' => '1',
            'new_icon_hour' => '24',
            'hot_icon_hit' => '100',
            'hot_icon_day' => '30',
            'subject_length' => '60',
            'mobile_subject_length' => '40',
            'reply_order' => 'asc',
            'gallery_cols' => '4',
            'gallery_image_width' => '120',
            'gallery_image_height' => '90',
            'mobile_gallery_cols' => '2',
            'mobile_gallery_image_width' => '120',
            'mobile_gallery_image_height' => '90',
            'use_scrap' => '1',
            'use_post_like' => '1',
            'use_post_dislike' => '1',
            'use_print' => '1',
            'use_sns' => '1',
            'use_prev_next_post' => '1',
            'use_mobile_prev_next_post' => '1',
            'use_blame' => '1',
            'blame_blind_count' => '3',
            'syntax_highlighter' => '1',
            'comment_syntax_highlighter' => '1',
            'use_autoplay' => '1',
            'post_image_width' => '700',
            'post_mobile_image_width' => '400',
            'content_target_blank' => '1',
            'use_auto_url' => '1',
            'use_mobile_auto_url' => '1',
            'use_post_dhtml' => '1',
            'link_num' => '2',
            'use_upload_file' => '1',
            'upload_file_num' => '2',
            'mobile_upload_file_num' => '2',
            'upload_file_max_size' => '32',
            'comment_count' => '20',
            'mobile_comment_count' => '20',
            'comment_page_count' => '5',
            'mobile_comment_page_count' => '3',
            'use_comment_like' => '1',
            'use_comment_dislike' => '1',
            'use_comment_secret' => '1',
            'comment_order' => 'asc',
            'use_comment_blame' => '1',
            'comment_blame_blind_count' => '3',
            'protect_comment_num' => '5',
            'use_sideview' => '1',
            'use_tempsave' => '1',
        );
        $boardMeta = [];
        foreach ($metadata as $column => $value) {
            $boardMeta[] = [
                'brd_id' => $brd_id,
                'bmt_key' => $column,
                'bmt_value' => $value,
            ];
        }
        return $boardMeta;
    }

    public function run()
    {
        $this->db->table('board_group_meta')->truncate();
        $this->db->table('board_group')->truncate();
        $this->db->table('board_meta')->truncate();
        $this->db->table('board')->truncate();

        $insertdata = array(
            'bgr_key' => 'g-a',
            'bgr_name' => '그룹 A',
            'bgr_order' => 1,
        );
        $this->db->table('board_group')->insert($insertdata);
        $bgr_id_1 = $bgr_id = $this->db->insertID();
        $this->db->table('board_group_meta')->insertBatch($this->boardGroupMetaConvert($bgr_id));

        $insertdata = array(
            'bgr_key' => 'g-b',
            'bgr_name' => '그룹 B',
            'bgr_order' => 2,
        );
        $this->db->table('board_group')->insert($insertdata);
        $bgr_id_2 = $bgr_id = $this->db->insertID();
        $this->db->table('board_group_meta')->insertBatch($this->boardGroupMetaConvert($bgr_id));

        $insertdata = array(
            'bgr_key' => 'g-c',
            'bgr_name' => '그룹 C',
            'bgr_order' => 3,
        );
        $this->db->table('board_group')->insert($insertdata);
        $bgr_id_3 = $bgr_id = $this->db->insertID();
        $this->db->table('board_group_meta')->insertBatch($this->boardGroupMetaConvert($bgr_id));

        $insertdata = array(
            'bgr_id' => $bgr_id_1,
            'brd_key' => 'b-a-1',
            'brd_name' => '게시판 A-1',
            'brd_order' => 1,
            'brd_search' => 1,
        );
        $this->db->table('board')->insert($insertdata);
        $brd_id = $this->db->insertID();
        $this->db->table('board_meta')->insertBatch($this->boardMetaConvert($brd_id));

        $insertdata = array(
            'bgr_id' => $bgr_id_1,
            'brd_key' => 'b-a-2',
            'brd_name' => '게시판 A-2',
            'brd_order' => 2,
            'brd_search' => 1,
        );
        $this->db->table('board')->insert($insertdata);
        $brd_id = $this->db->insertID();
        $this->db->table('board_meta')->insertBatch($this->boardMetaConvert($brd_id));

        $insertdata = array(
            'bgr_id' => $bgr_id_1,
            'brd_key' => 'b-a-3',
            'brd_name' => '게시판 A-3',
            'brd_order' => 3,
            'brd_search' => 1,
        );
        $this->db->table('board')->insert($insertdata);
        $brd_id = $this->db->insertID();
        $this->db->table('board_meta')->insertBatch($this->boardMetaConvert($brd_id));

        $insertdata = array(
            'bgr_id' => $bgr_id_2,
            'brd_key' => 'b-b-1',
            'brd_name' => '게시판 B-1',
            'brd_order' => 11,
            'brd_search' => 1,
        );
        $this->db->table('board')->insert($insertdata);
        $brd_id = $this->db->insertID();
        $this->db->table('board_meta')->insertBatch($this->boardMetaConvert($brd_id));

        $insertdata = array(
            'bgr_id' => $bgr_id_2,
            'brd_key' => 'b-b-2',
            'brd_name' => '게시판 B-2',
            'brd_order' => 12,
            'brd_search' => 1,
        );
        $this->db->table('board')->insert($insertdata);
        $brd_id = $this->db->insertID();
        $this->db->table('board_meta')->insertBatch($this->boardMetaConvert($brd_id));

        $insertdata = array(
            'bgr_id' => $bgr_id_2,
            'brd_key' => 'b-b-3',
            'brd_name' => '게시판 B-3',
            'brd_order' => 13,
            'brd_search' => 1,
        );
        $this->db->table('board')->insert($insertdata);
        $brd_id = $this->db->insertID();
        $this->db->table('board_meta')->insertBatch($this->boardMetaConvert($brd_id));

        $insertdata = array(
            'bgr_id' => $bgr_id_3,
            'brd_key' => 'b-c-1',
            'brd_name' => '게시판 C-1',
            'brd_order' => 21,
            'brd_search' => 1,
        );
        $this->db->table('board')->insert($insertdata);
        $brd_id = $this->db->insertID();
        $this->db->table('board_meta')->insertBatch($this->boardMetaConvert($brd_id));

        $insertdata = array(
            'bgr_id' => $bgr_id_3,
            'brd_key' => 'b-c-2',
            'brd_name' => '게시판 C-2',
            'brd_order' => 22,
            'brd_search' => 1,
        );
        $this->db->table('board')->insert($insertdata);
        $brd_id = $this->db->insertID();
        $this->db->table('board_meta')->insertBatch($this->boardMetaConvert($brd_id));

        $insertdata = array(
            'bgr_id' => $bgr_id_3,
            'brd_key' => 'b-c-3',
            'brd_name' => '게시판 C-3',
            'brd_order' => 23,
            'brd_search' => 1,
        );
        $this->db->table('board')->insert($insertdata);
        $brd_id = $this->db->insertID();
        $this->db->table('board_meta')->insertBatch($this->boardMetaConvert($brd_id));

    }
}