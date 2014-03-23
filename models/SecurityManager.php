<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SegurancaDao
 *
 * @author maxson
 */
class Seguranca extends CFormModel
{

    private $profile_id;
    private $resources;

    public static function model()
    {
        return new Seguranca;
    }

    /* Método responsavel por autorizar o uso de um determinado recurso por um usuario autenticado */

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

    public function rules()
    {
        return array(
        );
    }

    public function attributeLabels()
    {
        return array(
        );
    }

    private function update_resource()
    {
        $resource_id = array_keys($this->resource);
        $permitted = array_values($this->resource);
        
        $resource = new Resource;
        
        if ($this->profile_id != null)
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

    //setPerfisErecursos
    public function grant_or_revoke_resource($profile_id, $resources)
    {
        $this->profile_id = $profile_id;
        $this->resources = $resources;
        $this->update_resource();
    }

    //validaRecursoPerfil
    public function checkResourceProfile($profile_id, $resource_id)
    {
        $validated = PerfilRecurso::model()->exists('profile_id=:profile_id AND resource_id=:resource_id', array(':profile_id' => $profile_id, ':resource_id' => $resource_id));
        return $validated;
    }

}

?>
