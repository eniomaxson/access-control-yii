<?php

class User extends CActiveRecord
{
	public $rememberMe;
	private $_identity;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'user';
	}

	public function rules()
	{
		return array(
			array('first_name, last_name, username, password', 'required', 'on'=>'create'),
			array('username', 'unique',),
            array('password','authenticate', 'on'=>'login'),
			array('super_user,','safe'),
			);
	}

	public function relations()
	{
		return array(
			'user_profiles' => array(self::HAS_MANY, 'UserProfiles', 'user_id'),
			);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'Cod.',
			'first_name' => 'Nome',
			'last_name' => 'Sobrenome',
			'email' => 'Email',
			'super_user' => 'Super usuário',
			'username'=> 'Login',
			'password' => 'Senha',
			);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('super_user',$this->super_user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			));
	}

	protected function beforeSave()
	{
		$this->password = md5($this->password);
		return parent::beforeSave();
	}

	public function getFullName()
	{
		return $this->first_name . ' ' . $this->last_name; 
	}

	public function authenticate($attribute, $params) {
		if (!$this->hasErrors()) {
			$this->_identity = new UserIdentity($this->username, $this->password);
			if (!$this->_identity->authenticate())
				$this->addError('password', 'Incorrect username or password.');
		}
	}

    public function login() {
    	if ($this->_identity === null) {
    		$this->_identity = new UserIdentity($this->username, $this->password);	
    		$this->_identity->authenticate();
    	}

    	if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else
        	return false;
    }
}