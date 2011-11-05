<?php
class FindFiles
{
    public static function findFile($dir, $fileName)
    {
        $filePath = false;
        $handle=opendir($dir);
        while(($file=readdir($handle))!==false)
        {
            if($file === '.' || $file === '..')
                continue;

            $path=$dir.DIRECTORY_SEPARATOR.$file;
            if (is_file($path))
            {
                if ($file === $fileName)
                {
                    $filePath = $path;
                    break;
                }
            }
//            else
//            {
//                $filePath = self::findFile($path, $fileName);
//                if ($filePath) break;
//            }
        }
        closedir($handle);
        $filePath = str_replace('\\', '/', $filePath);
        return $filePath;
    }
}