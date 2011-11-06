<<<<<<< HEAD
<?php
class Dumper extends CVarDumper
{
    public static function dump($var, $highlight = true, $depth = 10)
    {
        parent::dump($var, $depth, $highlight);
    }

    public static function dumpString($var, $highlight = true, $depth = 10)
    {
        return parent::dumpAsString($var, $depth, $highlight);
    }
}
=======
<?php
class Dumper extends CVarDumper
{
    public static function dump($var, $highlight = true, $depth = 10)
    {
        parent::dump($var, $depth, $highlight);
    }

    public static function dumpString($var, $highlight = true, $depth = 10)
    {
        return parent::dumpAsString($var, $depth, $highlight);
    }
}
>>>>>>> ee77750e3ad3c002b0b50fb19d8daba9222d91c4
?>