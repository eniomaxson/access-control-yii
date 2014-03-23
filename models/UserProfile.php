<?php

/**
 * This is the model class for table "usuario_perfil".
 *
 * The followings are the available columns in table 'usuario_perfil':
 * @property integer $id
 * @property integer $perfil_id
 * @property integer $Usuario_id
 *
 * The followings are the available model relations:
 * @property Perfil $perfil
 * @property Usuario $usuario
 */
class UsuarioPerfil extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UsuarioPerfil the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'usuario_perfil';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('perfil_id, Usuario_id', 'required'),
			array('perfil_id, Usuario_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, perfil_id, Usuario_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'perfil' => array(self::BELONGS_TO, 'Perfil', 'perfil_id'),
			'usuario' => array(self::BELONGS_TO, 'Usuario', 'Usuario_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'perfil_id' => 'Perfil',
			'Usuario_id' => 'Usuario',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('perfil_id',$this->perfil_id);
		$criteria->compare('Usuario_id',$this->Usuario_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}