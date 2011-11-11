<?php
class Dumper extends CVarDumper
{
    public static function dump($var, $highlight = true, $depth = 10)
    {
        header("Content-Type: text/html;charset=utf8;");
        parent::dump($var, $depth, $highlight);
    }

    public static function dumpString($var, $highlight = true, $depth = 10)
    {
        return parent::dumpAsString($var, $depth, $highlight);
    }
}
?>