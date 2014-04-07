<div id="modal-associate-profile-form" class="modal <?php echo $user_id > 0 ? 'show': 'hide' ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
       <h4 class="blue bigger"> <?php echo  isset($user_name) ? 'Usuário: ' . ucfirst( $user_name ): 'Perfis de Acesso' ; ?> </h4>
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
                    'class'=>'span12', 

                ), 
            )); 
            ?>
                <input type="hidden" value="<?php echo ($user_id > 0) ? $user_id :'' ?>" id='user_id' />

            </div>
        <div class="row-fluid" id="profiles">
           
        <?php

            $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'grid-profile',
                'enablePagination'=>true,
                'type'=>'striped condensed', 
                'enableSorting'=>false,
                'template'=>'{items}',
                'rowCssClassExpression'=> 'Profile::model()->check_profile_user(' . $user_id  .' , $data->id) ? \'success\' : \'warning\'' ,
                'dataProvider'=> $data_provider,
                'columns'=>array(
                    array('name'=>'id',),
                    array('name'=>'name'),
                    array(
                        'class'=>'CButtonColumn',
                        'template'=>'{add}{rem}',
                        'buttons'=>array(
                            'add'=>array(
                                'label'=>'',
                                'options'=>array(
                                    'class'=>'icon-ok',
                                    'rel'=>'tooltip',
                                    'title'=>'Adicionar',
                                    'onClick'=>'(function(t){
                                        var profile_id = $(t).parent().parent().children(":first-child").text();
                                        var user_id    =  $("#user_id").val();
                                        if (user_id == ""){
                                            alert("Selecione um usuário!")
                                            return false;
                                        }
                                        $.ajax({ 
                                            type: "POST", 
                                            data: {user_id: user_id, profile_id: profile_id}, 
                                            url: "'.CController::createUrl("associateProfile").'", 
                                            success: function(e){ 
                                                $.fn.yiiGridView.update("grid-profile"); 
                                            }, 
                                            error : function(e){ 
                                                $("#myerrordiv").html(e.responseText).show(); 
                                            } 
                                        }); 
                                    })(this)',
                                ),
                            ),
                            'rem'=>array(
                                'label'=>'',
                                'options'=>array(
                                    'class'=>'icon-trash',
                                    'rel'=>'tooltip',
                                    'title'=>'Adicionar',
                                    'onClick'=>'(function(t){
                                        var profile_id = $(t).parent().parent().children(":first-child").text();
                                        var user_id    =  $("#user_id").val();
                                        if (user_id == ""){
                                            alert("Selecione um usuário!")
                                            return false;
                                        }
                                        $.ajax({ 
                                            type: "POST", 
                                            data: {user_id: user_id, profile_id: profile_id}, 
                                            url: "'.CController::createUrl("disassociateProfile").'", 
                                            success: function(e){ 
                                                $.fn.yiiGridView.update("grid-profile"); 
                                            }, 
                                            error : function(e){ 
                                                $("#myerrordiv").html(e.responseText).show(); 
                                            } 
                                        }); 
                                    })(this)',
                                ),
                            ),
                        ),
                    )
                ),
            ));

        ?>
<!--             <table class="table table-striped">
                <thead>
                    <th>
                        Perfil
                    </th>
                    <th>#</th>
                    <th style="display: none;"></th>
                </thead>
                <tbody>
                    <?php// foreach ($model->findAll() as $profile): ?>
                        <tr>
                            <td class="span5"><?php// echo $profile->name; ?></td>
                            <td class="span1"> 
                                <label>
                                    <input type="checkbox" name=''><span class="lbl"></span>
                                </label> 
                            </td>
                            <td style="display: none"><?php// echo $profile->id; ?></td>
                        </tr>
                    <?php// endforeach; ?>
                </tbody>
            </table> -->
        </div>
        <div class="modal-footer">
            <a href='<?php echo $this->createUrl('index'); ?>'class="btn btn-danger" role='button' id='btn-close-profile'>Fechar</a>
        </div>
        <?php $this->endWidget(); ?>
    </div>