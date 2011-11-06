<?php
class ImageBehavior extends CActiveRecordBehavior
{
    protected $image;
    protected $type;
    protected $width;
    protected $height;

    protected $newWidth;
    protected $newHeight;

    /**
     * 加载一个图片
     * @param string $filePath 原图路径
     */
    public function load($filePath)
    {
        list($this->width, $this->height, $this->type) = getimagesize($filePath);

        switch ($this->type)
        {
            case IMAGETYPE_GIF:
                $this->image = imagecreatefromgif($filePath);
                break;
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $this->image = imagecreatefrompng($filePath);
                break;
            default:
                throw new CException("不能识别的图片类型：" . $type);
        }

        return $this->owner;
    }

    /**
     * 调整图片大小
     * @param integer $width 宽
     * @param integer $height 高
     * @param string $attribute
     * @param string $filePath 原图路径
     */
    public function resize($width, $height, $attribute = null, $filePath = null)
    {
        if (empty($this->image))
            $this->load($filePath);

        $this->newWidth = $width;
        $this->newHeight = $height;

        $this->save($attribute, $filePath);

        return $this->owner;
    }

    /**
     * 保存图片
     * @param string $attribute
     * @param string $filePath 原图路径
     */
    public function save($attribute = null, $filePath = null)
    {
        $filePath = str_replace('uploads/', Yii::app()->params['upload_path'], $filePath);
        preg_match('{[a-z0-9]*\.[a-z0-9]*$}', $filePath, $fileName);
        $fileName = $fileName[0];
        $savePath = Yii::app()->params['upload_path'] . date('Ymd', Yii::app()->params['timestamp']) . '/' . 'thumb_' . $fileName;

        if ($this->newWidth <= 0)
            $this->newWidth = $this->width;

        if ($this->newHeight <= 0)
            $this->newHeight = $this->height;

        $newImage = imagecreatetruecolor($this->newWidth, $this->newHeight);
        imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $this->width, $this->height);
        switch ($this->type)
        {
            case IMAGETYPE_GIF:
                imagegif($newImage, $savePath);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($newImage, $savePath);
                break;
            case IMAGETYPE_PNG:
                imagepng($newImage, $savePath);
                break;
        }
        imagedestroy($newImage);
        imagedestroy($this->image);

        if (!is_file($savePath))
            throw new CException("图片输出错误");

        $url = Yii::app()->params['upload_url'] . date('Ymd', Yii::app()->params['timestamp']) . '/' . 'thumb_' . $fileName;
        $this->owner->$attribute = $url;
    }

}