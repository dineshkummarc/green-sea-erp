<?php

/**
 * This is the model class for table "{{star}}".
 *
 * The followings are the available columns in table '{{star}}':
 * @property string $id
 * @property string $admin_id
 * @property string $time
 * @property integer $is_month
 * @property string $star1
 * @property string $star2
 * @property string $star3
 * @property string $star4
 * @property string $star5
 *
 * The followings are the available model relations:
 * @property Admin $admin
 */
class Star extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Star the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{star}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('admin_id, time, is_month, star1', 'required'),
		    array('is_month', 'in', 'range'=>array(0,1)),
			array('admin_id, time', 'length', 'max'=>20),
			array('star1, star2, star3, star4, star5', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, admin_id, time, is_month, star1, star2, star3, star4, star5', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'admin_id' => 'Admin',
			'time' => 'Time',
			'is_month' => 'Is Month',
			'star1' => 'Star1',
			'star2' => 'Star2',
			'star3' => 'Star3',
			'star4' => 'Star4',
			'star5' => 'Star5',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('admin_id',$this->admin_id,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('is_month',$this->is_month);
		$criteria->compare('star1',$this->star1,true);
		$criteria->compare('star2',$this->star2,true);
		$criteria->compare('star3',$this->star3,true);
		$criteria->compare('star4',$this->star4,true);
		$criteria->compare('star5',$this->star5,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function starList(){
	    $criteria = new CDbCriteria(array('select'=>'*',));
	    $starList = Star::model()->with('Admin')->findAll($criteria);
	    return $starList;
	}
}