<?php namespace App\Services\Utility;

class StringUtility {

    public static function generateString($length = 4)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return self::generate($chars, $length);
    }

    public static function generateNumber($length = 4)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = "0123456789";
        return self::generate($chars, $length);
    }

    protected static function generate($chars, $length)
    {
        $str = "";
        for($i = 0; $i < $length; $i++)
        {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }






}

