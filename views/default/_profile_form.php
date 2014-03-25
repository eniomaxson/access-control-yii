<div id="modal-profile-form" class="modal <?php echo $model->id > 0 ? 'show': 'hide' ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
     <h4 class="blue bigger">Novo perfíl</h4>
 </div>

 <?php $form = $this->beginWidget('CActiveForm',  array(
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
                    <label for="#Profile_id">Codigo</label>
                    <div class="controls">
                        <?php echo $form->textField($model, 'id', array('disabled' => true, 'class' => 'input-small')); ?>
                        <?php echo $form->hiddenField($model,'id'); ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="form-field-username">Descrição</label>

                    <div class="controls">
                        <?php echo $form->textField($model, 'name', array('class' => 'input-xlarge')); ?>
                    </div>
                </div>

                <div class="control-group">
                    <label for="#Profile_id">Importar De</label>
                    <div class="controls">
                        <?php echo $form->dropDownList($model,'copy_from', Chtml::listdata(Profile::model()->findAll(), 'id', 'name'),array( 'empty'=>'Selecione','class' => 'input-xlarge') ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <a href='<?php echo $this->createUrl('index'); ?>'class="btn" role='button' id='btn-close-profile'>Cancelar</a>

        <input class="btn btn-primary" id="" type="submit" value="salvar" />
    </div>
    <?php $this->endWidget(); ?>
</div>
