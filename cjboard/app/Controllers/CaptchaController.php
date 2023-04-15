<?php

namespace App\Controllers;

class CaptchaController extends BaseController
{
    public function index()
    {
        service('MemberService')->captcha();
    }

    public function getCaptcha() {
        exit(json_encode(['word' => $this->session->get('captcha')]));
    }
}
