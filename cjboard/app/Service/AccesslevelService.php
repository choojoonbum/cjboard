<?php
namespace App\Service;

class AccesslevelService {

    private $memberService;

    public function __construct()
    {
        $this->memberService = service('memberService');
    }

    /**
     * 접근권한이 있는지를 판단합니다
     */
    public function isAccessable($access_type = '', $level = '', $group = '', $check = array())
    {
        $access_type = (string) $access_type;
        if (empty($access_type)) { // 모든 사용자
            return true;
        } elseif ($access_type === '1') { // 로그인 사용자
            if ($this->memberService->isMember() === false) {
                return false;
            }
            return true;
        } elseif ($access_type === '100') { // 관리자
            if ($this->memberService->isAdmin($check) === false) {
                return false;
            }
            return true;
        } elseif ($access_type === '2') { // 특정그룹사용자
            if ($this->memberService->isAdmin($check) !== false) {
                return true;
            }
            if ($this->memberService->isMember() === false) {
                return false;
            }
            $mygroup = $this->memberService->group();
            $groups = json_decode($group, true);
            $_flag = false;
            if ($mygroup && is_array($mygroup)) {
                foreach ($mygroup as $key => $value) {
                    if (is_array($groups) && in_array(val('mgr_id', $value), $groups)) {
                        $_flag = true;
                        break;
                    }
                }
            }
            if ($_flag === false) {
                return false;
            }
            return true;
        } elseif ($access_type === '3') { // 특정레벨이상인자
            if ($this->memberService->isAdmin($check) !== false) {
                return true;
            }
            if ($this->memberService->isMember() === false) {
                return false;
            }
            if ($this->memberService->item('mem_level') < $level) {
                return false;
            }
            return true;

        } elseif ($access_type === '4') { // 특정그룹 OR 특정레벨
            if ($this->memberService->isAdmin($check) !== false) {
                return true;
            }
            if ($this->memberService->isMember() === false) {
                return false;
            }
            $_flag = false;
            if ($this->memberService->item('mem_level') >= $level) {
                $_flag = true;
            }
            if ($_flag === false) {
                $mygroup = $this->memberService->group();
                $groups = json_decode($group, true);
                if ($mygroup && is_array($mygroup)) {
                    foreach ($mygroup as $key => $value) {
                        if (is_array($groups) && in_array(val('mgr_id', $value), $groups)) {
                            $_flag = true;
                            break;
                        }
                    }
                }
            }
            if ($_flag === false) {
                return false;
            }
            return true;

        } elseif ($access_type === '5') { // 특정그룹 AND 특정레벨
            if ($this->memberService->isAdmin($check) !== false) {
                return true;
            }
            if ($this->memberService->isMember() === false) {
                return false;
            }
            if ($this->memberService->item('mem_level') < $level) {
                return false;
            }
            $_flag = false;
            $mygroup = $this->memberService->group();
            $groups = json_decode($group, true);
            if ($mygroup && is_array($mygroup)) {
                foreach ($mygroup as $key => $value) {
                    if (is_array($groups) && in_array(val('mgr_id', $value), $groups)) {
                        $_flag = true;
                        break;
                    }
                }
            }
            if ($_flag === false) {
                return false;
            }
            return true;
        }
    }


    /**
     * 접근권한이 없으면 alert 를 띄웁니다
     */
    public function check($access_type = '', $level = '', $group = '', $alertmessage = '', $check = array())
    {
        if (empty($alertmessage)) {
            $alertmessage = '접근 권한이 없습니다';
        }
        $accessable = $this->isAccessable($access_type, $level, $group, $check);

        if ($accessable) {
            return true;
        } else {
            alert($alertmessage);
            return false;
        }
    }

}
