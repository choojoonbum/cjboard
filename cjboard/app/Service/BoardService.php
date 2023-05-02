<?php
namespace App\Service;

class BoardService {

    private $boardId;
    private $boardKey;
    private $group;
    private $admin;
    private $groupAdmin;
    private $callAdmin;
    private $callGroupAdmin;
    private $boardModel;
    private $boardMetaModel;
    private $memberService;
    private $uri;
    private $postModel;
    
    public function __construct()
    {
        $this->boardModel = model('BoardModel');
        $this->boardMetaModel = model('BoardMetaModel');
        $this->memberService = service('memberService');
        $this->uri = service('uri');
        $this->request = service('request');
        $this->postModel = model('PostModel');
    }

    public function itemKey($column = '', $brdKey = '')
    {
        if ( ! isset($this->boardKey[$brdKey])) {
            $this->getBoard('', $brdKey);
        }
        $board = $this->boardKey[$brdKey];

        return isset($board[$column]) ? $board[$column] : false;
    }

    public function getBoard($brdId = 0, $brdKey = '')
    {
        if (empty($brdId) && empty($brdKey)) {
            return false;
        }

        if ($brdId) {
            $board = $this->boardModel->getOne($brdId);
        } elseif ($brdKey) {
            $where = array(
                'brd_key' => $brdKey,
            );
            $board = $this->boardModel->getOne('', '', $where);

        } else {
            return false;
        }

        $board['board_name'] = $board['brd_name'];

        if (val('brd_id', $board)) {
            $boardMeta = $this->getAllMeta(val('brd_id', $board));
            if (is_array($boardMeta)) {
                $board = array_merge($board, $boardMeta);
            }
        }

        if (val('brd_id', $board)) {
            $this->boardId[val('brd_id', $board)] = $board;
        }
        if (val('brd_key', $board)) {
            $this->boardKey[val('brd_key', $board)] = $board;
        }
    }

    public function getAllMeta($brdId = 0)
    {
        $result = $this->boardMetaModel->getAllMeta($brdId);
        return $result;
    }

    public function itemAll($brdId = 0)
    {
        $brdId = (int) $brdId;
        if (empty($brdId) OR $brdId < 1) {
            return false;
        }
        if ( ! isset($this->boardId[$brdId])) {
            $this->getBoard($brdId);
        }
        if ( ! isset($this->boardId[$brdId])) {
            return false;
        }

        return $this->boardId[$brdId];
    }

    public function isGroupAdmin($bgrId = 0)
    {
        $bgrId = (int) $bgrId;
        if (empty($bgrId) OR $bgrId < 1) {
            return false;
        }
        if ( ! $this->memberService->item('mem_id')) {
            return false;
        }
        if ($this->callGroupAdmin) {
            return $this->groupAdmin;
        }
        $this->callGroupAdmin = true;
        $countwhere = array(
            'bgr_id' => $bgrId,
            'mem_id' => $this->memberServicer->item('mem_id'),
        );
        $count = model('BoardGroupAdminModel')->countBy($countwhere);
        if ($count) {
            $this->groupAdmin = true;
        } else {
            $this->groupAdmin = false;
        }

        return $this->groupAdmin;
    }

    public function isAdmin($brdId = 0)
    {
        $brdId = (int) $brdId;
        if (empty($brdId) OR $brdId < 1) {
            return false;
        }

        if ( ! $this->memberService->item('mem_id')) {
            return false;
        }
        if ($this->callAdmin) {
            return $this->admin;
        }
        $this->callAdmin = true;
        $countwhere = array(
            'brd_id' => $brdId,
            'mem_id' => $this->memberService->item('mem_id'),
        );
        $count = model('BoardAdminModel')->countBy($countwhere);
        if ($count) {
            $this->admin = true;
        } else {
            $this->admin = false;
        }
        return $this->admin;
    }
    
    public function get_board($brdId = 0, $brdKey = '')
    {
        if (empty($brdId) && empty($brdKey)) {
            return false;
        }

        if ($brdId) {
            $board = $this->boardModel->getOne($brdId);
        } elseif ($brdKey) {
            $where = array(
                'brd_key' => $brdKey,
            );
            $board = $this->boardModel->getOne('', '', $where);
        } else {
            return false;
        }
        $board['board_name'] = $board['brd_name'];
        if (val('brd_id', $board)) {
            $board_meta = $this->getAllMeta(val('brd_id', $board));
            if (is_array($board_meta)) {
                $board = array_merge($board, $board_meta);
            }
        }

        if (val('brd_id', $board)) {
            $this->boardId[val('brd_id', $board)] = $board;
        }
        if (val('brd_key', $board)) {
            $this->boardKey[val('brd_key', $board)] = $board;
        }
    }

