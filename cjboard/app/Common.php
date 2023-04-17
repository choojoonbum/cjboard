<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

use Config\Config;


if ( ! function_exists('config_item'))
{
    function config_item($item)
    {
        $config = (array) config(Config::class);
        return isset($config[$item]) ? $config[$item] : NULL;
    }
}

if ( ! function_exists('config_item_db'))
{
    function config_item_db($item)
    {
        if (! $config = cache($item)) {
            $config = model('ConfigModel')->find([$item]);
            cache()->save($item, $config, 86400);
        }
        return isset($config[0]['cfg_value']) ? $config[0]['cfg_value'] : NULL;
    }
}