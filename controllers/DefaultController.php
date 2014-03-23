<?php

class DefaultController extends Controller
{
	protected function authorize($resource_key)
    {
        if (!Resource::model()->authorize(Yii::app()->user->id, $resource_key))
        {
            throw new CHttpException(403, 'Você não possui permissão para executar esse recurso! Duvidas contate o administrador do sistema.');
        }
    }

    public function actionIndex()
    {
        //$this->autorizar(2);
        $model = new Profile;
        $this->render('index', array('model' => $model));
    }


    #actionNovoPerfil
    public function actionNewProfile()
    {
        if (isset($_POST['Profile']))
        {
            $model = new Profile;
            $model->attributes = $_POST['Profile'];
            
            if ($model->save())
                echo 'ok';
            else
                print_r($model->getErrors());
        }
    }

    #PermitirRecurso
    public function actionUpdateResource()
    {
        $profile = isset($_POST['perfil']) ? $_POST['perfil'] : false;
        
        $resources = $_POST['recurso'];

        $resource_id = array_keys($resources);
        $permitted = array_values($resources);
        
        $resource = new Resource;

        if ($profile)
        {
            if ($permitted[0] === "true")
                $resource->add_resource_to_profile($resource_id[0], $this->profile_id);
            else
                $resource->remove_resource_from_profile($resource_id[0], $this->profile_id)
        } else
        {
            if ($permitted[0] === "true")
                Resource::model()->make_public($resource_id);
             else
                Resource::model()->make_private($resource_id);
        }
    }
    
    #AtualizaGradeRecurso
    public function actionUpdateGridResource()
    {
        $profile = Profile::model()->findByPk($_POST['profile']);

        $grid = $this->renderPartial('_grid_resource', array('profile' => $profile), true);

        echo $grid;
    }

    #actionApagarItem
    public function actionRemoveProfile($profile_id)
    {
        try
        {
            Profile::model()->deleteByPk($profile_id);

        } catch (Exception $e)
        {
            throw new CHttpException(500, 'Não foi possivél concluir a transação, registro possui dependentes!');
        }
    }
}