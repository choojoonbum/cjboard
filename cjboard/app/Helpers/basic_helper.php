<?php

/**
 * Baisc helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

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

        if( service('memberService')->isAdmin() !== false ){	//관리자면 캡챠 사용 안함
            return false;
        }

        if( service('memberService')->isMember() === false ){	//비회원은 캡챠 사용
            $use_captcha = true;
        }

        if( val('board_use_captcha', $board) && $mode !== 'cu' ){ //글쓰기일때만
            $use_captcha = true;
        }

        return $use_captcha;

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

        $post_url = site_url(service('uri')->getSegment(1).'/'.config_item('uriSegmentPost') . '/' . $post_id);

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

    if ( ! function_exists('display_ipaddress')) {
        function display_ipaddress($ip = '', $type = '0001')
        {
            $len = strlen($type);
            if ($len !== 4) {
                return false;
            }
            if (empty($ip)) {
                return false;
            }

            $regex = '';
            $regex .= ($type[0] === '1') ? '\\1' : '&#9825;';
            $regex .= '.';
            $regex .= ($type[1] === '1') ? '\\2' : '&#9825;';
            $regex .= '.';
            $regex .= ($type[2] === '1') ? '\\3' : '&#9825;';
            $regex .= '.';
            $regex .= ($type[3] === '1') ? '\\4' : '&#9825;';

            return preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", $regex, $ip);
        }
    }
    if ( ! function_exists('display_html_content')) {
        function display_html_content($content = '', $html = '', $thumb_width=700, $autolink = false, $popup = false, $writer_is_admin = false)
        {
            $phpversion = phpversion();
            if (empty($html)) {
                $content = nl2br(esc($content));
                if ($autolink) {
                    $content = url_auto_link($content, $popup);
                }
                $content = preg_replace(
                    "/\[<a\s*href\=\"(http|https|ftp)\:\/\/([^[:space:]]+)\.(gif|png|jpg|jpeg|bmp).*<\/a>(\s\]|\]|)/i",
                    "<img src=\"$1://$2.$3\" alt=\"\" style=\"max-width:100%;border:0;\">",
                    $content
                );
                if (version_compare($phpversion, '7.2.0') >= 0) {

                } else {
                    $content = preg_replace_callback(
                        "/{지도\:([^}]*)}/is",
                        create_function('$match', '
						 global $thumb_width;
						 return get_google_map($match[1], $thumb_width);
					'),
                        $content
                    ); // Google Map
                }


                return $content;
            }

            $source = array();
            $target = array();

            $source[] = '//';
            $target[] = '';

            $source[] = "/<\?xml:namespace prefix = o ns = \"urn:schemas-microsoft-com:office:office\" \/>/";
            $target[] = '';

            // 테이블 태그의 갯수를 세어 테이블이 깨지지 않도록 한다.
            $table_begin_count = substr_count(strtolower($content), '<table');
            $table_end_count = substr_count(strtolower($content), '</table');
            for ($i = $table_end_count; $i < $table_begin_count; $i++) {
                $content .= '</table>';
            }

            $content = preg_replace($source, $target, $content);

            if ($autolink) {
                $content = url_auto_link($content, $popup);
            }

            //if ($writer_is_admin === false) {
            $content = html_purifier($content);
            //}

            $content = get_view_thumbnail($content, $thumb_width);

            if (version_compare($phpversion, '7.2.0') >= 0) {

            } else {
                $content = preg_replace_callback(
                    "/{&#51648;&#46020;\:([^}]*)}/is",
                    create_function('$match', '
					 global $thumb_width;
					 return get_google_map($match[1], $thumb_width);
				'),
                    $content
                ); // Google Map
            }

            return $content;
        }
    }
    /**
     * syntax highlight
     */
    if ( ! function_exists('content_syntaxhighlighter')) {
        function content_syntaxhighlighter($m)
        {
            $str = $m[3];

            if (empty($str)) {
                return;
            }

            $str = str_replace(
                array("<br>", "<br/>", "<br />", "<div>", "</div>", "<p>", "</p>", "&nbsp;"),
                "",
                $str
            );
            $target = array("/</", "/>/", "/\"/", "/\'/");
            $source = array("&lt;", "&gt;", "&#034;", "&#039;");

            $str = preg_replace($target, $source, $str);

            if (empty($str)) {
                return;
            }

            $brush = strtolower(trim($m[2]));
            $brush_arr = array('css', 'js', 'jscript', 'javascript', 'php', 'xml', 'xhtml', 'xslt', 'html');
            $brush = ($brush && in_array($brush, $brush_arr)) ? $brush : 'html';

            return '<pre class="brush: ' . $brush . ';">' . $str . '</pre>' . PHP_EOL;
        }
    }


    /**
     * syntax highlight
     */
    if ( ! function_exists('content_syntaxhighlighter_html')) {
        function content_syntaxhighlighter_html($m)
        {
            $str = $m[3];

            if (empty($str)) {
                return;
            }

            $str = str_replace(
                array("\n\r", "\r"),
                array("\n"),
                $str
            );
            $str = str_replace("\n", "", $str);
            $str = str_replace(
                array("<br>", "<br/>", "<br />", "<div>", "</div>", "<p>", "</p>", "&nbsp;"),
                array("\n", "\n", "\n", "\n", "", "\n", "", "\t"),
                $str
            );
            $target = array("/<span[^>]+>/i", "/<\/span>/i", "/</", "/>/", "/\"/", "/\'/");
            $source = array("", "", "&lt;", "&gt;", "&#034;", "&#039;");

            $str = preg_replace($target, $source, $str);

            if (empty($str)) {
                return;
            }

            $brush = strtolower(trim($m[2]));
            $brush_arr = array('css', 'js', 'jscript', 'javascript', 'php', 'xml', 'xhtml', 'xslt', 'html');
            $brush = ($brush && in_array($brush, $brush_arr)) ? $brush : 'html';

            return '<pre class="brush: ' . $brush . ';">' . $str . '</pre>' . PHP_EOL;
        }
    }
    /**
     * URL 자동 링크 생성
     */
    if ( ! function_exists('url_auto_link')) {
        function url_auto_link($str = '', $popup = false)
        {
            if (empty($str)) {
                return false;
            }
            $target = $popup ? 'target="_blank"' : '';
            $str = str_replace(
                array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"),
                array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"),
                $str
            );
            $str = preg_replace(
                "/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i",
                "\\1<a href=\"\\2\" {$target}>\\2</A>",
                $str
            );
            $str = preg_replace(
                "/(^|[\"'\s(])(www\.[^\"'\s()]+)/i",
                "\\1<a href=\"http://\\2\" {$target}>\\2</A>",
                $str
            );
            $str = preg_replace(
                "/[0-9a-z_-]+@[a-z0-9._-]{4,}/i",
                "<a href=\"mailto:\\0\">\\0</a>",
                $str
            );
            $str = str_replace(
                array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"),
                array("&nbsp;", "&lt;", "&gt;", "&#039;"),
                $str
            );
            return $str;
        }
    }
    /**
     * 게시물 작성 페이지 주소를 return 합니다
     */
    if ( ! function_exists('write_url')) {
        function write_url($key = '')
        {
            $key = trim($key, '/');
            return site_url(service('uri')->getSegment(1).'/'.config_item('uriSegmentWrite') . '/' . $key);
        }
    }


    /**
     * 게시물 답변 페이지 주소를 return 합니다
     */
    if ( ! function_exists('reply_url')) {
        function reply_url($key = '')
        {
            $key = trim($key, '/');
            return site_url(service('uri')->getSegment(1).'/'.config_item('uriSegmentReply') . '/' . $key);
        }
    }


    /**
     * 게시물 수정 페이지 주소를 return 합니다
     */
    if ( ! function_exists('modify_url')) {
        function modify_url($key = '')
        {
            $key = trim($key, '/');
            return site_url(service('uri')->getSegment(1).'/'.config_item('uriSegmentModify') . '/' . $key);
        }
    }

}