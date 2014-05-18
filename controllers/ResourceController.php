<?php

Yii::import('usercontrol.controllers.DefaultController');

class ResourceController extends DefaultController
{

	public function actionIndex($user_id = 0, $profile_id = 0)
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);
		
		$this->pageTitle  = 'Configuração de Acesso';
		
		$data_provider = Profile::model()->find_by_user_id(isset($_GET['user_id']) ?: 0 );

		$model = new Profile;

		$context = array('model' => $model,'data_provider'=>$data_provider);

		if ((int) $user_id > 0){
			$context['user'] = User::model()->findByPk($user_id);
		}

		if ((int) $profile_id > 0)
			$context['profile_id'] = $profile_id;

		$this->render('index',$context);
	}

    #PermitirRecurso
	public function actionGrantResource()
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);

		if (!isset($_POST['profile_id']) and !isset($_POST['resource_id']))
			return false;

		$profile_id = (Int) $_POST['profile_id'];

		$resource_id  = (Int) $_POST['resource_id'];

		$alredy_exists = ProfileResource::model()->exists('profile_id=:profile_id and resource_id=:resource_id', array(':profile_id'=>$profile_id, ':resource_id'=>$resource_id));

		if (!$alredy_exists)
			Resource::model()->add_resource_to_profile($resource_id,$profile_id);
	}

        #PermitirRecurso
	public function actionRevokeResource()
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);

		if (!isset($_POST['profile_id']) and !isset($_POST['resource_id']))
			return false;

		$profile_id = (Int) $_POST['profile_id'];

		$resource_id  = (Int) $_POST['resource_id'];

		$profile_resource = ProfileResource::model()->find('profile_id=:profile_id and resource_id=:resource_id', array(':profile_id'=>$profile_id, ':resource_id'=>$resource_id));

		if ($profile_resource)
			$profile_resource->delete();
	}

	public function actionMakeResourcePublic()
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);

		$resource_id = $_POST['resource_id'];
		$resource=Resource::model()->findByPk($resource_id);

		if ($resource->private)
			$resource->make_public();
		else
			$resource->make_private();
	}

	public function actioncreateProfile()
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);

		if (isset($_POST['Profile']))
		{
			$model = new Profile;
			$model->attributes = $_POST['Profile'];
			$model->copy_from = $_POST['Profile']['copy_from'];

			if ($model->save())
				$this->redirect(array('index'));

			$data_provider = Profile::model()->find_by_user_id( 0 );
			$context = array('model' => $model,'data_provider'=>$data_provider);   
			$this->render('index', $context);  
		}
	}

/*    #actionApagarItem
	public function actionRemoveProfile($profile_id)
	{
		try
		{
			$profile = Profile::model()->findByPk($profile_id)->delete();
		} catch (Exception $e)
		{
			throw new CHttpException(500, 'Não foi possivél concluir a transação, registro possui dependentes!');
		}
	}*/

	public function actionUpdateProfile($id)
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);
		
		if (!isset($id))
			$this->redirect(array('index'));

		$model = Profile::model()->findByPk($id);

		if (isset($_POST['Profile']))
		{
			$model->attributes = $_POST['Profile'];

			if($model->save())
				$this->redirect(array('index'));

		}

		$data_provider = Profile::model()->find_by_user_id( 0 );
		$context = array('model' => $model,'data_provider'=>$data_provider);   
		$this->render('index', $context);
	}
}