<?php

if ( ! function_exists('debug')) {
    function debug($var)
    {
        ob_start();
        print_r($var);
        $str = ob_get_contents();
        ob_end_clean();
        $str = str_replace(" ", "&nbsp;", $str);
        echo nl2br("<span style='font-family:Tahoma, 굴림; font-size:9pt;'>$str</span>");
    }
}

if ( ! function_exists('element'))
{
    function element($item, array $array, $default = NULL)
    {
        return array_deep_search($item, $array) ? $array[$item] : $default;
    }
}