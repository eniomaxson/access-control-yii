<?php

class Resource extends CActiveRecord
{
    private final $key = array(
        'cadastro_cliente' => 1,
    );
    
    public function getKey($index)
    {
        return $this->key[$index];
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
            array('name, key, title, url, public', 'required'),
            array('key, public, category_id', 'numerical', 'integerOnly' => true),
            array('name, url, icon', 'length', 'max' => 45),
            array('title', 'length', 'max' => 60),
            array('id, name, key, title, url, public, category_id', 'safe', 'on' => 'search'),
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
            'name' => 'Nome',
            'key' => 'Chave',
            'title' => 'Titulo',
            'url' => 'Link',
            'public' => 'Publico',
            'icon' => 'Icone',
            'category_id' => 'Recurso Categoria',
            );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('key', $this->key);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('public', $this->public);
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
            WHERE u.id = :user_id AND r.key = :key";

            $command = $connection->createCommand($sql);

            $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
            $command->bindParam(":key", $resource_key, PDO::PARAM_INT);

            $data = $command->queryScalar();

            (Int) $public = $connection->createCommand("select 1 from resource where key = {$resource_key}")->queryScalar();

            if (!empty($data) || $public == 1)
            {
                $authorized = true;
            }
        } catch (Exception $exc)
        {
            throw new CHttpException(500, 'Aconteceu um problema interno.');
        }

        return $authorized;
    }

    public function make_public($resource_id)
    {
        Resource::model()->updateByPk($resource_id, array('public' => 1));
    }

    public function make_private($resource_id)
    {
        Resource::model()->updateByPk($resource_id, array('public' => 0));
    }

    public function add_resource_to_profile($resource_id, $profile_id)
    {
        $model = new ProfileResource();
        $model->attributes = array('profile_id' => $profile_id, 'resource_id' => $resource_id);
        $model->save(false);
    }

    public function remove_resource_from_profile($resource_id, $profile_id)
    {    
        $deleted = ProfileResource::model()->deleteAll('profile_id = :profile_id and resource_id = :resource_id',
            array(':profile_id' => (Int) $profile_id, ':resource_id' => (Int) $resource_id ));
        return $deleted;
    }

    //validaRecursoPerfil
    public function check_resource_from_profile($profile_id, $resource_id)
    {
        $validated = ProfileResource::model()->exists('profile_id=:profile_id AND resource_id=:resource_id', array(':profile_id' => $profile_id, ':resource_id' => $resource_id));
        return $validated;
    }
}