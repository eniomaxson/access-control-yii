<?php

class UserProfile extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'user_profile';
	}

	public function rules()
	{
		return array(
			array('profile_id, user_id', 'required'),
			array('profile_id, user_id', 'numerical', 'integerOnly'=>true),
			array('id, profile_id, user_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		$user_model = Yii::app()->getModule('usercontrol')->get_user_model();
		
		return array(
			'profile' => array(self::BELONGS_TO, 'Perfil', 'profile_id'),
			'user' => array(self::BELONGS_TO,  $user_model, 'user_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'profile_id' => 'Perfil',
			'user_id' => 'Usuario',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('profile_id',$this->profile_id);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}