<?php

/**
 * This is the model class for table "{{shoot_base}}".
 *
 * The followings are the available columns in table '{{shoot_base}}':
 * @property string $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Admin[] $admins
 * @property Models[] $models
 * @property Order[] $orders
 */
class ShootBase extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShootBase the static model class
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
        return '{{shoot_base}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max'=>50),
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
            'Admins' => array(self::HAS_MANY, 'Admin', 'city_id'),
            'Models' => array(self::HAS_MANY, 'Models', 'base_id'),
            'Orders' => array(self::HAS_MANY, 'Order', 'city_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
        );
    }
}