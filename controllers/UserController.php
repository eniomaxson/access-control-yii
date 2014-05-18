<?php

Yii::import('usercontrol.controllers.DefaultController');

class UserController extends DefaultController
{
	public function actionIndex()
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);

		$model = new User('search');

		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$context= array('model'=>$model);

		$this->render('index', $context) ;
	}

	public function actionCreate()
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);
		$this->pageTitle = 'Novo  usuário';

		$model = new User('create');

		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];

			if($model->save()){
				Yii::app()->user->setFlash('success', 'Registro gravado com sucesso! Clique <a href="'.  $this->createUrl('associate/', array('id'=> $model->id))  .'"> aqui </a>para adicionar perfis de acesso!.');
				$this->redirect(array('index'));
			}
		}

		$context = array(
			'model' => $model
			);

		$this->render('form', $context);
	}

	public function actionUpdate($id)
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);

		$this->pageTitle = 'Atualizar - usuário #' . $id;

		$model = User::model()->findByPk($id);

		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];

			if($model->save()){
				Yii::app()->user->setFlash('success', '<strong>Sucesso!</strong> Registro atualizado com sucesso!.');
				$this->redirect(array('index'));
			}
		}

		$context = array(
			'model' => $model
			);

		$this->render('form', $context);
	}

	public function actionDelete($id)
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);
		try{
			$model = User::model()->findByPk($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));

		}catch(Exception $e)
		{
			throw new CHttpException(500, 'Operação não finalizada,o registro possui  dados vinculados!.');
		}
	}


	public function actionFindUser($term)
	{
		$this->module->authorize(Yii::app()->user->id, Resource::$_key['acesso']);
		$term = htmlspecialchars($term);
		
        $list_user = User::model()->findAll("username like '%$term%'");
        
        foreach ($list_user as $user) {
            $arr_user[] = array('label'=> $user->getFullName(),'id'=>$user->id);
        }

		echo json_encode($arr_user);
	}
}