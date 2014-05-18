<?php

Yii::import('usercontrol.controllers.DefaultController');

class ProfileController extends DefaultController
{
	public function actionIndex()
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);

		$model=new Profile('search');

		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Profile']))
			$model->attributes=$_GET['Profile'];

		$context = array('model'=>$model);

		$this->render('index', $context) ;
	}


	public function actionCreate()
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);

		$this->pageTitle = 'Novo - perfil';
		
		$model = new Profile;

		if (isset($_POST['Profile']))
		{
			$model->attributes = $_POST['Profile'];
			$model->copy_from  = $_POST['Profile']['copy_from'];
			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Registro gravado com sucesso. clique <a href="'. $this->createUrl('resource/', array('profile_id'=>$model->id)) .'">Aqui</a> para gerenciar os acessos!');
				$this->redirect(array('index'));
			}
		}
		$context =  array('model'=> $model);
		
		$this->render('form', $context);
	}

	public function actionUpdate($id)
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);
		
		$this->pageTitle = 'Atualizar - perfil #' .$id;

		$model = Profile::model()->findByPk($id);

		if (isset($_POST['Profile']))
		{
			$model->attributes = $_POST['Profile'];

			if ($model->save())
			{
				$this->redirect(array('index'));
			}
		}

		$context = array('model'=> $model);

		$this->render('form', $context);
	}

	public function actionDelete($id)
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);
		
		try{
           $model = Profile::model()->findByPk($id)->delete();
            
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		
		}catch(Exception $e)
		{
			throw new CHttpException(500, 'Operação não finalizada,o registro possui  dados vinculados!.');
		}
	}
}