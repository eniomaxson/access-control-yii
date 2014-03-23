<?php

class User_controlModule extends CWebModule
{
	private $table_user;
	private $user_primary_key;
	private $user_model;
	private $install;

	public function init()
	{
		$this->setImport(array(
			'usercontrol.models.*',
			'usercontrol.components.*',
		));

		if ($this->install) 
		{
			$this->create_db()
		} 
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	# create tables used in this module
	private function create_db(){
		$sql_path = $this->getBasePath();

		$connection = Yii::app()->db;

		if ($connection->getDriverName() == 'mysql')
			$file_sql = file_get_contents($sql_path . '/data/mysql');
		
		if($connection->getDriverName() == 'pgsql')
			$file_sql = file_get_contents($sql_path .'/data/pgsql');

		$file_sql = str_replace('{table_user}',$this->table_user, $file_sql);

		$file_sql = str_replace('{user_primary_key}',$this->user_primary_key, $file_sql);
		
		$success = true;
		
		$arr_sql = explode(';', $file_sql);
		
		$transaction = $connection->beginTransaction();

		try{
			foreach ($arr_sql as $sql) {
				$command = $connection->createCommand($sql);
				$command->execute();
			}
			$transaction->commit();
		}catch(Exption $e){
			$transaction->rollBack();
			return !$success;
		}
		return $success; 
	}

	public function get_table_user()
	{
		return $this->table_user;
	}

	public function get_user_primary_key()
	{
		return $this->user_primary_key;
	}
	public function get_user_model()
	{
		return $this->user_model;
	}
	
	# responsable method for validate one user for one resource

	public function authorize($user_id, $resource_key)
    {
        return Resource::model()->authorize($user_id, $resource_key);
    }
}
