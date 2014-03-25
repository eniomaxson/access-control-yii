<div id="modal-associate-profile-form" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
       <h4 class="blue bigger">Usuário perfíl</h4>
   </div>

   <?php $form = $this->beginWidget('CActiveForm',  array(
    'id' => 'frm-profile',
    'method'=>'post',
    )); ?>

    <div class="modal-body">
        <div class="row-fluid">
         <?php 
         $this->widget('zii.widgets.jui.CJuiAutoComplete', array( 
            'id'=>'usuario', 
            'name'=>'usuario-find',  
            'sourceUrl'=>$this->createUrl('findUser'), 
            'options'=>array( 
                'autofocus'=>true, 
                'delay'=>50, 
                'showAnim'=>'fold', 

                'select'=>"js: function(event, ui) { 
                    $('#user_id').val(ui.item.id).change(); 
                    return true; 
                }", 

                'minLength'=>'2', 
                ), 
            'htmlOptions'=>array( 
                'class'=>'campor', 
                'placeholder'=>'Name', 
                            //'required'=>'required', 
                'class'=>'span12', 

                ), 
            )); 
            ?>

            </div>
        <div class="row-fluid">
            <table class="table table-striped">
                <thead>
                    <th>
                        Perfil
                    </th>
                    <th>#</th>
                    <th style="display: none;"></th>
                </thead>
                <tbody>
                    <?php foreach ($model->findAll() as $profile): ?>
                        <tr>
                            <td class="span5"><?php echo $profile->name; ?></td>
                            <td class="span1"> 
                                <label>
                                    <input type="checkbox" name=''><span class="lbl"></span>
                                </label> 
                            </td>
                            <td style="display: none"><?php echo $profile->id; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <a href='<?php echo $this->createUrl('index'); ?>'class="btn" role='button' id='btn-close-profile'>Cancelar</a>

            <input class="btn btn-primary" id="" type="submit" value="salvar" />
        </div>
        <?php $this->endWidget(); ?>
    </div>
