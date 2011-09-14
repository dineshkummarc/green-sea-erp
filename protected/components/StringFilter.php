<?php
/**
 * 字符串过滤类
 * @author Yuanchensi
 * 2011-08-25
 */
class StringFilter
{
    /**
     * 过滤JS脚本
     * @param string $str
     */
    public static function JavascriptFilter($str)
    {
        $re = "/<script.*>.*<\/script\s*>/isU";
        return preg_replace($re, '', $str);
    }
}
?>