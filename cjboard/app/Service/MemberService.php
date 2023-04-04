<?php
namespace App\Service;

class MemberService {

    public function __construct() {

    }

    public function accessCheck() {
        if ($this->member->is_member()
            && ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uriSegmentAdmin'))) {
            redirect();
        }
    }

}