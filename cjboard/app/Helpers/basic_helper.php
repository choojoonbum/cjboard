<?php

if ( ! function_exists('debug')) {
    function debug($var,$exit = false)
    {
        ob_start();
        print_r($var);
        $str = ob_get_contents();
        ob_end_clean();
        $str = str_replace(" ", "&nbsp;", $str);
        echo nl2br("<span style='font-family:Tahoma, 굴림; font-size:9pt;'>$str</span>");
        if ($exit) exit();
    }
}

if ( ! function_exists('val'))
{
    function val($item, $array, $default = NULL)
    {
        if (!is_array($array)) return $default;
        return array_deep_search($item, $array) ? $array[$item] : $default;
    }
}

if ( ! function_exists('btAlert')) {
    function btAlert($message = '')
    {
        if (empty($message)) {
            return false;
        }
        return "<div class=\"alert fade in alert-danger alert-auto-close\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>".$message."</div>";

    }
}

if ( ! function_exists('checkCacheDir')) {
    function checkCacheDir($dir)
    {
        $cachePath = WRITEPATH . 'cache/'. $dir;

        if ( ! is_dir($cachePath) OR ! is_really_writable($cachePath))
        {
            if (mkdir($cachePath , 0755)) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
}

if ( ! function_exists('check_use_captcha'))
{
    function check_use_captcha($board=array(), $mode=''){

        $use_captcha = false;
        return $use_captcha;
        /*
        if( $member->is_admin() !== false ){	//관리자면 캡챠 사용 안함
            return false;
        }

        if( $member->is_member() === false ){	//비회원은 캡챠 사용
            $use_captcha = true;
        }

        if( element('board_use_captcha', $board) && $mode !== 'cu' ){ //글쓰기일때만
            $use_captcha = true;
        }

        return $use_captcha;
        */
    }
}

/**
 * 게시물 열람 페이지 주소를 return 합니다
 */
if ( ! function_exists('post_url')) {
    function post_url($key = '', $post_id = '')
    {
        $key = trim($key, '/');
        $post_id = trim($post_id, '/');

        $post_url = '';
        if (strtoupper(config_item('uriSegmentPostType')) === 'B') {
            $post_url = site_url($key . '/' . config_item('uriSegmentPost') . '/' . $post_id);
        } elseif (strtoupper(config_item('uriSegmentPostType')) === 'C') {
            $post_url = site_url(config_item('uriSegmentPost') . '/' . $key . '/' . $post_id);
        } else {
            $post_url = site_url(config_item('uriSegmentPost') . '/' . $post_id);
        }
        return $post_url;
    }
}

if ( ! function_exists('alert')) {
    function alert($msg = '', $url = '')
    {
        if (empty($msg)) {
            $msg = '잘못된 접근입니다';
        }
        echo '<meta http-equiv="content-type" content="text/html; charset=' . config_item('charset') . '">';
        echo '<script type="text/javascript">alert("' . $msg . '");';
        if (empty($url)) {
            echo 'history.go(-1);';
        }
        if ($url) {
            echo 'document.location.href="' . $url . '"';
        }
        echo '</script>';
        exit;
    }
}

/**
 * 게시판 목록 주소를 return 합니다
 */
if ( ! function_exists('board_url')) {
    function board_url($key = '')
    {
        $key = trim($key, '/');
        return site_url(config_item('uriSegmentBoard') . '/' . $key);
    }
}

if ( ! function_exists('cut_str')) {
    function cut_str($str = '', $len = '', $suffix = '…')
    {
        $arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
        $str_len = count($arr_str);

        if ($str_len >= $len) {
            $slice_str = array_slice($arr_str, 0, $len);
            $str = join('', $slice_str);
            return $str . ($str_len > $len ? $suffix : '');
        } else {
            $str = join('', $arr_str);
            return $str;
        }
    }
}

/**
 * 회원닉네임을 사이드뷰와 함께 출력
 */
if ( ! function_exists('display_username')) {
    function display_username($userid = '', $name = '', $icon = '', $use_sideview = '')
    {
        $name = $name ? esc($name) : '비회원';
        $title = $userid ? '[' . $userid . ']' : '[비회원]';

        $_use_sideview = config_item_db('use_sideview');

        $result = '';
        if ($use_sideview) {
            if ($use_sideview === 'Y' && $userid) {
                $result .= '<a href="javascript:;"
					onClick="getSideView(this, \'' . $userid . '\');"
					title="' . $title . $name . '" style="text-decoration:none;">';
            }
        } elseif ($_use_sideview && $userid) {
            $result .= '<a href="javascript:;"
				onClick="getSideView(this, \'' . $userid . '\');"
				title="' . $title . $name . '" style="text-decoration:none;">';
        }
        if (config_item_db('use_member_icon') && $icon) {
            $width = config_item_db('member_icon_width');
            $height = config_item_db('member_icon_height');
            $result .= '<img src="'
                . member_icon_url($icon) . '" alt="icon" class="member-icon"
				width="' . $width . '" height="' . $height . '" /> ';
        }

        $result .= $name;

        if ($use_sideview) {
            if ($use_sideview === 'Y' && $userid) {
                $result .= '</a>';
            }
        } elseif ($_use_sideview && $userid) {
            $result .= '</a>';
        }

        return $result;
    }
}

if ( ! function_exists('member_icon_url')) {
    function member_icon_url($img = '', $width = '', $height = '')
    {
        if (empty($img)) {
            return false;
        }
        is_numeric($width) OR $width = config_item_db('member_icon_width');
        is_numeric($height) OR $height = config_item_db('member_icon_height');

        return thumb_url('member_icon', $img, $width, $height);
    }
}

/**
 * 날짜 표시하기
 */
if ( ! function_exists('display_datetime')) {
    function display_datetime($datetime = '', $type = '', $custom = '')
    {
        if (empty($datetime)) {
            return false;
        }

        if ($type === 'sns') {

            $diff = time() - strtotime($datetime);

            $s = 60; //1분 = 60초
            $h = $s * 60; //1시간 = 60분
            $d = $h * 24; //1일 = 24시간
            $y = $d * 10; //1년 = 1일 * 10일

            if ($diff < $s) {
                $result = $diff . '초전';
            } elseif ($h > $diff && $diff >= $s) {
                $result = round($diff/$s) . '분전';
            } elseif ($d > $diff && $diff >= $h) {
                $result = round($diff/$h) . '시간전';
            } elseif ($y > $diff && $diff >= $d) {
                $result = round($diff/$d) . '일전';
            } else {
                if (substr($datetime,0, 10) === date('Y-m-d')) {
                    $result = str_replace('-', '.', substr($datetime,11,5));
                } else {
                    $result = substr($datetime, 5, 5);
                }
            }
        } elseif ($type === 'user' && $custom) {
            return date($custom, strtotime($datetime));
        } elseif ($type === 'full') {
            if (substr($datetime,0, 10) === date('Y-m-d')) {
                $result = substr($datetime,11,5);
            } elseif (substr($datetime,0, 4) === date('Y')) {
                $result = substr($datetime,5,11);
            } else {
                $result = substr($datetime,0,10);
            }
        } else {
            if (substr($datetime,0, 10) === date('Y-m-d')) {
                $result = substr($datetime,11,5);
            } else {
                $result = substr($datetime,5,5);
            }
        }

        return $result;
    }
}