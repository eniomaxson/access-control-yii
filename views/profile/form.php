<?php

$this->breadcrumbs=array(
    'Perfil'=>array('index'),
    $model->isNewRecord ? 'Novo': 'Atualização',
    );
?>

<h3> <?php echo $this->pageTitle ?> </h3>
    
<hr />

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',  array(
    'id' => 'frm-profile',
    'method'=>'post',
    'type'=>'horizontal',
    'action'=> $model->isNewRecord ?  $this->createUrl('create') : $this->createUrl('update',array('id'=>$model->id)) ));
?>


<?php echo $form->textFieldRow($model, 'name'); ?>  

<?php if (!isset($model->id )): ?>
        <?php echo $form->dropDownListRow($model,'copy_from',
        Chtml::listdata(Profile::model()->findAll(), 'id', 'name'),
        array( 'empty'=>'Selecione',) ); ?>
<?php endif; ?>


<div class='form-actions'>
    <a href='<?php echo $this->createUrl('index'); ?>'class="btn btn-danger" role='button' id='btn-close-profile'>Fechar</a>
    <input class="btn btn-primary" id="" type="submit" value="salvar" />
</div>

<?php $this->endWidget(); ?>
