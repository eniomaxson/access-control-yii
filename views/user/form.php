<?php
$this->breadcrumbs=array(
    'Usuário'=>array('index'),
    $model->isNewRecord ? 'Novo': 'Atualização',
    );
?>

<h3> <?php echo $this->pageTitle ?> </h3>
    
<hr />


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',  array(
    'id' => 'frm-profile',
    'method'=>'post',
    'type'=>'horizontal',
    'action'=> $model->isNewRecord ? 
    $this->createUrl('create') :
    $this->createUrl('update',array('id'=>$model->id)) 
    ));
?>

    <?php echo $form->textFieldRow($model, 'first_name', array('class' => 'input-xlarge')); ?>                
    <?php echo $form->textFieldRow($model, 'last_name', array('class' => 'input-xlarge')); ?>                
    <?php echo $form->textFieldRow($model, 'email', array('class' => 'input-xlarge')); ?>                
    <?php echo $form->textFieldRow($model, 'username', array('class' => 'input-xlarge')); ?>                
    <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'input-xlarge')); ?>                

    <div class='form-actions'>
        <a href='<?php echo $this->createUrl('index'); ?>'class="btn btn-danger" role='button' id='btn-close-profile'>Fechar</a>
        <input class="btn btn-primary" id="" type="submit" value="salvar" />
    </div>

<?php $this->endWidget(); ?>
