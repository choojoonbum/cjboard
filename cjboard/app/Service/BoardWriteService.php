<?php
namespace App\Service;

class BoardWriteService {

    private $uri;
    private $postModel;
    private $memberService;
    private $validation;
    private $request;

    public function __construct()
    {
        $this->uri = service('uri');
        $this->postModel = model('PostModel');
        $this->memberService = service('memberService');
        $this->validation = service('validation');
        $this->request = service('request');
    }

    public function _write_common($board, $origin = '', $reply = '')
    {
        $param = $this->uri->getQuery();

        $view = array();
        $view['view'] = array();
        $view['view']['post'] = array();

        $view['view']['board'] = $board;
        $view['view']['board_key'] = val('brd_key', $board);
        $mem_id = $this->memberService->item('mem_id');

        $primary_key = $this->postModel->primaryKey;

        $view['view']['is_admin'] = $is_admin = $this->memberService->isAdmin(
            array(
                'board_id' => val('brd_id', $board),
                'group_id' => val('bgr_id', $board),
            )
        );
/*
        // 글 한개만 작성 가능
        if (val('use_only_one_post', $board) && $is_admin === false) {
            if ($this->memberService->isMember() === false) {
                alert('비회원은 글을 작성할 수 있는 권한이 없습니다. 회원이사라면 로그인 후 이용해주세요');
            }
            $mywhere = array(
                'brd_id' => val('brd_id', $board),
                'mem_id' => $mem_id,
            );
            $cnt = $this->Post_model->count_by($mywhere);
            if ($cnt) {
                alert('이 게시판은 한 사람이 하나의 글만 등록 가능합니다.');
            }
        }

        // 글쓰기 기간제한
        if (val('write_possible_days', $board) && $is_admin === false) {
            if ($this->memberService->isMember() === false) {
                alert('비회원은 글을 작성할 수 있는 권한이 없습니다. 회원이사라면 로그인 후 이용해주세요');
            }

            if ((ctimestamp() - strtotime($this->memberService->item('mem_register_datetime'))) < val('write_possible_days', $board) * 86400 ) {
                alert('이 게시판은 회원가입한지 ' . val('write_possible_days', $board) . '일이 지난 회원만 게시물 작성이 가능합니다');
            }
        }

        if ($this->session->userdata('lastest_post_time') && $this->cbconfig->item('new_post_second')) {
            if ($this->session->userdata('lastest_post_time') >= ( ctimestamp() - $this->cbconfig->item('new_post_second')) && $is_admin === false) {
                alert('너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.\\n\\n' . ($this->cbconfig->item('new_post_second') - (ctimestamp() - $this->session->userdata('lastest_post_time'))) . '초 후 글쓰기가 가능합니다');
            }
        }
*/

        $view['view']['post']['is_post_name'] = $is_post_name
            = ($this->memberService->isMember() === false) ? true : false;
        $view['view']['post']['post_title']
            = val('post_default_title', $board);
        $view['view']['post']['post_content']
            = val('post_default_content', $board);
        $view['view']['post']['can_post_notice'] = $can_post_notice = ($is_admin !== false) ? true : false;
        $view['view']['post']['can_post_secret'] = $can_post_secret
            = val('use_post_secret', $board) === '1' ? true : false;
        $view['view']['post']['post_secret'] = val('use_post_secret_selected', $board) ? '1' : '';
        $view['view']['post']['can_post_receive_email'] = $can_post_receive_email
            = val('use_post_receive_email', $board) ? true : false;

        $extravars = val('extravars', $board);
        $form = json_decode($extravars, true);
        $use_subj_style = val('use_subject_style', $board);
        $use_poll = val('use_poll', $board);

        $config = [
            'post_title' => [
                'rules'  => 'trim|required',
            ],
            'post_content' => [
                'rules'  => 'trim|required',
            ],
        ];

        if ($form && is_array($form)) {
            foreach ($form as $key => $value) {
                if ( ! val('use', $value)) {
                    continue;
                }
                $required = val('required', $value) ? '|required' : '';
                if (val('field_type', $value) === 'checkbox') {
                    $config[val('field_name', $value) . '[]'] = array(
                        'rules' => 'trim' . $required,
                    );
                } else {
                    $config[val('field_name', $value)] = array(
                        'rules' => 'trim' . $required,
                    );
                }
            }
        }

        if ($is_post_name) {
            //|callback__mem_nickname_check
            $config['post_nickname'] = array(
                'rules' => 'required|min_length[2]|max_length[20]',
            );
            //|callback__mem_email_check
            $config['post_email'] = array(
                'rules' => 'required|valid_email|max_length[50]',
            );
            $config['post_homepage'] = array(
                'rules' => 'valid_url',
            );
        }
        if ($this->memberService->isMember() === false) {
            $password_length = config_item_db('password_length');
            //|callback__mem_password_check
            $config['post_password'] = array(
                'rules' => 'required|min_length[' . $password_length . ']',
            );
        }

        //$config['captcha_key'] = array(
        ///    'rules' => 'required|valid_captcha',
        //);

        if ($use_subj_style) {
            $config['post_title_color'] = array(
                'rules' => 'exact_length[7]',
            );
            $config['post_title_bold'] = array(
                'rules' => 'exact_length[1]',
            );
        }
        if (val('use_category', $board) && $is_admin === false) {
            $config['post_category'] = array(
                'rules' => 'required',
            );
        }

        $this->validation->setRules($config);
        $form_validation = $this->validation->withRequest($this->request)->run();

        $file_error = '';
        $uploadfiledata = array();

        $use_upload = false;
        $use_dhtml = false;
        $view['view']['board']['use_dhtml'] = $use_dhtml;

        $use_subject_style = false;
        $view['view']['board']['use_subject_style'] = $use_subject_style;

        $can_poll_write = false;
        $view['view']['board']['can_poll_write'] = $can_poll_write;

        $can_tag_write = false;
        $view['view']['board']['can_tag_write'] = $can_tag_write;

        $view['view']['board']['link_count']
            = val('link_num', $board);

        $view['view']['board']['use_emoticon']
            = val('use_post_emoticon', $board);

        $view['view']['board']['use_specialchars']
            = val('use_post_specialchars', $board);

        $view['view']['board']['headercontent']
            = val('header_content', $board);

        $view['view']['board']['footercontent']
            = val('footer_content', $board);

        $view['valid'] = true;
        if ($form_validation === false OR $file_error) {
      
            if ($file_error) {
                $view['view']['message'] = $file_error;
            }

            /**
             * primary key 정보를 저장합니다
             */
            $view['view']['primary_key'] = $primary_key;

            $extra_content = array();

            $k= 0;
            if ($form && is_array($form)) {
                foreach ($form as $key => $value) {
                    if ( ! val('use', $value)) {
                        continue;
                    }
                    $required = val('required', $value) ? 'required' : '';

                    $extra_content[$k]['field_name'] = val('field_name', $value);
                    $extra_content[$k]['display_name'] = val('display_name', $value);
                    $extra_content[$k]['input'] = '';

                    //field_type : text, url, email, phone, textarea, radio, select, checkbox, date
                    if (val('field_type', $value) === 'text'
                        OR val('field_type', $value) === 'url'
                        OR val('field_type', $value) === 'email'
                        OR val('field_type', $value) === 'phone'
                        OR val('field_type', $value) === 'date') {

                        if (val('field_type', $value) === 'date') {
                            $extra_content[$k]['input'] .= '<input type="text" id="' . val('field_name', $value) . '" name="' . val('field_name', $value) . '" class="form-control input datepicker" value="' . set_value(val('field_name', $value)) . '" readonly="readonly" ' . $required . ' />';
                        } elseif (val('field_type', $value) === 'phone') {
                            $extra_content[$k]['input'] .= '<input type="text" id="' . val('field_name', $value) . '" name="' . val('field_name', $value) . '" class="form-control input validphone" value="' . set_value(val('field_name', $value)) . '" ' . $required . ' />';
                        } else {
                            $extra_content[$k]['input'] .= '<input type="' . val('field_type', $value) . '" id="' . val('field_name', $value) . '" name="' . val('field_name', $value) . '" class="form-control input" value="' . set_value(val('field_name', $value)) . '" ' . $required . '/>';
                        }
                    } elseif (val('field_type', $value) === 'textarea') {
                        $extra_content[$k]['input'] .= '<textarea id="' . val('field_name', $value) . '" name="' . val('field_name', $value) . '" class="form-control input" ' . $required . '>' . set_value(val('field_name', $value)) . '</textarea>';
                    } elseif (val('field_type', $value) === 'radio') {
                        $extra_content[$k]['input'] .= '<div class="checkbox">';
                        $options = explode("\n", val('options', $value));
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $radiovalue = $oval;
                                $extra_content[$k]['input'] .= '<label for="' . val('field_name', $value) . '_' . $i . '"><input type="radio" name="' . val('field_name', $value) . '" id="' . val('field_name', $value) . '_' . $i . '" value="' . $radiovalue . '" ' . set_radio(val('field_name', $value), $radiovalue) . ' /> ' . $oval . ' </label> ';
                                $i++;
                            }
                        }
                        $extra_content[$k]['input'] .= '</div>';
                    } elseif (val('field_type', $value) === 'checkbox') {
                        $extra_content[$k]['input'] .= '<div class="checkbox">';
                        $options = explode("\n", val('options', $value));
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $extra_content[$k]['input'] .= '<label for="' . val('field_name', $value) . '_' . $i . '"><input type="checkbox" name="' . val('field_name', $value) . '[]" id="' . val('field_name', $value) . '_' . $i . '" value="' . $oval . '" ' . set_checkbox(val('field_name', $value), $oval) . ' /> ' . $oval . ' </label> ';
                                $i++;
                            }
                        }
                        $extra_content[$k]['input'] .= '</div>';
                    } elseif (val('field_type', $value) === 'select') {
                        $extra_content[$k]['input'] .= '<div class="input-group">';
                        $extra_content[$k]['input'] .= '<select name="' . val('field_name', $value) . '" class="form-control input" ' . $required . '>';
                        $extra_content[$k]['input'] .= '<option value="" >선택하세요</option> ';
                        $options = explode("\n", val('options', $value));
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $extra_content[$k]['input'] .= '<option value="' . $oval . '" ' . set_select(val('field_name', $value), $oval) . ' >' . $oval . '</option> ';
                            }
                        }
                        $extra_content[$k]['input'] .= '</select>';
                        $extra_content[$k]['input'] .= '</div>';
                    }
                    $k++;
                }
            }

            $view['view']['extra_content'] = $extra_content;

            /*
            if (val('use_category', $board)) {
                $this->load->model('Board_category_model');
                $view['view']['category']
                    = $this->Board_category_model
                    ->get_all_category(val('brd_id', $board));
            }
            */

            if ($this->request->getMethod() == 'post') {
                $view['errors'] = $this->validation->getErrors();
            }
            $view['valid'] = false;

        } else {

            $content_type = $use_dhtml ? 1 : 0;

            if ($origin) {
                $post_num = val('post_num', $origin);
                $post_reply = $reply;
            } else {
                $post_num = $this->postModel->next_post_num();
                $post_reply = '';
            }

            $metadata = array();

            $post_title = $this->request->getPost('post_title');
            $post_content = $this->request->getPost('post_content');

            $updatedata = array(
                'post_num' => $post_num,
                'post_reply' => $post_reply,
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_html' => $content_type,
                'post_datetime' => date('Y-m-d H:i:s'),
                'post_updated_datetime' => date('Y-m-d H:i:s'),
                'post_ip' => $this->request->getIPAddress(),
                'brd_id' => val('brd_id', $board),
            );

            if ($mem_id) {
                if (val('use_anonymous', $board)) {
                    $updatedata['mem_id'] = (-1) * $mem_id;
                    $updatedata['post_userid'] = '';
                    $updatedata['post_username'] = '익명사용자';
                    $updatedata['post_nickname'] = '익명사용자';
                    $updatedata['post_email'] = '';
                    $updatedata['post_homepage'] = '';
                } else {
                    $updatedata['mem_id'] = $mem_id;
                    $updatedata['post_userid'] = $this->memberService->item('mem_userid');
                    $updatedata['post_username'] = $this->memberService->item('mem_username');
                    $updatedata['post_nickname'] = $this->memberService->item('mem_nickname');
                    $updatedata['post_email'] = $this->memberService->item('mem_email');
                    $updatedata['post_homepage'] = $this->memberService->item('mem_homepage');
                }
            }

            if ($is_post_name) {
                $updatedata['post_nickname'] = $this->request->getPost('post_nickname');
                $updatedata['post_email'] = $this->request->getPost('post_email');
                $updatedata['post_homepage'] = $this->request->getPost('post_homepage');
            }

            if ($this->memberService->isMember() === false && $this->request->getPost('post_password')) {
                $updatedata['post_password'] = password_hash($this->request->getPost('post_password'), PASSWORD_BCRYPT);
            }

            if ($can_post_notice) {
                $updatedata['post_notice'] = $this->request->getPost('post_notice');
            }
            if ($can_post_secret) {
                $updatedata['post_secret'] = $this->request->getPost('post_secret') ? 1 : 0;
            }
            if (val('use_post_secret', $board) === '2') {
                $updatedata['post_secret'] = 1;
            }
            if ($can_post_receive_email) {
                $updatedata['post_receive_email'] = $this->request->getPost('post_receive_email') ? 1 : 0;
            }
            if ($use_subject_style) {
                $metadata['post_title_color'] = $this->request->getPost('post_title_color');
                $metadata['post_title_font'] = $this->request->getPost('post_title_font');
                $metadata['post_title_bold'] = $this->request->getPost('post_title_bold');
            }
            if (val('use_category', $board)) {
                $updatedata['post_category'] = $this->request->getPost('post_category');
            }

            $updatedata['post_device']
                = 'desktop';

            $post_id = $this->postModel->insert($updatedata);

            if ($can_post_secret && $this->request->getPost('post_secret')) {
                session()->set(
                    'view_secret_' . $post_id,
                    '1'
                );
            }


            $extradata = array();
            if ($form && is_array($form)) {
                foreach ($form as $key => $value) {
                    if ( ! val('use', $value)) {
                        continue;
                    }
                    if (val('func', $value) === 'basic') {
                        continue;
                    }
                    $extradata[val('field_name', $value)] = $this->request->getPost(val('field_name', $value));
                }
                $this->Post_extra_vars_model
                    ->save($post_id, val('brd_id', $board), $extradata);
            }

            if ($reply && $origin && config_item_db('use_notification') && config_item_db('notification_reply')) {
                $this->load->library('notificationlib');
                $not_message = $updatedata['post_nickname'] . '님께서 [' . val('post_title', $origin) . '] 에 답변을 남기셨습니다';
                $not_url = post_url(val('brd_key', $board), $post_id);
                $this->notificationlib->set_noti(
                    val('mem_id', $origin),
                    $mem_id,
                    'reply',
                    $post_id,
                    $not_message,
                    $not_url
                );
            }

            if (isset($metadata) && $metadata) {
                $this->Post_meta_model
                    ->save($post_id, val('brd_id', $board), $metadata);
            }

            if (val('use_posthistory', $board)) {
                $this->load->model('Post_history_model');
                $historydata = array(
                    'post_id' => $post_id,
                    'brd_id' => val('brd_id', $board),
                    'mem_id' => $mem_id,
                    'phi_title' => $post_title,
                    'phi_content' => $post_content,
                    'phi_content_html_type' => $content_type,
                    'phi_ip' => $this->request->getIPAddress(),
                    'phi_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->Post_history_model->insert($historydata);
            }
            $post_link = $this->request->getPost('post_link');
            $post_link_update_chk = false;
            if ($post_link && is_array($post_link) && count($post_link) > 0) {
                foreach ($post_link as $pkey => $pval) {
                    if ($pval) {
                        $linkupdate = array(
                            'post_id' => $post_id,
                            'brd_id' => val('brd_id', $board),
                            'pln_url' => prep_url($pval),
                        );
                        $this->Post_link_model->insert($linkupdate);
                        $post_link_update_chk = true;
                    }
                }
                $postupdate = array(
                    'post_link_count' => count($post_link),
                );
                if ($post_link_update_chk) $this->Post_model->update($post_id, $postupdate);
            }


            $file_updated = false;
            if ($use_upload && $uploadfiledata
                && is_array($uploadfiledata) && count($uploadfiledata) > 0) {
                foreach ($uploadfiledata as $pkey => $pval) {
                    if ($pval) {
                        $fileupdate = array(
                            'post_id' => $post_id,
                            'brd_id' => val('brd_id', $board),
                            'mem_id' => $mem_id,
                            'pfi_originname' => val('pfi_originname', $pval),
                            'pfi_filename' => val('pfi_filename', $pval),
                            'pfi_filesize' => val('pfi_filesize', $pval),
                            'pfi_width' => val('pfi_width', $pval),
                            'pfi_height' => val('pfi_height', $pval),
                            'pfi_type' => val('pfi_type', $pval),
                            'pfi_is_image' => val('is_image', $pval),
                            'pfi_datetime' => cdate('Y-m-d H:i:s'),
                            'pfi_ip' => $this->request->ip_address(),
                        );
                        $file_id = $this->Post_file_model->insert($fileupdate);
                        $file_updated = true;
                    }
                }
            }
            //$result = $this->Post_file_model->get_post_file_count($post_id);
            $result = false;
            $postupdatedata = array();
            if ($result && is_array($result)) {
                foreach ($result as $value) {
                    if (val('pfi_is_image', $value)) {
                        $postupdatedata['post_image'] = val('cnt', $value);
                    } else {
                        $postupdatedata['post_file'] = val('cnt', $value);
                    }
                }
                $this->Post_model->update($post_id, $postupdatedata);
            }

            if (val('use_post_tag', $board) && $can_tag_write) {
                $this->load->model('Post_tag_model');
                $deletewhere = array(
                    'post_id' => $post_id,
                );
                $this->Post_tag_model->delete_where($deletewhere);
                if ($this->request->getPost('post_tag')) {
                    $tags = explode(',', $this->request->getPost('post_tag'));
                    if ($tags && is_array($tags)) {
                        foreach ($tags as $key => $value) {
                            $value = trim($value);
                            if ($value) {
                                $tagdata = array(
                                    'post_id' => $post_id,
                                    'brd_id' => val('brd_id', $board),
                                    'pta_tag' => $value,
                                );
                                $this->Post_tag_model->insert($tagdata);
                            }
                        }
                    }
                }
            }

            session()->setFlashdata(
                'message',
                '게시물이 정상적으로 입력되었습니다'
            );
            session()->setFlashdata(
                'lastest_post_time',
                time()
            );

            /**
             * 게시물의 신규입력 또는 수정작업이 끝난 후 뷰 페이지로 이동합니다
             */
            $redirecturl = post_url(val('brd_key', $board), $post_id);
            $view['redirecturl'] = $redirecturl;
            $view['redirecturl'] = 'community/notice';
        }


        return $view;

    }

}
