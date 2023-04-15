<?php
namespace App\Service;

use Gregwar\Captcha\CaptchaBuilder;

class MemberService {

    private $configBasic = [];
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
        if ($this->item('mem_isAdmin')) {
            return 'super';
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
            if (empty($this->mb)) {
                $member = $this->CI->Member_model->get_by_memid($this->isMember());
                $extras = $this->get_all_extras(element('mem_id', $member));
                if (is_array($extras)) {
                    $member = array_merge($member, $extras);
                }
                $metas = $this->get_all_meta(element('mem_id', $member));
                if (is_array($metas)) {
                    $member = array_merge($member, $metas);
                }
                $member['social'] = $this->get_all_social_meta(element('mem_id', $member));
                $this->mb = $member;
            }
            return $this->mb;
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

}