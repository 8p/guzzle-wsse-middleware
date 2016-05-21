<?php

namespace EightPoints\Guzzle\Util;

class GuidGenerator
{
    public static function generate()
    {
        if (function_exists('com_create_guid')) {

            return com_create_guid();
        } else {

            mt_srand((double)microtime() * 10000);

            $charid = strtoupper(md5(uniqid(mt_rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8) . $hyphen
                .substr($charid, 8, 4) . $hyphen
                .substr($charid, 12, 4) . $hyphen
                .substr($charid, 16, 4) . $hyphen
                .substr($charid, 20, 12)
                .chr(125);// "}"

            return $uuid;
        }
    }
}
