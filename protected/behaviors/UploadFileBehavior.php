<?php
class UploadFileBehavior extends CActiveRecordBehavior
{
    /**
     * 上传单个文件
     * @param CUploadedFile $instance 上传文件实例
     * @param string $attribute 文件路径保存属性
     */
    public function upload($instance, $attribute)
    {
        // 如果传过来的不是一个上传实例，则不作任何修改
        if (!$instance instanceof CUploadedFile)
            return true;

            // 赋值，并验证文件实例
        $this->owner->$attribute = $instance;
        if ($this->owner->validate(array($attribute)))
        {
            // 验证通过，保存文件
            $url = $this->saveFile($instance, $attribute);
            if ($url !== false)
                $this->owner->$attribute = $url;
            else
            {
                $this->owner->addError($attribute, '上传文件失败，请联系管理员');
                return false;
            }
            return true;
        }
        else
        {
            // 验证不通过，删除临时文件
            $this->owner->$attribute = null;
            $this->deleteFile($instance);
            $this->owner->addError($attribute, print_r($this->owner->getErrors()));
            return false;
        }
    }

    /**
     * 保存上传的文件
     * @param CUploadedFile $instance 上传文件实例
     * @param string $attribute 文件路径保存属性
     * @param string $path 文件保存路径
     * @param string $url 文件网络地址
     */
    private function saveFile($instance, $attribute, $path = null, $url = null)
    {
        $uploadFile = new UploadFile();
        $uploadFile->upload($instance, $path = null, $url = null);
        if (!empty($uploadFile->error))
        {
            $this->owner->addError($attribute, $uploadFile->error);
            return false;
        }
        return $uploadFile->fileUrl;
    }

    /**
     * 删除上传的文件
     * @param CUploadedFile $instance 上传文件实例
     */
    private function deleteFile($instance)
    {
        if (!$instance instanceof CUploadedFile)
            return;

        @unlink($ad->param->tempName);
    }

    public function multiUpload($instances)
    {
    }

}