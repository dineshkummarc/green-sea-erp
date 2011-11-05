<?php
/**
 * 中文字符操作类
 * 用来进行编码转换及计算字符串长度
 * @author Yuanchensi
 *
 */
class Chinese
{
    /**
     * 字符编码转换
     * @param string $from 来源字符编码
     * @param string $to 欲转换的字符编码
     * @param string $str 欲转换的字符串
     */
    public static function convert($from = null, $to, $str)
    {
        if (!function_exists('iconv'))
        {
            return false;
        }

        if ($str == '' || preg_match("/[\x80-\xFF]+/", $str) == 0)
        {
            return $str;
        }

        if ($from == $to) return $str;

        $str = @iconv($from, $to."//IGNORE", $str);
        return $str;

    }

    /**
     * 获取字符串编码
     * @param string $str
     */
    public static function charset($str)
    {
        $result = preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E] # ASCII
            | [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
            | \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
            | \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
            | \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
            )*$%xs', $str);
        if ($result === true)
            return 'utf-8';
        return 'ansi';
    }

    /**
     * 计算字符串长度
     * @param string $str
     */
    public static function length($str)
    {
        $from = self::charset($str);
        if ($from == 'utf-8')
        {
            return self::utf8Length($str);
        }
        else if ($from == 'anis')
        {
            return self::gbkLength($str);
        }
    }

    /**
     * 计算GBK编码的符串长度
     * @param string $str
     */
    public static function gbkLength($str)
    {
        $len=strlen($str);
        $i=0;
        while($i<$len)
        {
            if(preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/",$str[$i]))
            {
                $i+=2;
            }
            else
            {
                $i+=1;
            }
        }
        return $i;
    }

    /**
     * 计算UTF8编码的字符串长度
     * @param string $str
     */
    public static function utf8Length($str)
    {
        $i = 0;
        $count = 0;
        $len = strlen ($str);
        while ($i < $len)
        {
            $chr = ord ($str[$i]);
            $count++;
            $i++;
            if($i >= $len) break;
            if($chr & 0x80)
            {
                $chr <<= 1;
                while ($chr & 0x80)
                {
                    $i++;
                    $chr <<= 1;
                }
            }
        }
        return $count;
    }
}