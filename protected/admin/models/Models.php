<?php

/**
 * This is the model class for table "{{models}}".
 *
 * The followings are the available columns in table '{{models}}':
 * @property string $id
 * @property string $nick_name
 * @property string $password
 * @property string $head_img
 * @property string $picture
 * @property string $area_id
 * @property string $china_name
 * @property string $english_name
 * @property integer $height
 * @property integer $weight
 * @property integer $chest
 * @property integer $waist
 * @property integer $hip
 * @property string $shoes
 * @property integer $sign_up
 * @property integer $level
 * @property string $price_markup
 */
class Models extends CActiveRecord
{

	public function behaviors()
    {
        return array(
            'uploadFile'=>array(
                'class'=>'application.behaviors.UploadFileBehavior',
            ),
        );
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @return Models the static model class
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
		return '{{models}}';
	}

	/**
	 * 获取模特
	 */
	public static function getModel()
	{
		if(!empty($id))
			$sql = "SELECT id, nick_name FROM {{models}} WHERE id =".$id;
		else
			$sql = "SELECT id, nick_name FROM {{models}}";
		$command = Yii::app()->db->createCommand($sql);
		$models = $command->queryAll();
        return $models;
	}
	/**
	 * 获取模特姓名
	 */
	public static function getModelName($id = null)
	{
		$sql = "SELECT nick_name FROM {{models}} WHERE id =".$id;
		$command = Yii::app()->db->createCommand($sql);
		$models = $command->queryScalar();
        return $models;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nick_name, password, head_img, picture, area_id, china_name, english_name, height, weight, chest, waist, hip, shoes, sign_up, level, price_markup', 'required'),
			array('height, weight, chest, waist, hip, sign_up, level', 'numerical', 'integerOnly'=>true),
			array('nick_name, password', 'length', 'max'=>32),
			array('head_img, picture', 'length', 'max'=>200),
			array('area_id, china_name', 'length', 'max'=>10),
			array('english_name', 'length', 'max'=>20),
			array('shoes', 'length', 'max'=>4),
			array('price_markup', 'length', 'max'=>3),
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
		    'Styles'=>array(self::MANY_MANY, 'Style', '{{model_styles}}(model_id, style_id)'),
		    'Area'=>array(self::BELONGS_TO, 'Area', 'area_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nick_name' => 'Nick Name',
			'password' => 'Password',
			'head_img' => 'Head Img',
			'picture' => 'Picture',
			'area_id' => 'Area',
			'china_name' => 'China Name',
			'english_name' => 'English Name',
			'height' => 'Height',
			'weight' => 'Weight',
			'chest' => 'Chest',
			'waist' => 'Waist',
			'hip' => 'Hip',
			'shoes' => 'Shoes',
			'sign_up' => 'Sign Up',
			'level' => 'Level',
			'price_markup' => 'Price Markup',
		);
	}
}