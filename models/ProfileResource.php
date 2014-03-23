<?php

class ProfileResource extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'profile_resource';
    }

    public function rules()
    {
        return array(
            array('resource_id, profile_id', 'required'),
            array('resource_id, profile_id', 'numerical', 'integerOnly' => true),
            array('id, resource_id, profile_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'profile' => array(self::BELONGS_TO, 'Profile', 'profile_id'),
            'resource' => array(self::BELONGS_TO, 'Resource', 'resource_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'Cod.',
            'resource_id' => 'Recurso',
            'profile_id' => 'Perfil',
        );
    }

}