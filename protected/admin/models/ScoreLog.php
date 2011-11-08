<?php

/**
 * This is the model class for table "{{score_log}}".
 *
 * The followings are the available columns in table '{{score_log}}':
 * @property string $id
 * @property string $user_name
 * @property string $content
 */
class ScoreLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ScoreLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 保存日志
	 * @param int $score 操作的积分
	 * @param string $reason 操作原因
	 */
    public static function log($score, $reason)
	{
	    $sql = "INSERT INTO {{score_log}} (id, user_id, score, reason, create_time) VALUES (NULL, :user_id, :score, :reason, :create_time)";
	    $command = Yii::app()->db->createCommand($sql);
	    $command->bindValue(":user_id", Yii::app()->user->id);
	    $command->bindValue(":score", $score);
	    $command->bindValue(":reason", $reason);
	    $command->bindValue(":create_time", Yii::app()->params['timestamp']);
	    $command->execute();
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{score_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, score, reason, create_time', 'required'),
			array('user_id, score, create_time', 'length', 'max'=>10),
			array('reason', 'length', 'max'=>255),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_name' => 'User Name',
			'content' => 'Content',
		);
	}
}