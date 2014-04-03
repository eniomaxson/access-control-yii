<?php

class DefaultController extends Controller
{

    public function actionIndex($id = 0)
    {
        $this->module->authorize(Yii::app()->user->id, 1);
        
        $data_provider = Profile::model()->find_by_user_id(isset($_GET['user_id']) ?: 0 );
        
        $model = new Profile;

        $context = array('model' => $model,'data_provider'=>$data_provider);

        if ((int) $id > 0){
            $context['user_id'] = $id;
        }

        $this->render('index',$context);
    }

    #PermitirRecurso
    public function actionUpdateResource()
    {
        $profile_id = (Int) $_POST['profile_id'] ?: false;
        
        $resources = $_POST['resource'];

        $resource_id = array_keys($resources);
        $permitted = array_values($resources);
        
        $resource = new Resource;

        if ($profile_id)
        {
            if ($permitted[0] === "true")
                $resource->add_resource_to_profile($resource_id[0], $profile_id);
            else
                $resource->remove_resource_from_profile($resource_id[0], $profile_id);
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

    #actionNovoPerfil
    public function actioncreateProfile()
    {
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

    public function actionUpdateProfile($id){
        
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

    public function actionFindUser($term)
    {
        $users = Profile::model()->find_user($term);

        echo json_encode($users);
    }

    public function actionAssociateProfile()
    {
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

    public function actionDisassociateProfile()
    {
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