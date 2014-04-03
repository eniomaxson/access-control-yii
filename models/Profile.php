<?php


class Profile extends CActiveRecord
{
    public $copy_from;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'profile';
    }

    public function rules()
    {
        return array(
            array('name', 'required'),
            array('id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 45),
            array('name', 'unique'),
            array('id, name, copy_from', 'safe', 'on' => 'search'),

            );
    }

    public function relations()
    {
        return array(
            'profile_resources' => array(self::HAS_MANY, 'ProfileResource', 'profile_id'),
            'user_profiles' => array(self::HAS_MANY, 'UserProfile', 'profile_id'),
            );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Nome',
            'copy_from'=>'Importar De',
            );
    }

    public function search()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            ));
    }

    protected function afterSave()
    {   
        if ($this->copy_from > 0){
            $this->set_permission_profile_from();
        }
        return parent::afterSave();
    }

    protected function set_permission_profile_from()
    {
        $base_profile = Profile::model()->findByPk($this->copy_from);
        foreach ($base_profile->profile_resources as $value) {
            $model = new ProfileResource;
            
            $model->attributes=array('profile_id'=> $this->find(array('order'=>'id desc'))->id ,'resource_id'=>$value->resource_id);

            if (!$model->exists('profile_id=:p and resource_id=:r', array(':r'=>$model->resource_id,':p'=>$model->profile_id)))
                $model->save(false);
        }
    }

    public function find_user($find) 
    {
        $user_model=Yii::app()->getModule('usercontrol')->user_model;
        
        $arr_user = null;

        $user_find_to=Yii::app()->getModule('usercontrol')->user_find;
        
        Yii::import('application.models.' . $user_model);
        
        $user_primary_key = Yii::app()->getModule('usercontrol')->get_user_primary_key();
        
        $list_user = $user_model::model()->findAll("$user_find_to like '%$find%'");
        
        foreach ($list_user as $user) {
            $arr_user[] = array('label'=> $user->$user_find_to,'id'=>$user->$user_primary_key);
        }

        return $arr_user;
    }

    public function update_user_profile($user_id, $profiles)
    {   
        UserProfile::model()->deleteAll('user_id = ?', array($user_id) );

        foreach ($profiles as $profile_id) {
            $user_profile = new UserProfile;
            $user_profile->profile_id = $profile_id;
            $user_profile->user_id = $user_id;
            $user_profile->save(false);
        }
    }

    public function find_by_user_id($user_id = 0)
    {
        $data_provider= new CActiveDataProvider('Profile', array(
            'criteria'=>array(
                'order'=>'name',
            ),
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));
        return $data_provider;
    }

    public function check_profile_user($user_id, $profile_id)
    {
        $check = UserProfile::model()->exists('user_id = :user_id and profile_id = :profile_id', array(':user_id'=>$user_id, ':profile_id'=>$profile_id) );         
        return $check;
    }

}