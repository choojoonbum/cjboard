<?php

namespace App\Controllers;

class MemberController extends BaseController
{
    public function index()
    {
        return view('home');
    }

    public function register()
    {
        $this->session->set('registeragree', '');

        $view['view']['member_register_policy1'] = config_item_db('member_register_policy1');
        $view['view']['member_register_policy2'] = config_item_db('member_register_policy2');
        $view['view']['canonical'] = site_url('register/form');

        return view('member/register',$view);
    }
}
