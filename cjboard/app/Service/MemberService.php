<?php
namespace App\Service;

use Gregwar\Captcha\CaptchaBuilder;

class MemberService {

    private $memberModel;
    private $session;
    private $member;
    private $validation;
    private $request;

    public function __construct() {
        $this->session = session();
        $this->memberModel = model('MemberModel');
        $this->validation = service('validation');
        $this->request = service('request');
    }

    public function isMember() {
        return $this->session->get('mem_id') ?? false;
    }

    public function isAdmin($check = array())
    {
        if ($this->item('mem_is_admin')) {
            return 'super';
        }
        if (val('group_id', $check)) {
            $isGroupAdmin = service('BoardService')->isGroupAdmin(val('group_id', $check));
            if ($isGroupAdmin) {
                return 'group';
            }
        }
        if (val('board_id', $check)) {
            $isBoardAdmin = service('BoardService')->isAdmin(val('board_id', $check));
            if ($isBoardAdmin) {
                return 'board';
            }
        }
        return false;
    }

    public function item($column = '')
    {
        if (empty($column)) {
            return false;
        }
        if (empty($this->member)) {
            $this->getMember();
        }
        if (empty($this->member)) {
            return false;
        }

        return isset($this->member[$column]) ? $this->member[$column] : false;
    }


    public function accessCheck() {
        if ($this->isMember()
            && ! ($this->member->isAdmin() === 'super' && $this->uri->segment(1) === config_item('uriSegmentAdmin'))) {
            redirect();
        }
    }

    public function getMember()
    {
        if ($this->isMember()) {
            if (empty($this->member)) {
                $this->member = $this->memberModel->getByMemid($this->isMember());
            }
            return $this->member;
        } else {
            return false;
        }
    }

    public function captcha() {
        // Creating the captcha instance and setting the phrase in the session to store
        // it for check when the form is submitted
        $captcha = new CaptchaBuilder;
        $this->session->set('captcha', $captcha->getPhrase());

        // Setting the header to image jpeg because we here render an image
        header('Content-Type: image/jpeg');

        // Running the actual rendering of the captcha image
        $captcha
            ->build()
            ->output()
        ;
    }

    public function agreement()
    {

        $view = array();

        $config = [
            'agree' => [
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '회원가입약관 필수',
                ],
            ],
            'agree2' => [
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '개인정보취급방침 필수',
                ],
            ],
        ];

        $view['valid'] = true;
        $this->validation->setRules($config);
        if (! $this->validation->withRequest($this->request)->run()) {

            $this->session->remove('registeragree');

            $view['view']['member_register_policy1'] = config_item_db('member_register_policy1');
            $view['view']['member_register_policy2'] = config_item_db('member_register_policy2');

            if ($this->request->getMethod() == 'post') {
                $view['errors'] = $this->validation->getErrors();
            }
            $view['valid'] = false;
        } else {
            $this->session->set('registeragree', '1');
        }

        return $view;
    }

    public function login() {
        $view = array();
        $view['view'] = array();

        $config = [
            'mem_userid' => [
                'rules' => 'required|alpha_dash|min_length[3]|max_length[20]',
            ],
            'mem_password' => [
                'rules' => 'required|min_length[4]|check_id_pw',
            ],
        ];

        $view['valid'] = true;
        $this->validation->setRules($config);
        if (! $this->validation->withRequest($this->request)->run()) {
            if ($this->request->getMethod() == 'post') {
                $view['errors'] = $this->validation->getErrors();
            }
            $view['valid'] = false;
        } else {
            $userinfo = $this->memberModel->getByUserid($this->request->getPost('mem_userid'));
            $this->updateLoginLog(val('mem_id', $userinfo), $this->request->getPost('mem_userid'), 1, '로그인 성공');
            $this->session->set('mem_id', val('mem_id', $userinfo));
/*
            if ($this->input->post('autologin')) {
                $vericode = array('$', '/', '.');
                $hash = str_replace(
                    $vericode,
                    '',
                    password_hash(random_string('alnum', 10) . val('mem_id', $userinfo) . ctimestamp() . val('mem_userid', $userinfo), PASSWORD_BCRYPT)
                );
                $insertautologin = array(
                    'mem_id' => val('mem_id', $userinfo),
                    'aul_key' => $hash,
                    'aul_ip' => $this->input->ip_address(),
                    'aul_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->load->model(array('Autologin_model'));
                $this->Autologin_model->insert($insertautologin);

                $cookie_name = 'autologin';
                $cookie_value = $hash;
                $cookie_expire = 2592000; // 30일간 저장
                set_cookie($cookie_name, $cookie_value, $cookie_expire);
            }
*/
        }

        return $view;
    }

    public function changePassword() {
        $changePasswordDate = config_item_db('change_password_date');
        $site_title = config_item_db('site_title');
        if ($changePasswordDate) {
            $metaChangePwDatetime = config_item_db('meta_change_pw_datetime');
            if ( time() - strtotime($metaChangePwDatetime) > $changePasswordDate * 86400) {
                $this->session->set('membermodify', '1');
                $this->session->setFlashdata('message', esc($site_title) . ' 은(는) 회원님의 비밀번호를 주기적으로 변경하도록 권장합니다.
					<br /> 오래된 비밀번호를 사용중인 회원님께서는 안전한 서비스 이용을 위해 비밀번호 변경을 권장합니다');
                return true;
            }
        }
        return false;
    }

    public function updateLoginLog($mem_id= 0, $userid = '', $success= 0, $reason = '') {
        helper('url');
        $success = $success ? 1 : 0;
        $mem_id = (int) $mem_id ? (int) $mem_id : 0;
        $reason = isset($reason) ? $reason : '';
        $referer = $this->request->getPost('url');
        $agent = $this->request->getUserAgent();
        $loginlog = array(
            'mll_success' => $success,
            'mem_id' => $mem_id,
            'mll_userid' => $userid,
            'mll_datetime' => date('Y-m-d H:i:s'),
            'mll_ip' => $this->request->getIPAddress(),
            'mll_reason' => $reason,
            'mll_useragent' => $agent->getAgentString(),
            'mll_url' => current_url(),
            'mll_referer' => $referer,
        );
        model('MemberLoginLogModel')->insert($loginlog);
    }

}