<?php
return array(
    1=>array(
        'text'=>'拍上衣整体图时，是否需要盘起头发以便于展示领口特点？',
        'dbtext'=>'%s盘起头发以便于展示领口特点',
        'options'=>array(
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][1]',
                'value'=>'需要',
                'text'=>'是'
            ),
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][1]',
                'value'=>'不需要',
                'text'=>'否',
                'checked'=>true
            )
        ),
        'other'=>'<span style="color: red;">注意：盘起头发可能会影响整体造型的美感，按绿浪拍摄标准，<br/>
            我们会拍领口的局部特写，绿浪建议您以整体的美感为第一考<br/>
            虑，领口特点通过局部特写来展示。</span>',
    ),
    2=>array(
        'text'=>'如果刚好合适的话，我们是否可以用您自己的服饰来搭配您的衣服？',
        'dbtext'=>'%s用自己的服饰来搭配您的衣服',
        'options'=>array(
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][2]',
                'value'=>'可以',
                'text'=>'是'
            ),
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][2]',
                'value'=>'不可以',
                'text'=>'否',
                'checked'=>true
            )
        ),
    ),
    3=>array(
        'text'=>'拍裤子时，是否可以用靴子搭配？',
        'dbtext'=>'%s用靴子搭配',
        'options'=>array(
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][3]',
                'value'=>'可以',
                'text'=>'是'
            ),
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][3]',
                'value'=>'不可以',
                'text'=>'否',
                'checked'=>true
            )
        ),
        'other'=>'<span>注意：这会影响裤脚的局部展示。</span>'
    ),
    4=>array(
        'text'=>'拍外套时，是否必须要有敞开拉链的图片？',
        'dbtext'=>'%s有敞开拉链的图片',
        'options'=>array(
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][4]',
                'value'=>'要',
                'text'=>'是'
            ),
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][4]',
                'value'=>'不要',
                'text'=>'否',
                'checked'=>true
            )
        ),
    ),
    5=>array(
        'text'=>'秋冬的短袖上衣搭配：',
        'dbtext'=>'秋冬的短袖上衣搭配：%s',
        'options'=>array(
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][5]',
                'value'=>'长衫打底',
                'text'=>'长衫打底'
            ),
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][5]',
                'value'=>'带长手套',
                'text'=>'带长手套'
            ),
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][5]',
                'value'=>'均可',
                'text'=>'均可',
                'checked'=>true
            )
        ),
    ),
    6=>array(
        'text'=>'拍长上衣时，您是要搭配？',
        'dbtext'=>'拍长上衣时，上衣搭配：%s',
        'options'=>array(
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][6]',
                'value'=>'裤子',
                'text'=>'裤子'
            ),
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][6]',
                'value'=>'丝袜',
                'text'=>'丝袜'
            ),
            array(
                'type'=>'radio',
                'name'=>'Form[shoot_notice][6]',
                'value'=>'均可',
                'text'=>'均可',
                'checked'=>true
            )
        ),
    ),
);
?>