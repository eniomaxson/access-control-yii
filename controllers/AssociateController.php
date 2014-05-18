<?php

Yii::import('usercontrol.controllers.DefaultController');

class AssociateController extends DefaultController
{
	
	public function actionIndex($id)
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);
		
		$model= new Profile('search');

		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Profile']))
			$model->attributes=$_GET['Profile'];

		$context = array(
			'model'=>$model,
			'user'=> User::model()->findByPk($id),
			);

		$this->render('index', $context) ;    
	}
	// this method resposable to designate a profile for user
	public function actionAssociateProfile()
	{
		$this->module->authorize(Yii::app()->user->id, Resource::_key['acesso']);
		
		if (!isset($_POST['user_id']) and !isset($_POST['profile_id']))
			return false;

		$user_id = (int) $_POST['user_id'];
		
		$profile_id = (int) $_POST['profile_id'];
		
		if ($user_id == 0 and $profile_id == 0)
			return false;
		
		if(UserProfile::model()->exists('user_id=:user_id and profile_id=:profile_id', array(':user_id'=>$user_id,':profile_id'=>$profile_id)))
			return false;

		$user_profile = new UserProfile;
		$user_profile->user_id = $user_id;
		$user_profile->profile_id = $profile_id;
		$user_profile->save(false);
	}    
	// mmethod responsable to disassociate a profile from user
	public function actionDisassociateProfile()
	{
		$this->module->authorize(Yii::app()->user->id, Resource::_key['acesso']);
		
		if (!isset($_POST['user_id']) and !isset($_POST['profile_id']))
			return false;

		$user_id = (int) $_POST['user_id'];
		
		$profile_id = (int) $_POST['profile_id'];
		
		if ($user_id == 0 and $profile_id == 0)
			return false;

		$user_profile = UserProfile::model()->find('user_id=:user_id and profile_id=:profile_id', array(':user_id'=>$user_id,':profile_id'=>$profile_id));
		
		if($user_profile)
			$user_profile->delete();            
	}    
	
}