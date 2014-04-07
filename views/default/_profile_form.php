<div id="modal-profile-form" class="modal <?php echo $model->name ? 'show': 'hide' ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
     <h4 class="blue bigger">Novo perfíl</h4>
 </div>

 <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',  array(
    'id' => 'frm-profile',
    'method'=>'post',
    'action'=> $model->isNewRecord ? 
    $this->createUrl('createProfile') :
    $this->createUrl('updateProfile',array('id'=>$model->id)) 
    )); ?>

    <div class="modal-body">
        <div class="row-fluid">
            <div class="span4">
                <div class="control-group">
                    <label class="control-label" for="form-field-username">Descrição</label>

                    <div class="controls">
                        <?php echo $form->textFieldRow($model, 'name', array('class' => 'input-xlarge')); ?>
                    </div>
                </div>
                <?php if (!isset($model->id )): ?>
                <div class="control-group">
                    <label for="#Profile_id">Importar De</label>
                    <div class="controls">
                        <?php echo $form->dropDownList($model,'copy_from', Chtml::listdata(Profile::model()->findAll(), 'id', 'name'),array( 'empty'=>'Selecione','class' => 'input-xlarge') ); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <?php if ($model->id > 0): ?> <a href="#" id="btn-remove-profile" class="btn btn-danger"><i class='icon-trash'></i> Remover!</a> <?php endif; ?>
        <a href='<?php echo $this->createUrl('index'); ?>'class="btn btn-info" role='button' id='btn-close-profile'>Fechar</a>

        <input class="btn btn-primary" id="" type="submit" value="salvar" />
    </div>
    <?php $this->endWidget(); ?>
</div>