    public function item_all($brdId = 0)
    {
        $brdId = (int) $brdId;
        if (empty($brdId) OR $brdId < 1) {
            return false;
        }
        if ( ! isset($this->boardId[$brdId])) {
            $this->get_board($brdId, '');
        }
        if ( ! isset($this->boardId[$brdId])) {
            return false;
        }

        return $this->boardId[$brdId];
    }
    
    public function item_key($column = '', $brdKey = '')
    {
        if (empty($column)) {
            return false;
        }
        if (empty($brdKey)) {
            return false;
        }
        if ( ! isset($this->boardKey[$brdKey])) {
            $this->get_board('', $brdKey);
        }
        if ( ! isset($this->boardKey[$brdKey])) {
            return false;
        }
        $board = $this->boardKey[$brdKey];

        return isset($board[$column]) ? $board[$column] : false;
    }

    public function _get_board($brdKey)
    {
        $boardId = $this->item_key('brd_id', $brdKey);
        if (empty($boardId)) {
            show_404();
        }
        $board = $this->item_all($boardId);
        return $board;
    }

    public function _get_list($brdKey, $from_view = '')
    {

        $view = array();
        $view['view'] = array();

        $return = array();
        $board = $this->_get_board($brdKey);
        $mem_id = $this->memberService->item('mem_id');

        if (val('use_personal', $board) && $this->memberService->isMember() === false) {
            alert('이 게시판은 1:1 게시판입니다. 비회원은 접근할 수 없습니다');
            return false;
        }


        $view['view']['is_admin'] = $is_admin = $this->memberService->isAdmin(
            array(
                'boardId' => val('brd_id', $board),
                'group_id' => val('bgr_id', $board)
            )
        );

        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param = $this->uri->getQuery();
        $page = (((int) $this->request->getGet('page')) > 0) ? ((int) $this->request->getGet('page')) : 1;
        $order_by_field = val('order_by_field', $board)
            ? val('order_by_field', $board)
            : 'post_num, post_reply';

        $findex = $this->request->getGet('findex');
        $sfield = $sfieldchk = $this->request->getGet('sfield');
        if ($sfield === 'post_both') {
            $sfield = array('post_title', 'post_content');
        }
        $skeyword = $this->request->getGet('skeyword');
        $per_page = val('list_count', $board)
            ? (int) val('list_count', $board) : 20;
        $offset = ($page - 1) * $per_page;

        $this->postModel->allow_search_field = array('post_id', 'post_title', 'post_content', 'post_both', 'post_category', 'post_userid', 'post_nickname'); // 검색이 가능한 필드
        $this->postModel->search_field_equal = array('post_id', 'post_userid', 'post_nickname'); // 검색중 like 가 아닌 = 검색을 하는 필드

        if (val('use_category', $board)) {
            $this->load->model('Board_category_model');
            $board['category'] = $this->Board_category_model
                ->get_all_category(val('brd_id', $board));
        }

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $where = array(
            'brd_id' => $this->item_key('brd_id', $brdKey),
        );
        $where['post_del <>'] = 2;

        $category_id = (int) $this->request->getGet('category_id');
        if (empty($category_id) OR $category_id < 1) {
            $category_id = '';
        }
        $result = $this->postModel->get_post_list($per_page, $offset, $where, $category_id, $findex, $sfield, $skeyword);

        $list_num = $result['total_rows'] - ($page - 1) * $per_page;
        if (val('list', $result)) {
            foreach (val('list', $result) as $key => $val) {
                $result['list'][$key]['post_url'] = post_url(val('brd_key', $board), val('post_id', $val));

                //$result['list'][$key]['meta'] = $meta
                //    = $this->Post_meta_model
                //    ->get_all_meta(val('post_id', $val));

                $result['list'][$key]['title'] = val('subject_length', $board)
                    ? cut_str(val('post_title', $val), val('subject_length', $board))
                    : val('post_title', $val);

                if (val('post_del', $val)) {
                    $result['list'][$key]['title'] = '게시물이 삭제 되었습니다';
                }
                $is_blind = (val('blame_blind_count', $board) > 0 && val('post_blame', $val) >= val('blame_blind_count', $board)) ? true : false;
                if ($is_blind) {
                    $result['list'][$key]['title'] = '신고가 접수된 게시글입니다.';
                }

                if (val('mem_id', $val) >= 0) {
                    $result['list'][$key]['display_name'] = display_username(
                        val('post_userid', $val),
                        val('post_nickname', $val),
                        '',
                        'N'
                    );
                } else {
                    $result['list'][$key]['display_name'] = '익명사용자';
                }

                $result['list'][$key]['display_datetime'] = display_datetime(
                    val('post_datetime', $val)
                );
                $result['list'][$key]['category'] = '';
                if (val('use_category', $board) && val('post_category', $val)) {
                    $result['list'][$key]['category']
                        = $this->Board_category_model
                        ->get_category_info(val('brd_id', $val), val('post_category', $val));
                }
                $result['list'][$key]['ppo_id'] = '';

                if ($this->uri->getQuery()) {
                    $result['list'][$key]['post_url'] .= '?' . $this->uri->getQuery();
                }
                $result['list'][$key]['num'] = $list_num--;
                $result['list'][$key]['is_hot'] = false;

                $hot_icon_day = val('hot_icon_day', $board);

                $hot_icon_hit = val('hot_icon_hit', $board);

                if ($hot_icon_day && ( time() - strtotime(val('post_datetime', $val)) <= $hot_icon_day * 86400)) {
                    if ($hot_icon_hit && $hot_icon_hit <= val('post_hit', $val)) {
                        $result['list'][$key]['is_hot'] = true;
                    }
                }
                $result['list'][$key]['is_new'] = false;
                $new_icon_hour = val('new_icon_hour', $board);

                if ($new_icon_hour && ( time() - strtotime(val('post_datetime', $val)) <= $new_icon_hour * 3600)) {
                    $result['list'][$key]['is_new'] = true;
                }


                $result['list'][$key]['is_mobile'] = false;

                $result['list'][$key]['thumb_url'] = '';
                $result['list'][$key]['origin_image_url'] = '';
                if (val('use_gallery_list', $board)) {
                    if (val('post_image', $val)) {
                        $filewhere = array(
                            'post_id' => val('post_id', $val),
                            'pfi_is_image' => 1,
                        );
                        $file = $this->Post_file_model
                            ->get_one('', '', $filewhere, '', '', 'pfi_id', 'ASC');
                        $result['list'][$key]['thumb_url'] = thumb_url('post', val('pfi_filename', $file), $gallery_image_width, $gallery_image_height);
                        $result['list'][$key]['origin_image_url'] = thumb_url('post', val('pfi_filename', $file));
                    } else {
                        $thumb_url = get_post_image_url(val('post_content', $val), $gallery_image_width, $gallery_image_height);
                        $result['list'][$key]['thumb_url'] = $thumb_url
                            ? $thumb_url
                            : thumb_url('', '', $gallery_image_width, $gallery_image_height);

                        $result['list'][$key]['origin_image_url'] = $thumb_url;
                    }
                }
            }
        }

        $return['data'] = $result;

        $return['board'] = $board;



        /**
         * primary key 정보를 저장합니다
         */
        $return['primary_key'] = $this->postModel->primaryKey;

        $highlight_keyword = '';
        if ($skeyword) {
            if ( ! $this->session->userdata('skeyword_' . $skeyword)) {
                $sfieldarray = array(
                    'post_title',
                    'post_content',
                    'post_both',
                );
                if (in_array($sfieldchk, $sfieldarray)) {
                    $this->load->model('Search_keyword_model');
                    $searchinsert = array(
                        'sek_keyword' => $skeyword,
                        'sek_datetime' => date('Y-m-d H:i:s'),
                        'sek_ip' => $this->request->getIPAddress(),
                        'mem_id' => $mem_id,
                    );
                    $this->Search_keyword_model->insert($searchinsert);
                    $this->session->set_userdata(
                        'skeyword_' . $skeyword,
                        1
                    );
                }
            }
            $key_explode = explode(' ', $skeyword);
            if ($key_explode) {
                foreach ($key_explode as $seval) {
                    if ($highlight_keyword) {
                        $highlight_keyword .= ',';
                    }
                    $highlight_keyword .= '\'' . esc($seval) . '\'';
                }
            }
        }
        $return['highlight_keyword'] = $highlight_keyword;

        // Call makeLinks() to make pagination links.
        $return['paging'] = service('pager')->makeLinks($page, $per_page, $result['total_rows']);
        $return['page'] = $page;

        return $return;
    }

}