<?php

namespace App\Validation;

use Gregwar\Captcha\PhraseBuilder;

class MyRules
{
    public function korean_alpha_dash($str)
    {
        return ( preg_match('/[^\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}0-9a-zA-Z_-]/u',$str)) ? false : true;
    }

    public function valid_phone($value)
    {
        $value = trim($value);
        if ($value === '') {
            return true;
        } else {
            if (preg_match('/^\(?[0-9]{2,3}\)?[-. ]?[0-9]{3,4}[-. ]?[0-9]{4}$/', $value)) {
                return preg_replace('/^\(?([0-9]{2,3})\)?[-. ]?([0-9]{3,4})[-. ]?([0-9]{4})$/', '$1-$2-$3', $value);
            } else {
                return false;
            }
        }
    }

    public function prep_url($str = '')
    {
        if ($str === 'http://' OR $str === '')
        {
            return '';
        }

        if (strpos($str, 'http://') !== 0 && strpos($str, 'https://') !== 0)
        {
            return 'http://'.$str;
        }

        return $str;
    }

    public function valid_captcha(string $str, ?string &$error = null): bool {
        $session = session();
        $validCaptcha = true;
        if (! PhraseBuilder::comparePhrases($session->get('captcha'), $str)) {
            $validCaptcha = false;
            $error = '올바른 캡챠를 입력해주세요!';
        }
        $session->remove('captcha');
        return $validCaptcha;
    }

    public function mem_userid_check(string $str, ?string &$error = null): bool {
        if (preg_match("/[\,]?{$str}/i", config_item_db('denied_userid_list'))) {
            $error = $str . ' 은(는) 예약어로 사용하실 수 없는 회원아이디입니다';
            return false;
        }

        return true;
    }

    public function mem_email_check(string $str, ?string &$error = null): bool {
        list($emailid, $emaildomain) = explode('@', $str);
        $denied_list = explode(',', config_item_db('denied_email_list'));
        $emaildomain = trim($emaildomain);
        $denied_list = array_map('trim', $denied_list);
        if (in_array($emaildomain, $denied_list)) {
            $error = $emaildomain . ' 은(는) 사용하실 수 없는 이메일입니다';
            return false;
        }

        return true;
    }

    public function mem_recommend_check(string $str, ?string &$error = null): bool {
        if( ! $str) {
            return true;
        }
        $countwhere = array(
            'mem_userid' => $str,
            'mem_denied' => 0,
        );
        $row = model('MemberModel')->where($countwhere)->countAllResults();
        if ($row === 0) {
            $error = $str . ' 는 존재하지 않는 추천인 회원아이디입니다';
            return false;
        }

        return true;
    }

    public function mem_password_check(string $str, ?string &$error = null): bool {
        $uppercase = config_item_db('password_uppercase_length');
        $number = config_item_db('password_numbers_length');
        $specialchar = config_item_db('password_specialchars_length');

        helper('chkstring');
        $str_uc = count_uppercase($str);
        $str_num = count_numbers($str);
        $str_spc = count_specialchars($str);

        if ($str_uc < $uppercase OR $str_num < $number OR $str_spc < $specialchar) {

            $description = '비밀번호는 ';
            if ($str_uc < $uppercase) {
                $description .= ' ' . $uppercase . '개 이상의 대문자';
            }
            if ($str_num < $number) {
                $description .= ' ' . $number . '개 이상의 숫자';
            }
            if ($str_spc < $specialchar) {
                $description .= ' ' . $specialchar . '개 이상의 특수문자';
            }
            $description .= '를 포함해야 합니다';

            $error = $description;
            return false;

        }

        return true;
    }

    public function mem_nickname_check(string $str, ?string &$error = null): bool {
        helper('chkstring');
        if (chkstring($str, _HANGUL_ + _ALPHABETIC_ + _NUMERIC_) === false) {
            $error = '닉네임은 공백없이 한글, 영문, 숫자만 입력 가능합니다';
            return false;
        }

        if (preg_match("/[\,]?{$str}/i", config_item_db('denied_nickname_list'))) {
            $error = $str . ' 은(는) 예약어로 사용하실 수 없는 닉네임입니다';
            return false;
        }
        $countwhere = array(
            'mem_nickname' => $str,
        );
        $row = model('MemberModel')->where($countwhere)->countAllResults();

        if ($row > 0) {
            $error = $str . ' 는 이미 다른 회원이 사용하고 있는 닉네임입니다';
            return false;
        }

        return true;
    }

    public function check_id_pw(string $password, ?string &$error = null): bool {
        $request = service('request');
        $memberService= service('MemberService');
        $userid = $request->getPost('mem_userid');
        $max_login_try_count = (int) config_item_db('max_login_try_count');
        $max_login_try_limit_second = (int) config_item_db('max_login_try_limit_second');

        $loginfailnum = 0;
        $loginfailmessage = '';
        if ($max_login_try_count && $max_login_try_limit_second) {
            $where = array(
                'mll_ip' => $request->getIPAddress(),
                'mll_datetime > ' => strtotime(time() - 86400 * 30),
            );
            $logindata = model('MemberLoginLogModel')->where($where)->orderBy('mll_id', 'DESC')->findAll();
            if ($logindata && is_array($logindata)) {
                foreach ($logindata as $key => $val) {
                    if ((int) $val['mll_success'] === 0) {
                        $loginfailnum++;
                    } elseif ((int) $val['mll_success'] === 1) {
                        break;
                    }
                }
            }
            if ($loginfailnum > 0 && $loginfailnum % $max_login_try_count === 0) {
                $lastlogintrydatetime = $logindata[0]['mll_datetime'];
                $next_login = strtotime($lastlogintrydatetime)
                    + $max_login_try_limit_second
                    - time();
                if ($next_login > 0) {
                    $error = '회원님은 패스워드를 연속으로 ' . $loginfailnum . '회 잘못 입력하셨기 때문에 '
                        . $next_login . '초 후에 다시 로그인 시도가 가능합니다';
                    return false;
                }
            }
            $loginfailmessage = '<br />회원님은 ' . ($loginfailnum + 1)
                . '회 연속으로 패스워드를 잘못입력하셨습니다. ';
        }

        $userinfo = model('MemberModel')->getByUserid($userid);
        if ( ! val('mem_id', $userinfo) OR ! val('mem_password', $userinfo)) {
            $error = '회원 아이디와 패스워드가 서로 맞지 않습니다' . $loginfailmessage;
            $memberService->updateLoginLog(0, $userid, 0, '회원 아이디가 존재하지 않습니다');
            return false;
        } elseif ( ! password_verify($password, val('mem_password', $userinfo))) {
            $error = '회원 아이디와 패스워드가 서로 맞지 않습니다' . $loginfailmessage;
            $memberService->updateLoginLog(val('mem_id', $userinfo), $userid, 0, '패스워드가 올바르지 않습니다');
            return false;
        } elseif (val('mem_denied', $userinfo)) {
            $error = '회원님의 아이디는 접근이 금지된 아이디입니다';
            $memberService->updateLoginLog(val('mem_id', $userinfo), $userid, 0, '접근이 금지된 아이디입니다');
            return false;
        } elseif (val('mem_is_admin', $userinfo) && $request->getPost('autologin')) {
            $error = '최고관리자는 자동로그인 기능을 사용할 수 없습니다';
            return false;
        }

        return true;

    }

}