<?php

class Resource extends CActiveRecord
{
    private  $_key = array(
        'cadastro_cliente' => 1,
    );
    
    public function getKey($index)
    {
        return $this->_key[$index];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'resource';
    }

    public function rules()
    {
        return array(
            array('description, resource_key, title, url, private', 'required'),
            array('key, private, category_id', 'numerical', 'integerOnly' => true),
            array('description, url, icon', 'length', 'max' => 45),
            array('title', 'length', 'max' => 60),
            array('id, description, key, title, url, private, category_id', 'safe', 'on' => 'search'),
            );
    }

    public function relations()
    {
        return array(
            'profile_resources' => array(self::HAS_MANY, 'PerfilRecurso', 'recurso_id'),
            'category' => array(self::BELONGS_TO, 'RecursoCategoria', 'recurso_categoria_id'),
            );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'description' => 'Descrição',
            'resource_key' => 'Chave',
            'title' => 'Titulo',
            'url' => 'Link',
            'private' => 'Publico',
            'icon' => 'Icone',
            'category_id' => 'Recurso Categoria',
            );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('resource_key', $this->resource_key);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('private', $this->private);
        $criteria->compare('icon', $this->icon, true);
        $criteria->compare('category_id', $this->category_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            ));
    }

    public function list_resources($user_id = null)
    {
        $resources = null;
        
        $table_user = Yii::app()->getModule('usercontrol')->get_table_user();
        
        $user_primary_key = Yii::app()->getModule('usercontrol')->get_user_primary_key();

        $sql = 'SELECT r.* FROM resource r WHERE r.public = 1';

        try
        {
            $con = Yii::app()->db;
            
            if ($user_id)
            {
                $sql .= " UNION 
                
                SELECT r.* FROM resource r 
                
                INNER JOIN profile_resource pr ON pr.resource_id = r.id

                INNER JOIN profile p ON p.id = pr.profile_id
                
                INNER JOIN user_profile up ON up.profile_id = p.id
                
                INNER JOIN {$table_user} u ON u.{$user_primary_key} = up.user_id
                
                WHERE u.{$user_primary_key} = :user_id  ORDER BY 'r.title'";
            }

            $command = $con->createCommand($sql);

            if ($user_id != null)
            {
                $command->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            }
            
            $recursos = $command->queryAll();

        } catch (Exception $exc)
        {
            throw new CHttpException(500, 'Erro aqui interno');
        }

        return $recursos;
    }

    public function authorize($user_id, $resource_key)
    {
        $table_user = Yii::app()->getModule('usercontrol')->get_table_user();
        
        $user_primary_key = Yii::app()->getModule('usercontrol')->get_user_primary_key();

        $authorized = false;
        try
        {
            $connection = Yii::app()->db;

            $sql = "SELECT 1 FROM {$table_user} u 
            INNER JOIN user_profile up ON up.user_id = u.{$user_primary_key}
            INNER JOIN profile p ON p.id = up.profile_id
            INNER JOIN profile_resource pr ON pr.profile_id = p.id
            INNER JOIN resource r ON r.id = pr.resource_id 
            WHERE u.{$user_primary_key} = :user_id AND r.resource_key = :key";

            $command = $connection->createCommand($sql);

            $command->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $command->bindParam(":key", $resource_key, PDO::PARAM_INT);

            $data = $command->queryScalar();

            (Int) $public = $connection->createCommand("select 1 from resource where resource_key = {$resource_key} and private = 0")->queryScalar();

            if (!empty($data) || !empty($public))
            {
                $authorized = true;
            }
        } catch (Exception $exc)
        {
            throw new CHttpException(500, 'Falha interna!');
        }

        return $authorized;
    }

    public function make_public()
    {
        $this->updateByPk($this->id, array('private' => 0));
    }

    public function make_private()
    {
        $this->updateByPk($this->id, array('private' => 1));
    }

    public function add_resource_to_profile($resource_id, $profile_id)
    {
        $model = new ProfileResource();
        $model->attributes = array('profile_id' => $profile_id, 'resource_id' => $resource_id);
        $model->save(false);
    }

    //validaRecursoPerfil
    public function check_resource_from_profile($profile_id, $resource_id)
    {
        $validated = ProfileResource::model()->exists('profile_id=:profile_id AND resource_id=:resource_id', array(':profile_id' => $profile_id, ':resource_id' => $resource_id));
        return $validated;
    }

    # method that verify if user is super 
    public function is_super_user($user_id)
    {            
        $user_model=Yii::app()->getModule('usercontrol')->user_model;
        
        Yii::import('application.models.' . $user_model);
        
        $super_user = false;
        
        if (!isset($user_id))
            return $super_user;

        $user_primary_key = Yii::app()->getModule('usercontrol')->get_user_primary_key();
        
        $user = $user_model::model()->find("$user_primary_key = $user_id");
        
        if ($user->super_user){
            $super_user = true;
        }

        return $super_user;
    }
}