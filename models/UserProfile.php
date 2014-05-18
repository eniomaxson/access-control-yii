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
		return array(
			'profile' => array(self::BELONGS_TO, 'Profile', 'profile_id'),
			'user' => array(self::BELONGS_TO,  'User', 'user_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'Cod.',
			'profile_id' => 'Perfil',
			'user_id' => 'Usuário',
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