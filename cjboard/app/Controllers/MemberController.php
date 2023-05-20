<?php

namespace App\Controllers;
use Gregwar\Captcha\PhraseBuilder;
use App\Validation\MyRules;

class MemberController extends BaseController
{
    private $memberService;

    public function __construct()
    {
        $this->memberService = service('MemberService');
    }

    public function login()
    {
        $view = $this->memberService->login();
        if (! $view['valid']){
            if ($this->request->getPost('returnurl')) {
                $this->session->setFlashdata('loginuserid', $this->request->getPost('mem_userid'));
                return redirect(urldecode($this->request->getPost('returnurl')));
            }
            return view('member/login', $view);
        } else {
            //if ($this->memberService->changePassword()) return redirect('mypage/password_modify');
            $urlAfterLogin = $this->request->getPost('url') ? urldecode($this->request->getPost('url')) : '/';
            return redirect($urlAfterLogin);
        }
    }

    public function agreement()
    {
        $view = $this->memberService->agreement();

        if (! $view['valid']){
            return view('member/agreement', $view);
        } else {
            return redirect('member/register');
        }
    }

    public function register()
    {

        $view = array();

        /*
        if ($this->member->is_member() && ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
            redirect();
        }
        */

        if ( /*$this->member->is_admin() === false &&*/ !$this->session->get('registeragree')) {
            $this->session->setFlashdata('message', '회원가입약관동의와 개인정보취급방침동의후 회원가입이 가능합니다');
            return redirect('member/agreement');
        }

        $configBasic = array();

        $configBasic['mem_userid'] = array(
            'rules' => 'required|alpha_dash|min_length[3]|max_length[20]|is_unique[member.mem_userid]|mem_userid_check',
            'errors' => [
                'required' => '필수입력',
                'alpha_dash' => '영문자, 숫자, _ 만 입력 가능',
                'min_length' => '최소 3자 이상',
                'max_length' => '최대 20자 이상',
                'is_unique' => '이미 존재하는 아이디',
                 ],
        );

        $password_length = config_item_db('password_length');
        $configBasic['mem_password'] = array(
            'rules' => 'required|min_length[' . $password_length . ']|mem_password_check',
            'errors' => [
                'required' => '필수입력',
                'min_length' => '비밀번호는 ' . $password_length . '자리 이상 ',
            ],
        );
        $configBasic['mem_password_re'] = array(
            'rules' => 'required|min_length[' . $password_length . ']|matches[mem_password]',
            'errors' => [
                'required' => '필수입력',
                'min_length' => '비밀번호는 ' . $password_length . '자리 이상 ',
                'matches' => '비밀번호가 일치하는지 확인합니다. ',
            ],
        );
        $configBasic['mem_username'] = array(
            'rules' => 'trim|min_length[2]|max_length[20]',
        );
        $configBasic['mem_nickname'] = array(
            'rules' => 'required|min_length[2]|max_length[20]|mem_nickname_check',
            'errors' => [
                'required' => '필수입력',
                'min_length' => '최소 2자 이상',
                'max_length' => '최대 20자 이하',
            ],
        );
        $configBasic['mem_email'] = array(
            'rules' => 'required|valid_email|max_length[50]|is_unique[member.mem_email]|mem_email_check',
        );
        $configBasic['mem_homepage'] = array(
            'rules' => 'prep_url|valid_url',
        );
        $configBasic['mem_birthday'] = array(
            'rules' => 'exact_length[10]',
        );
        $configBasic['mem_sex'] = array(
            'rules' => 'exact_length[1]',
        );
        $configBasic['mem_zipcode'] = array(
            'rules' => 'min_length[5]|max_length[7]',
        );
        $configbasic['mem_address1'] = array(
            'rules' => 'trim',
        );
        $configbasic['mem_address2'] = array(
            'rules' => 'trim',
        );
        $configbasic['mem_address3'] = array(
            'rules' => 'trim',
        );
        $configbasic['mem_address4'] = array(
            'rules' => 'trim',
        );
        $configbasic['mem_profile_content'] = array(
            'rules' => 'trim',
        );
        $configBasic['mem_open_profile'] = array(
            'rules' => 'exact_length[1]',
        );
        $configBasic['mem_use_note'] = array(
            'rules' => 'exact_length[1]',
        );
        $configBasic['mem_receive_email'] = array(
            'rules' => 'exact_length[1]',
        );
        $configBasic['mem_receive_sms'] = array(
            'rules' => 'exact_length[1]',
        );
        $configBasic['mem_recommend'] = array(
            'rules' => 'alpha_dash|min_length[3]|max_length[20]|mem_recommend_check',
        );
        $configBasic['mem_recommend'] = array(
            'rules' => 'alpha_dash|min_length[3]|max_length[20]',
        );

        $registerForm = config_item_db('registerform');
        $form = json_decode($registerForm, true);

        $config = $formErrors = $mem_photo_errors = $mem_icon_errors = array();
        if ($form && is_array($form)) {
            foreach ($form as $key => $value) {
                if ( ! val('use', $value)) {
                    continue;
                }
                if (val('func', $value) === 'basic') {

                    if ($key === 'mem_address') {
                        if (val('required', $value) === '1') {
                            $configBasic['mem_zipcode']['rules'] = $configBasic['mem_zipcode']['rules'] . '|required';
                        }
                        $config[$key] = $configBasic['mem_zipcode'];
                        if (val('required', $value) === '1') {
                            $configBasic['mem_address1']['rules'] = $configBasic['mem_address1']['rules'] . '|required';
                        }
                        $config[$key] = $configBasic['mem_address1'];
                        if (val('required', $value) === '1') {
                            $configBasic['mem_address2']['rules'] = $configBasic['mem_address2']['rules'] . '|required';
                        }
                        $config[$key] = $configbasic['mem_address2'];
                    } else {
                        if (val('required', $value) === '1') {
                            $configBasic[$value['field_name']]['rules'] = $configBasic[$value['field_name']]['rules'] . '|required';
                        }
                        if (val('field_type', $value) === 'phone') {
                            $configBasic[$value['field_name']]['rules'] = $configBasic[$value['field_name']]['rules'] . '|valid_phone';
                        }
                        $config[$key] = $configBasic[$value['field_name']];
                        if ($key === 'mem_password') {
                            $config[$key] = $configBasic['mem_password_re'];
                        }
                    }
                } else {
                    $required = val('required', $value) ? '|required' : '';
                    if (val('field_type', $value) === 'checkbox') {
                        $config[$key] = $configBasic[val('field_name', $value) . '[]'] = array(
                            'rules' => 'trim' . $required,
                        );
                    } else {
                        $config[$key] = $configBasic[val('field_name', $value)] = array(
                            'rules' => 'trim' . $required,
                        );
                    }
                }
            }

            $config['captcha_key'] = array(
                'rules' => 'required|valid_captcha',
            );
        }

        if (! $formValidation = $this->validate($config)) {
            $formErrors =  $this->validator->getErrors();
        }

        if ($formValidation) {
            if (config_item_db('use_member_photo') && config_item_db('member_photo_width') > 0 && config_item_db('member_photo_height') > 0) {
                $uploadImgs = array('mem_photo','mem_icon');
                foreach ($uploadImgs as $uploadImg) {
                    if (isset($_FILES) && isset($_FILES[$uploadImg]) && isset($_FILES[$uploadImg]['name']) && $_FILES[$uploadImg]['name'] && $img = $this->request->getFile($uploadImg)) {
                        $validationRule = [
                            $uploadImg => [
                                'rules' => 'uploaded['.$uploadImg.']'
                                    . '|is_image['.$uploadImg.']'
                                    . '|mime_in['.$uploadImg.',image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                                    . '|max_size['.$uploadImg.',2000]'
                                    . '|max_dims['.$uploadImg.',1000,1000]',
                            ],
                        ];
                        if (! $this->validate($validationRule)) {
                            ${$uploadImg.'_errors'} = $this->validator->getErrors();
                        } else {
                            if ($img->isValid() && ! $img->hasMoved()) {
                                ${$uploadImg.'_update'} = $newName = $img->getRandomName();
                                $img->move(WRITEPATH . 'uploads/'.$uploadImg, $newName);
                            }
                        }
                    }
                }
            }
        }

        if (! $formValidation || !empty($mem_photo_errors) || !empty($mem_icon_errors)) {

            $html_content = array();

            $k = 0;
            if ($form && is_array($form)) {
                foreach ($form as $key => $value) {
                    if ( ! val('use', $value)) {
                        continue;
                    }

                    $required = val('required', $value) ? 'required' : '';
                    $html_content[$k]['field_name'] = val('field_name', $value);
                    $html_content[$k]['display_name'] = val('display_name', $value);
                    $html_content[$k]['input'] = '';

                    //field_type : text, url, email, phone, textarea, radio, select, checkbox, date
                    if (val('field_type', $value) === 'text'
                        OR val('field_type', $value) === 'url'
                        OR val('field_type', $value) === 'email'
                        OR val('field_type', $value) === 'phone'
                        OR val('field_type', $value) === 'date') {
                        if (val('field_type', $value) === 'date') {
                            $html_content[$k]['input'] .= '<input type="text" id="' . val('field_name', $value) . '" name="' . val('field_name', $value) . '" class="form-control input datepicker" value="' . set_value(val('field_name', $value)) . '" readonly="readonly" ' . $required . ' />';
                        } elseif (val('field_type', $value) === 'phone') {
                            $html_content[$k]['input'] .= '<input type="text" id="' . val('field_name', $value) . '" name="' . val('field_name', $value) . '" class="form-control input validphone" value="' . set_value(val('field_name', $value)) . '" ' . $required . ' />';
                        } else {
                            $html_content[$k]['input'] .= '<input type="' . val('field_type', $value) . '" id="' . val('field_name', $value) . '" name="' . val('field_name', $value) . '" class="form-control input" value="' . set_value(val('field_name', $value)) . '" ' . $required . '/>';
                        }
                    } elseif (val('field_type', $value) === 'textarea') {
                        $html_content[$k]['input'] .= '<textarea id="' . val('field_name', $value) . '" name="' . val('field_name', $value) . '" class="form-control input" ' . $required . '>' . set_value(val('field_name', $value)) . '</textarea>';
                    } elseif (val('field_type', $value) === 'radio') {
                        $html_content[$k]['input'] .= '<div class="checkbox">';
                        if (val('field_name', $value) === 'mem_sex') {
                            $options = array(
                                '1' => '남성',
                                '2' => '여성',
                            );
                        } else {
                            $options = explode("\n", val('options', $value));
                        }
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $radiovalue = (val('field_name', $value) === 'mem_sex') ? $okey : $oval;
                                $html_content[$k]['input'] .= '<label for="' . val('field_name', $value) . '_' . $i . '"><input type="radio" name="' . val('field_name', $value) . '" id="' . val('field_name', $value) . '_' . $i . '" value="' . $radiovalue . '" ' . set_radio(val('field_name', $value), $radiovalue) . ' /> ' . $oval . ' </label> ';
                                $i++;
                            }
                        }
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (val('field_type', $value) === 'checkbox') {
                        $html_content[$k]['input'] .= '<div class="checkbox">';
                        $options = explode("\n", val('options', $value));
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $html_content[$k]['input'] .= '<label for="' . val('field_name', $value) . '_' . $i . '"><input type="checkbox" name="' . val('field_name', $value) . '[]" id="' . val('field_name', $value) . '_' . $i . '" value="' . $oval . '" ' . set_checkbox(val('field_name', $value), $oval) . ' /> ' . $oval . ' </label> ';
                                $i++;
                            }
                        }
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (val('field_type', $value) === 'select') {
                        $html_content[$k]['input'] .= '<div class="input-group">';
                        $html_content[$k]['input'] .= '<select name="' . val('field_name', $value) . '" class="form-control input" ' . $required . '>';
                        $html_content[$k]['input'] .= '<option value="" >선택하세요</option> ';
                        $options = explode("\n", val('options', $value));
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $html_content[$k]['input'] .= '<option value="' . $oval . '" ' . set_select(val('field_name', $value), $oval) . ' >' . $oval . '</option> ';
                            }
                        }
                        $html_content[$k]['input'] .= '</select>';
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (val('field_name', $value) === 'mem_address') {
                        $html_content[$k]['input'] .= '
							<label for="mem_zipcode">우편번호</label>
							<label>
								<input type="text" name="mem_zipcode" value="' . set_value('mem_zipcode') . '" id="mem_zipcode" class="form-control input" size="7" maxlength="7" ' . $required . '/>
							</label>
							<label>
								<button type="button" class="btn btn-black btn-sm" style="margin-top:0px;" onclick="win_zip(\'fregisterform\', \'mem_zipcode\', \'mem_address1\', \'mem_address2\', \'mem_address3\', \'mem_address4\');">주소 검색</button>
							</label>
							<div class="addr-line mt10">
								<label for="mem_address1">기본주소</label>
								<input type="text" name="mem_address1" value="' . set_value('mem_address1') . '" id="mem_address1" class="form-control input" placeholder="기본주소" ' . $required . ' />
							</div>
							<div class="addr-line mt10">
								<label for="mem_address2">상세주소</label>
								<input type="text" name="mem_address2" value="' . set_value('mem_address2') . '" id="mem_address2" class="form-control input" placeholder="상세주소" ' . $required . ' />
							</div>
							<div class="addr-line mt10">
								<label for="mem_address3">참고항목</label>
								<input type="text" name="mem_address3" value="' . set_value('mem_address3') . '" id="mem_address3" class="form-control input" readonly="readonly" placeholder="참고항목" />
							</div>
							<input type="hidden" name="mem_address4" value="' . set_value('mem_address4') . '" />
						';
                    } elseif (val('field_name', $value) === 'mem_password') {
                        $html_content[$k]['input'] .= '<input type="' . val('field_type', $value) . '" id="' . val('field_name', $value) . '" name="' . val('field_name', $value) . '" class="form-control input" minlength="' . $password_length . '" />';
                    }

                    if (val('field_name', $value) === 'mem_password') {
                        $k++;
                        $html_content[$k]['field_name'] = 'mem_password_re';
                        $html_content[$k]['display_name'] = '비밀번호 확인';
                        $html_content[$k]['input'] = '<input type="password" id="mem_password_re" name="mem_password_re" class="form-control input" minlength="' . $password_length . '" />';
                    }
                    $k++;
                }
            }

            $view['view']['html_content'] = $html_content;
            $view['view']['open_profile_description'] = '';
            if (config_item_db('change_open_profile_date')) {
                $view['view']['open_profile_description'] = '정보공개 설정은 ' . config_item_db('change_open_profile_date') . '일 이내에는 변경할 수 없습니다';
            }

            $view['view']['use_note_description'] = '';
            if (config_item_db('change_use_note_date')) {
                $view['view']['use_note_description'] = '쪽지 기능 사용 설정은 ' . config_item_db('change_use_note_date') . '일 이내에는 변경할 수 없습니다';
            }

            $view['view']['canonical'] = site_url('register/form');

            if ($this->request->getMethod() == 'post') {
                $view['errors'] = array_merge($formErrors,$mem_photo_errors,$mem_icon_errors);
            }
            return view('member/register', $view);

        } else {

            $mem_level = (int) config_item_db('register_level');
            $insertData = array();
            $metaData = array();

            $insertData['mem_userid'] = $this->request->getPost('mem_userid');
            $insertData['mem_email'] = $this->request->getPost('mem_email');
            $insertData['mem_password'] = password_hash($this->request->getPost('mem_password'), PASSWORD_BCRYPT);
            $insertData['mem_nickname'] = $this->request->getPost('mem_nickname');
            $metaData['meta_nickname_datetime'] = date('Y-m-d H:i:s');
            $insertData['mem_level'] = $mem_level;

            if (isset($form['mem_username']['use']) && $form['mem_username']['use']) {
                $insertData['mem_username'] = $this->request->getPost('mem_username');
            }
            if (isset($form['mem_homepage']['use']) && $form['mem_homepage']['use']) {
                $insertData['mem_homepage'] = $this->request->getPost('mem_homepage');
            }
            if (isset($form['mem_phone']['use']) && $form['mem_phone']['use']) {
                $insertData['mem_phone'] = $this->request->getPost('mem_phone');
            }
            if (isset($form['mem_birthday']['use']) && $form['mem_birthday']['use']) {
                $insertData['mem_birthday'] = $this->request->getPost('mem_birthday');
            }
            if (isset($form['mem_sex']['use']) && $form['mem_sex']['use']) {
                $insertData['mem_sex'] = $this->request->getPost('mem_sex');
            }
            if (isset($form['mem_address']['use']) && $form['mem_address']['use']) {
                $insertData['mem_zipcode'] = $this->request->getPost('mem_zipcode');
                $insertData['mem_address1'] = $this->request->getPost('mem_address1');
                $insertData['mem_address2'] = $this->request->getPost('mem_address2');
                $insertData['mem_address3'] = $this->request->getPost('mem_address3');
                $insertData['mem_address4'] = $this->request->getPost('mem_address4');
            }
            $insertData['mem_receive_email'] = $this->request->getPost('mem_receive_email') ? 1 : 0;
            if (config_item_db('use_note')) {
                $insertData['mem_use_note'] = $this->request->getPost('mem_use_note') ? 1 : 0;
                $metaData['meta_use_note_datetime'] = date('Y-m-d H:i:s');
            }
            $insertData['mem_receive_sms'] = $this->request->getPost('mem_receive_sms') ? 1 : 0;
            $insertData['mem_open_profile'] = $this->request->getPost('mem_open_profile') ? 1 : 0;
            $metaData['meta_open_profile_datetime'] = date('Y-m-d H:i:s');
            $insertData['mem_register_datetime'] = date('Y-m-d H:i:s');
            $insertData['mem_register_ip'] = $this->request->getIPAddress();
            $metaData['meta_change_pw_datetime'] = date('Y-m-d H:i:s');
            if (isset($form['mem_profile_content']['use']) && $form['mem_profile_content']['use']) {
                $insertData['mem_profile_content'] = $this->request->getPost('mem_profile_content');
            }

            if (config_item_db('use_register_email_auth')) {
                $insertData['mem_email_cert'] = 0;
                $metaData['meta_email_cert_datetime'] = '';
            } else {
                $insertData['mem_email_cert'] = 1;
                $metaData['meta_email_cert_datetime'] = date('Y-m-d H:i:s');
            }


            if (isset($mem_photo_update)) {
                $insertData['mem_photo'] = $mem_photo_update;
            }
            if (isset($mem_icon_update)) {
                $insertData['mem_icon'] = $mem_icon_update;
            }

            $MemberModel = model('MemberModel');
            $MemberModel->insert($insertData);
            $mem_id = $MemberModel->getInsertID();

            $memGroupDefault = model('MemberGroupModel')->where('mgr_is_default', 1)->first();
            $gminsert = array(
                'mgr_id' => $memGroupDefault['mgr_id'],
                'mem_id' => $mem_id,
                'mgm_datetime' => date('Y-m-d H:i:s'),
            );
            model('MemberGroupMemberModel')->insert($gminsert);

            $this->session->setFlashdata('nickname', $this->request->getPost('mem_nickname'));
            $this->session->set('mem_id', $mem_id);

            return redirect('member/result');
        }

    }

    public function result() {
        if ( ! $this->session->get('nickname')) {
            redirect();
        }
        return view('member/result');
    }

    public function logout() {
        $this->session->destroy();
        $urlAfterLogout = $this->request->getPost('url') ? $this->request->getPost('url') : '';

        return redirect()->to(site_url($urlAfterLogout));
    }

    public function ajaxEmailCheck()
    {
        $email = trim($this->request->getPost('email'));
        if (empty($email)) {
            $result = array(
                'result' => 'no',
                'reason' => '이메일값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        $where = array(
            'mem_email' => $email,
        );
        $count = model('MemberModel')->where($where)->countAllResults();
        if ($count > 0) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 이메일입니다',
            );
            exit(json_encode($result));
        }
        $myRules = new MyRules();
        if ($myRules->mem_email_check($email) === false) {
            $result = array(
                'result' => 'no',
                'reason' => $email . '은(는) 예약어로 사용하실 수 없는 이메일입니다',
            );
            exit(json_encode($result));
        }

        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 이메일입니다',
        );
        exit(json_encode($result));
    }


    public function ajaxPasswordCheck()
    {
        $password = trim($this->request->getPost('password'));
        if (empty($password)) {
            $result = array(
                'result' => 'no',
                'reason' => '패스워드값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        $myRules = new MyRules();
        if ($myRules->mem_password_check($password) === false) {
            $result = array(
                'result' => 'no',
                'reason' => '패스워드는 최소 1개 이상의 숫자를 포함해야 합니다',
            );
            exit(json_encode($result));
        }

        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 패스워드입니다',
        );
        exit(json_encode($result));
    }


    public function ajaxNicknameCheck()
    {
        $nickname = trim($this->request->getPost('nickname'));
        if (empty($nickname)) {
            $result = array(
                'result' => 'no',
                'reason' => '닉네임값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        $where = array(
            'mem_nickname' => $nickname,
        );
        $count = model('MemberModel')->where($where)->countAllResults();
        if ($count > 0) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 닉네임입니다',
            );
            exit(json_encode($result));
        }

        $myRules = new MyRules();
        if ($myRules->mem_nickname_check($nickname) === false) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 닉네임입니다',
            );
            exit(json_encode($result));
        }

        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 닉네임입니다',
        );
        exit(json_encode($result));
    }

    public function ajaxUseridCheck()
    {
        $userid = trim($this->request->getPost('userid'));
        if (empty($userid)) {
            $result = array(
                'result' => 'no',
                'reason' => '아이디값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        if ( ! preg_match("/^([a-z0-9_])+$/i", $userid)) {
            $result = array(
                'result' => 'no',
                'reason' => '아이디는 숫자, 알파벳, _ 만 입력가능합니다',
            );
            exit(json_encode($result));
        }

        $where = array(
            'mem_userid' => $userid,
        );
        $count = model('MemberModel')->where($where)->countAllResults();
        if ($count > 0) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 아이디입니다',
            );
            exit(json_encode($result));
        }

        $myRules = new MyRules();
        if ($myRules->mem_userid_check($userid) === false) {
            $result = array(
                'result' => 'no',
                'reason' => $userid . '은(는) 예약어로 사용하실 수 없는 회원아이디입니다',
            );
            exit(json_encode($result));
        }

        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 아이디입니다',
        );
        exit(json_encode($result));
    }


}
