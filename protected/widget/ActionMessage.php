<?php
class ActionMessage extends CWidget
{
    public $keys;
    public $template = '<div class="msg {key}">{message}</div>';
    public $htmlOptions=array();

    public function run()
    {
        $id = $this->getId();

        if (isset($this->htmlOptions['id']))
			$id = $this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

        if (is_string($this->keys))
            $this->keys = array($this->keys);

        $content = '';
        foreach ($this->keys as $key)
        {
            if (Yii::app()->user->hasFlash($key))
            {
                $content = strtr($this->template,array(
					'{key}'=>$key,
					'{message}'=>Yii::app()->user->getFlash($key),
				));
            }
        }

        if ($content !== '')
        {
            echo CHtml::openTag('div',$this->htmlOptions);
            echo $content;
            echo CHtml::closeTag('div');
            Yii::app()->clientScript->registerScript(__CLASS__.'#'.$id,
				"setTimeout(\"jQuery('#$id').animate({height: 0}, 2000, function () { $(this).hide() });\", 3000);",CClientScript::POS_READY);
        }
    }
}
?>