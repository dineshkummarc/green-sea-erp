<?php
class UploadFile
{
    public static function upload($instance)
    {
        $path = Yii::app()->params['upload_path'] . date('Ymd');
        $url = Yii::app()->params['upload_url'] . date('Ymd') . '/';
        if (!file_exists($path))
            if (!mkdir($path))
                return false;

        $filename = md5_file($instance->tempName) . '.' . $instance->getExtensionName();
        $filePath = FindFiles::findFile($path, $filename);
        if (!$filePath)
        {
            if ($instance->saveAs($path . '/' . $filename))
                $filePath = $url .$filename;
            else
                return false;
        }
        else
        {
            $filePath = strrchr($filePath, 'uploads');
        }
        return $filePath;
    }

}