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
    private $session;
    
    public function __construct()
    {
        $this->boardModel = model('BoardModel');
        $this->boardMetaModel = model('BoardMetaModel');
        $this->memberService = service('memberService');
        $this->uri = service('uri');
        $this->request = service('request');
        $this->postModel = model('PostModel');
        $this->session = session();
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

        $check = array(
            'group_id' => val('bgr_id', $board),
            'board_id' => val('brd_id', $board),
        );
        $can_write = service('AccesslevelService')->isAccessable(
            val('access_write', $board),
            val('access_write_level', $board),
            val('access_write_group', $board),
            $check
        );

        $return['write_url'] = '';
        if ($can_write === true) {
            $return['write_url'] = write_url($brdKey);
        } elseif (val('always_show_write_button', $board)) {
            $return['write_url'] = 'javascript:alert(\'비회원은 글쓰기 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.\');';
        }

        $return['list_delete_url'] = site_url('postact/listdelete/' . $brdKey . '?' . $this->uri->getQuery());



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

    public function post($post_id) {

        $view = array();
        $view['view'] = array();

        $post = $this->postModel->getOne($post_id);
        $view['view']['post'] = $post;

        $mem_id = (int) $this->memberService->item('mem_id');

        if ( ! val('post_id', $post)) {
            throw new \Exception('post_id가 존재하지 않음');
        }
        if (val('post_del', $post) > 1) {
            throw new \Exception('삭제된 게시글');
        }

        $board = $this->item_all(val('brd_id', $post));

        if ( ! val('brd_id', $board)) {
            show_404();
        }

        $skeyword = $this->request->getGet('skeyword', null, '');

        $alertmessage = $this->memberService->isMember()
            ? '회원님은 내용을 볼 수 있는 권한이 없습니다'
            : '비회원은 내용을 볼 수 있는 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오';

        $check = array(
            'group_id' => val('bgr_id', $board),
            'board_id' => val('brd_id', $board),
        );
        //$this->accesslevel->check(
        //    val('access_view', $board),
        //    val('access_view_level', $board),
         //   val('access_view_group', $board),
         //   $alertmessage,
         //   $check
        //);


        $view['view']['is_admin'] = $is_admin = $this->memberService->isAdmin(
            array(
                'board_id' => val('brd_id', $board),
                'group_id' => val('bgr_id', $board)
            )
        );
        $view['view']['board_key'] = val('brd_key', $board);

        if (val('use_personal', $board) && $this->memberService->isMember() === false) {
            throw new \Exception('이 게시판은 1:1 게시판입니다. 비회원은 접근할 수 없습니다');
        }


        if (val('post_secret', $post)) {
            if (val('mem_id', $post)) {
                if ($is_admin === false && $mem_id !== abs(val('mem_id', $post))) {
                    throw new \Exception('비밀글은 본인과 관리자만 확인 가능합니다');
                }
            } else {
                if ($is_admin !== false) {
                    $this->session->set(
                        'view_secret_' . val('post_id', $post),
                        '1'
                    );
                }
                if ( ! $this->session->get('view_secret_' . val('post_id', $post))
                    && $this->request->getPost('modify_password')) {

                    if ( password_verify($this->request->getPost('modify_password'), val('post_password', $post))) {
                        $this->session->set(
                            'view_secret_' . val('post_id', $post),
                            '1'
                        );
                        redirect(current_url());
                    } else {
                        $view['view']['message'] = '패스워드가 잘못 입력되었습니다';
                    }
                }
                if ( ! $this->session->get('view_secret_' . val('post_id', $post))) {
                    return true;
                }
            }
        }


        if (val('use_personal', $board) && $is_admin === false
            && $mem_id !== abs(val('mem_id', $post))) {
            alert('1:1 게시판은 본인의 글 이외의 열람이 금지되어있습니다.');
            return false;
        }

        $this->_stat_count_board(val('brd_id', $board)); // stat_count_board ++

        // 세션 생성
        if ( ! $this->session->get('post_id_' . $post_id)) {
            $this->postModel->update_plus($post_id, 'post_hit', 1);
            $this->session->set(
                'post_id_' . $post_id,
                '1'
            );
        }

        $use_sideview = val('use_sideview', $board);
        $use_sideview_icon = val('use_sideview_icon', $board);
        $view_date_style = val('view_date_style', $board);
        $view_date_style_manual = val('view_date_style_manual', $board);

        if (val('mem_id', $post) >= 0) {
            $dbmember = model('memberModel')->getByMemid(val('mem_id', $post), 'mem_icon');
            $view['view']['post']['display_name'] = display_username(
                val('post_userid', $post),
                val('post_nickname', $post),
                ($use_sideview_icon ? val('mem_icon', $dbmember) : ''),
                ($use_sideview ? 'Y' : 'N')
            );
        } else {
            $view['view']['post']['display_name'] = '익명사용자';
        }
        $view['view']['post']['display_datetime'] = display_datetime(
            val('post_datetime', $post),
            $view_date_style,
            $view_date_style_manual
        );

        $view['view']['post']['is_mobile'] = (val('post_device', $post) === 'mobile') ? true : false;
        $view['view']['post']['category'] = '';

        if (val('use_category', $board) && val('post_category', $post)) {
            $this->load->model('Board_category_model');
            $view['view']['post']['category'] = $this->Board_category_model
                ->get_category_info(val('brd_id', $post), val('post_category', $post));
        }

        $view['view']['post']['display_ip'] = '';

        $show_ip = val('show_ip', $board);

        if ($this->memberService->isAdmin() === 'super' OR $show_ip === '2') {
            $view['view']['post']['display_ip'] = display_ipaddress(val('post_ip', $post), '1111');
        } elseif ($show_ip === '1') {
            $view['view']['post']['display_ip'] = display_ipaddress(val('post_ip', $post), config_item_db('ip_display_style'));
        }
        $image_width = val('post_image_width', $board);

        $board['target_blank'] = $target_blank
            = val('content_target_blank', $board);

        $board['show_url_qrcode'] = val('use_url_qrcode', $board);

        $board['show_attached_url_qrcode'] = val('use_attached_url_qrcode', $board);

        $link_player = '';
        $view['view']['link'] = $link = array();

        if (val('post_link_count', $post)) {
            $this->load->model('Post_link_model');
            $linkwhere = array(
                'post_id' => $post_id,
            );
            $view['view']['link'] = $link = $this->Post_link_model
                ->get('', '', $linkwhere, '', '', 'pln_id', 'ASC');
            if ($link && is_array($link)) {
                foreach ($link as $key => $value) {
                    $view['view']['link'][$key]['link_link'] = site_url('postact/link/' . val('pln_id', $value));
                    if (val('use_autoplay', $board)) {
                        $link_player .= $this->videoplayer->
                        get_video(prep_url(val('pln_url', $value)));
                    }
                }
            }
        }
        $view['view']['link_count'] = $link_count = count($link);

        $file_player = '';
        if (val('post_file', $post) OR val('post_image', $post)) {
            $this->load->model('Post_file_model');
            $filewhere = array(
                'post_id' => $post_id,
            );
            $view['view']['file'] = $file = $this->Post_file_model
                ->get('', '', $filewhere, '', '', 'pfi_id', 'ASC');
            $view['view']['file_download'] = array();
            $view['view']['file_image'] = array();

            $play_extension = array('acc', 'flv', 'f4a', 'f4v', 'mov', 'mp3', 'mp4', 'm4a', 'm4v', 'oga', 'ogg', 'rss', 'webm');

            if ($file && is_array($file)) {
                foreach ($file as $key => $value) {
                    if (val('pfi_is_image', $value)) {
                        $value['origin_image_url'] = site_url(config_item('uploads_dir') . '/post/' . val('pfi_filename', $value));
                        $value['thumb_image_url'] = thumb_url('post', val('pfi_filename', $value), $image_width);
                        $view['view']['file_image'][] = $value;
                    } else {
                        $value['download_link'] = site_url('postact/download/' . val('pfi_id', $value));
                        $view['view']['file_download'][] = $value;
                        if (val('use_autoplay', $board) && in_array(val('pfi_type', $value), $play_extension)) {
                            $file_player .= $this->videoplayer->get_jwplayer(site_url(config_item('uploads_dir') . '/post/' . val('pfi_filename', $value)), $image_width);
                        }
                    }
                }
            }
            $view['view']['file_count'] = count($file);
            $view['view']['file_download_count'] = count($view['view']['file_download']);
            $view['view']['file_image_count'] = count($view['view']['file_image']);
        }

        $autourl = val('use_auto_url', $board);

        $autolink = $autourl ? true : false;
        $popup = $target_blank ? true : false;

        $view['view']['post']['content'] = '';

        if (val('post_del', $post)) {

            $view['view']['post']['post_title'] = '게시물이 삭제되었습니다';
            $view['view']['post']['content'] = '<div class="alert alert-danger">이 게시물은 '
                . html_escape(val('delete_mem_nickname', val('meta', $post)))
                . '님에 의해 '
                . html_escape(val('delete_datetime', val('meta', $post)))
                . ' 에 삭제 되었습니다</div>';

        } else {
            $is_blind = (val('blame_blind_count', $board) > 0 && val('post_blame', $post) >= val('blame_blind_count', $board)) ? true : false;
            if ($is_blind === true) {
                $view['view']['post']['content'] .= '<div class="alert alert-danger">신고가 접수된 게시글입니다. 본인과 관리자만 확인이 가능합니다</div>';
            }

            if ($is_blind === false OR $is_admin !== false
                OR (val('mem_id', $post) && abs(val('mem_id', $post)) === $mem_id)) {
                $view['view']['post']['content'] .= $file_player . $link_player
                    . display_html_content(
                        val('post_content', $post),
                        val('post_html', $post),
                        $image_width,
                        $autolink,
                        $popup
                    );

                if (val('syntax_highlighter', $board)) {
                    if (val('post_html', $post)) {
                        $view['view']['post']['content'] = preg_replace_callback(
                            "/(\[code\]|\[code=(.*)\])(.*)\[\/code\]/iUs",
                            "content_syntaxhighlighter_html",
                            $view['view']['post']['content']
                        ); // SyntaxHighlighter
                    } else {
                        $view['view']['post']['content'] = preg_replace_callback(
                            "/(\[code\]|\[code=(.*)\])(.*)\[\/code\]/iUs",
                            "content_syntaxhighlighter",
                            $view['view']['post']['content']
                        ); // SyntaxHighlighter
                    }
                }
            }

            $view['view']['tag'] = '';
            if (val('use_post_tag', $board)) {

                $tagwhere = array(
                    'post_id' => $post_id,
                );
                $view['view']['post']['tag'] = $tag = model('PostTagModel')->get('', '', $tagwhere, '', '', 'pta_id', 'ASC');
            }

            $extravars = val('extravars', $board);
            $form = json_decode($extravars, true);
            $extra_content = '';
            $k = 0;
            if ($form && is_array($form)) {
                foreach ($form as $key => $value) {
                    if ( ! val('use', $value)) {
                        continue;
                    }

                    $item = val(val('field_name', $value), val('extravars', $post));
                    $extra_content[$k]['field_name'] = val('field_name', $value);
                    $extra_content[$k]['display_name'] = val('display_name', $value);
                    if (val('field_type', $value) === 'checkbox') {
                        $tmp_value = json_decode($item);
                        $tmp = '';
                        if ($tmp_value) {
                            foreach ($tmp_value as $val) {
                                if ($tmp) {
                                    $tmp .= ', ';
                                }
                                $tmp .= $val;
                            }
                        }
                        $item = $tmp;
                    }
                    $extra_content[$k]['output'] = $item;
                    $k++;
                }
            }

            $view['view']['extra_content'] = $extra_content;
        }
        $show_list_from_view = val('show_list_from_view', $board);

        $board['headercontent'] = val('header_content', $board);

        if (empty($show_list_from_view)) {
            $board['footercontent'] = val('footer_content', $board);
        }


        $view['view']['post_url'] = $post_url = post_url(val('brd_key', $board), $post_id);

        $view['view']['board'] = $board;


        $view['view']['comment']['is_cmt_name'] = $is_cmt_name
            = ($this->memberService->isMember() === false) ? true : false;

        $view['view']['comment']['use_emoticon']
            = val('use_comment_emoticon', $board);

        $view['view']['comment']['use_specialchars']
            = val('use_comment_specialchars', $board);

        $view['view']['comment']['show_textarea']
            = val('always_show_comment_textarea', $board);

        $check = array(
            'group_id' => val('bgr_id', $board),
            'board_id' => val('brd_id', $board)
        );
        /*
        $can_write = $this->accesslevel->is_accessable(
            val('access_write', $board),
            val('access_write_level', $board),
            val('access_write_group', $board),
            $check
        );
        $can_comment_write = $this->accesslevel->is_accessable(
            val('access_comment', $board),
            val('access_comment_level', $board),
            val('access_comment_group', $board),
            $check
        );
        */
        $can_comment_write = $can_write = $can_reply = false;

        $can_comment_write_message = '';
        if ($can_comment_write === false) {
            $can_comment_write_message = '비회원은 댓글쓰기 권한이 없습니다. 회원이시라면 로그인후 이용해보십시오';
        }
        //$can_reply = $this->accesslevel->is_accessable(
        //    val('access_reply', $board),
        //    val('access_reply_level', $board),
       //     val('access_reply_group', $board),
       //     $check
       // );


        $can_modify = ($is_admin !== false OR ! val('mem_id', $post)
            OR (val('mem_id', $post) && $mem_id === abs(val('mem_id', $post)))) ? true : false;
        $can_delete = ($is_admin !== false OR ! val('mem_id', $post)
            OR (val('mem_id', $post) && $mem_id === abs(val('mem_id', $post)))) ? true : false;

        $view['view']['write_url'] = '';
        if ($can_write === true) {
            $view['view']['write_url'] = write_url(val('brd_key', $board));
        } elseif (val('always_show_write_button', $board)) {
            $view['view']['write_url'] = 'javascript:alert(\'비회원은 글쓰기 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.\');';
        } elseif (val('mobile_always_show_write_button', $board)) {
            $view['view']['write_url'] = 'javascript:alert(\'비회원은 글쓰기 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.\');';
        }

        $view['view']['reply_url'] = ($can_reply === true && ! val('post_del', $post))
            ? reply_url(val('post_id', $post)) : '';
        $view['view']['modify_url'] = ($can_modify && ! val('post_del', $post))
            ? modify_url(val('post_id', $post) . '?' . $this->uri->getQuery()) : '';
        $view['view']['delete_url'] = ($can_delete && ! val('post_del', $post))
            ? site_url('postact/delete/' . val('post_id', $post) . '?' . $this->uri->getQuery()) : '';

        if ($skeyword) {
            $view['view']['list_url'] = board_url(val('brd_key', $board));
            $view['view']['search_list_url'] = board_url(val('brd_key', $board) . '?' . $this->uri->getQuery());
        } else {
            $view['view']['list_url'] = board_url(val('brd_key', $board) . '?' . $this->uri->getQuery());
            $view['view']['search_list_url'] = '';
        }
        $view['view']['trash_url'] = site_url('boards/trash/' . val('post_id', $post) . '?' . $this->uri->getQuery());

        if (val('notice_comment_block', $board) && val('post_notice', $post)) {
            $can_comment_write = false;
            $can_comment_write_message = '공지사항 글에는 댓글 입력이 제한되어 있습니다.';
        }
        if (val('post_del', $post)) {
            $can_comment_write = false;
            $can_comment_write_message = '삭제된 글에는 댓글 입력이 제한되어 있습니다.';
        }


        $highlight_keyword = '';
        if ($skeyword) {
            $key_explode = explode(' ', $skeyword);
            if ($key_explode) {
                foreach ($key_explode as $seval) {
                    if ($highlight_keyword) {
                        $highlight_keyword .= ',';
                    }
                    $highlight_keyword .= '\'' . html_escape($seval) . '\'';
                }
            }
        }
        $view['view']['highlight_keyword'] = $highlight_keyword;

        $view['view']['next_post'] = '';
        $view['view']['prev_post'] = '';
        $use_prev_next = false;

        if (val('use_prev_next_post', $board)) {
            $use_prev_next = true;
        }
        if (val('use_mobile_prev_next_post', $board)) {
            $use_prev_next = true;
        }
        if ($use_prev_next) {
            $where = array();
            $where['brd_id'] = val('brd_id', $post);

            $where['post_del <>'] =2;
            $where['post_secret'] = 0;
            if (val('except_notice', $board)) {
                $where['post_notice'] = 0;
            }
            if (val('mobile_except_notice', $board)) {
                $where['post_notice'] = 0;
            }
            if (val('use_personal', $board) && $is_admin === false) {
                $where['post.mem_id'] = $mem_id;
            }
            $sfield = $sfieldchk = $this->request->getGet('sfield', null, '');
            if ($sfield === 'post_both') {
                $sfield = array('post_title', 'post_content');
            }
            $skeyword = $this->request->getGet('skeyword', null, '');

            $view['view']['next_post'] = $next_post = $this->postModel->get_prev_next_post(
                    val('post_id', $post),
                    val('post_num', $post),
                    'next',
                    $where,
                    $sfield,
                    $skeyword
                );

            if (val('post_id', $next_post)) {
                $view['view']['next_post']['url'] = post_url(val('brd_key', $board), val('post_id', $next_post)) . '?' . $this->uri->getQuery();
            }

            $view['view']['prev_post'] = $prev_post = $this->postModel->get_prev_next_post(
                    val('post_id', $post),
                    val('post_num', $post),
                    'prev',
                    $where,
                    $sfield,
                    $skeyword
                );

            if (val('post_id', $prev_post)) {
                $view['view']['prev_post']['url'] = post_url(val('brd_key', $board), val('post_id', $prev_post)) . '?' . $this->uri->getQuery();
            }
        }

        $view['view']['comment']['can_comment_write'] = $can_comment_write;
        $view['view']['comment']['can_comment_write_message']
            = $can_comment_write_message;
        $view['view']['comment']['can_comment_view'] = true;

        $view['view']['comment']['is_comment_name']
            = ($this->memberService->isMember() === false) ? true : false;
        $view['view']['comment']['can_comment_secret']
            = (val('use_comment_secret', $board) === '1' && $this->memberService->isMember())
            ? true : false;
        $view['view']['comment']['cmt_secret']
            = val('use_comment_secret_selected', $board) ? '1' : '';

        $password_length = config_item_db('password_length');
        $view['view']['comment']['password_length'] = $password_length;
        $view['view']['comment']['cmt_content']
            = val('comment_default_content', $board);

        if ($show_list_from_view) {
            $view['view']['list'] = $list = $this->_get_list(val('brd_key', $board), 1);
        }

        return $view;
    }

    public function _stat_count_board($brd_id = 0)
    {
        if (empty($brd_id)) {
            return false;
        }

        helper('cookie');
        // 방문자 기록
        if ( ! get_cookie('board_id_' . $brd_id)) {
            $cookie_name = 'board_id_' . $brd_id;
            $cookie_value = '1';
            $cookie_expire = 86400; // 1일간 저장
            set_cookie($cookie_name, $cookie_value, $cookie_expire);

            model('StatCountBoardModel')->add_visit_board($brd_id);

        }
    }

}