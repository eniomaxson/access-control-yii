<?php

class DefaultController extends Controller
{

    public function actionIndex($id = 0, $profile_id = 0)
    {
        $this->module->authorize(Yii::app()->user->id, 1);
        
        $data_provider = Profile::model()->find_by_user_id(isset($_GET['user_id']) ?: 0 );
        
        $model = new Profile;

        $context = array('model' => $model,'data_provider'=>$data_provider);

        if ((int) $id > 0){
            $context['user_id'] =  $id;
            $context['user_name'] = $this->module->get_user_name($id);
        }
        
        if ((int) $profile_id > 0)
            $context['profile_id'] = $profile_id;

        $this->render('index',$context);
    }

    #PermitirRecurso
    public function actionGrantResource()
    {
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
        $resource_id = $_POST['resource_id'];
        $resource=Resource::model()->findByPk($resource_id);
        
        if ($resource->private)
           $resource->make_public();
        else
           $resource->make_private();
    }

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
            $profile = Profile::model()->findByPk($profile_id)->delete();
        } catch (Exception $e)
        {
            throw new CHttpException(500, 'Não foi possivél concluir a transação, registro possui dependentes!');
        }
    }

    public function actionUpdateProfile($id){
        
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