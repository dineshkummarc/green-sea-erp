<?php
class FileValidator extends CFileValidator
{
    private $_path;

    protected function validateAttribute($object, $attribute)
    {
        if (empty($this->_path))
            $this->_path = Yii::app()->params['upload_path'];

        if (!$object->$attribute instanceof CUploadedFile)
            return;
        if (!file_exists($this->_path))
        {
            if (!mkdir($this->_path))
            {
                $object->addError($attribute, '文件上传出错，目录新建失败！');
            }
        }
        else
        {
            if (!is_string($object->$attribute))
            {
                if ($object->isNewRecord)
                    parent::validateAttribute($object, $attribute);
                if (!$object->hasErrors())
                {
                    $filename = md5_file($object->$attribute->tempName).'.'.$object->$attribute->getExtensionName();
                    if (!file_exists($this->_path.$filename))
                        $object->$attribute->saveAs($this->_path.$filename);
                    $object->$attribute = Yii::app()->params['upload_url'].$filename;
                }
            }
        }
    }
}
?>