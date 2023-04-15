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

if ( ! function_exists('val'))
{
    function val($item, array $array, $default = NULL)
    {
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