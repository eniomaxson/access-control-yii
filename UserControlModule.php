<?php

class UserControlModule extends CWebModule
{
	public $install;
	public $brand;
	public $defaultController = 'resource';
	
	public function init()
	{
		$this->setImport(array(
			'usercontrol.models.*',
			'usercontrol.components.*',
		));

		if ($this->install) 
		{
			$this->create_db();
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
		$connection = Yii::app()->db;

		$file_sql = $this->prepare_sql_to_install($connection);

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
		}
	}

	protected function prepare_sql_to_install($connection)
	{
		$sql_path = $this->getBasePath();
		$file_sql = null;

		if ($connection->getDriverName() == 'mysql')
			$file_sql = file_get_contents($sql_path . '/data/mysql');
		
		if($connection->getDriverName() == 'pgsql')
			$file_sql = file_get_contents($sql_path .'/data/pgsql');

		return $file_sql;
	}

	public function authorize($user_id, $resource_key)
    {
    	$authorized = true;
    	
		if (!Resource::model()->is_super_user($user_id))
		{
        	if (!Resource::model()->authorize($user_id, $resource_key))
        	{
        		$authorized = false;			
        	}
		}  		   	

        if (!$authorized)
        	throw new CHttpException(403, 'Permisão negada para acessar este recurso!');
    }

    public function get_default_view()
    {
    	return $this->getViewPath() . '/default/base';
    }
}
