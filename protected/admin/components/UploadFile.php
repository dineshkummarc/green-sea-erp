<?php
class UploadFile
{
    public $error = "";
    public $fileUrl = "";

    public function upload($instance, $path = null, $url = null)
    {
        if (empty($path))
            $path = Yii::app()->params['upload_path'] . date('Ymd');

        if (empty($url))
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
                $this->error = "文件上传失败";
        }
        else
        {
            $filePath = strrchr($filePath, 'uploads');
        }
        $this->fileUrl = $filePath;
    }
}