<div class="page-content">
<div class="page-header position-relative">
        <h2>
            Configuração de Acesso
        </h2>
        <div class="btn-group">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                Opções
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="#modal-profile-form" role="button" data-toggle="modal"><i class="icon-file"></i> Novo Perfil </a></li>
                <li><a href="#modal-associate-profile-form" role="button" data-toggle="modal"><i class="icon-tasks"></i> Associar Perfil </a></li>
            </ul>
        </div>  
    </div>

    <div id="dv-msg" class="span11"></div>

    <div class="row-fluid">
        <div class="span4">
            
            <?php
                $profile_id = isset($profile_id) ? $profile_id : 0;
                $this->widget('bootstrap.widgets.TbGridView', array(
                    'id'=>'index-grid-profile',
                    'enablePagination'=>true,
                    'type'=>'striped condensed', 
                    'enableSorting'=>false,
                    'selectableRows'=>1,
                    'template'=>'{items}',
                    'rowCssClassExpression'=> '' . $profile_id .'==  $data->id ? \'success\' : \'\'' ,
                    'dataProvider'=> Profile::model()->search(),
                    'columns'=>array(
                        array('name'=>'id'),
                        array('name'=>'name'),
                    ),
                ));

            ?>

        </div>
        <div class="span8" id="dv-grade-recursos">
            <?php 
                
                $this->widget('bootstrap.widgets.TbGridView', array(
                    'id'=>'grid-resource',   
                    'type'=>'striped condensed', 
                    'enableSorting'=>false,
                    'template'=>'{items}',
                    'rowCssClassExpression'=> 'Resource::model()->check_resource_from_profile(' . $profile_id .' , $data->id) ? \'success\' : \'\'' ,
                    'dataProvider'=> Resource::model()->search(),
                    'columns'=>array(
                        array('name'=>'id'),
                        array('name'=>'description'),
                        array('name'=>'url'),
                        array('name'=>'private', 'value'=>'$data->private ? "Não" : "Sim"'),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{add}{rem}{make-public}',
                            'buttons'=>array(
                            'add'=>array(
                                'label'=>'',
                                'options'=>array(
                                    'class'=>'icon-ok',
                                    'rel'=>'tooltip',
                                    'title'=>'Adicionar',
                                    'onClick'=>'(function(t){
                                        var resource_id = $(t).parent().parent().children(":first-child").text();
                                        var profile_id    =  '. $profile_id  .';
                                        
                                        if (profile_id == ""){
                                            alert("Selecione um perfil de usuário a sua esquerda!")
                                            return false;
                                        }

                                        $.ajax({ 
                                            type: "POST", 
                                            data: {profile_id: profile_id, resource_id: resource_id}, 
                                            url: "'.CController::createUrl("grantResource").'", 
                                            success: function(e){ 
                                                $.fn.yiiGridView.update("grid-resource");
                                            }, 
                                            error : function(e){ 
                                                $("#dv-msg").html(e.responseText).show(); 
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
                                    'title'=>'Remover',
                                    'onClick'=>'(function(t){
                                        var resource_id = $(t).parent().parent().children(":first-child").text();
                                        var profile_id    =  '. $profile_id  .';
                                        
                                        if (profile_id == ""){
                                            alert("Selecione um usuário!")
                                            return false;
                                        }

                                        $.ajax({ 
                                            type: "POST", 
                                            data: {profile_id: profile_id, resource_id: resource_id}, 
                                            url: "'.CController::createUrl("revokeResource").'", 
                                            success: function(e){ 
                                                $.fn.yiiGridView.update("grid-resource");
                                            }, 
                                            error : function(e){ 
                                                $("#dv-msg").html(e.responseText).show(); 
                                            } 
                                        }); 
                                    })(this)',
                                ),
                            ),
                            'make-public'=>array(
                                'label'=>'',
                                'options'=>array(
                                    'class'=>'icon-thumbs-up',
                                    'rel'=>'tooltip',
                                    'title'=>'Tornar público',
                                    'onClick'=>'(function(t){
                                        var resource_id = $(t).parent().parent().children(":first-child").text();
                                        
                                        $.ajax({ 
                                            type: "POST", 
                                            data: {resource_id: resource_id}, 
                                            url: "'.CController::createUrl("makeResourcePublic").'", 
                                            success: function(e){ 
                                                $.fn.yiiGridView.update("grid-resource");
                                            }, 
                                            error : function(e){ 
                                                $("#dv-msg").html(e.responseText).show(); 
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
        </div>
    </div>
</div>

<?php $this->renderPartial('_profile_form', array('model' => $model)); ?>
<?php $this->renderPartial('_progress_dialog', array()); ?>
<?php $this->renderPartial('_profile_associate', array('model'=>$model,'user_name'=>  isset($user_name) ? $user_name : null ,'data_provider'=>$data_provider, 'user_id'=> isset($user_id) ? $user_id : 0 )); ?>

<script>
    (function($) {

        var ConfigAccess = {
            profile_id: '',
            row_profile: $('#index-grid-profile tbody tr'),
            check_resource: $('.checkbox-recurso'),
            btn_remove_profile: $('#btn-remove-profile'),
            btn_edit_profile : $('.btn-edit-profile'),
            grid_resources : $('#dv-grade-recursos'),
            btn_profile_associate : $('#btn-profile-associate'),
            user_id : $('#user_id'),

            onclick_row_profile: function() {
                ConfigAccess.profile_id = $(this).children(":first-child").text();
                window.location = '/siscacex/usercontrol/default/index/profile_id/' + ConfigAccess.profile_id;
            },

            dblclick_row_profile: function(){
                ConfigAccess.profile_id = $(this).children(":first-child").text();
                window.location = "/siscacex/usercontrol/default/updateProfile/id/" + ConfigAccess.profile_id;
            },      

            onchange_userid: function()
            {
                var user_id = ConfigAccess.user_id.val();
                window.location = '/siscacex/usercontrol/default/index/id/' + user_id ;
            },
            onclick_remove_profile: function()
            {
                if (window.confirm('Deseja realmente excluir este item?')) {
                    ConfigAccess.ajax_remove_profile();
                }
            },
            ajax_remove_profile: function()
            {
                $.ajax({
                    type: 'get',
                    url: '<?php echo $this->createUrl('removeProfile'); ?>',
                    data: {profile_id: <?php echo isset($model->id) ? $model->id : 0 ; ?>},
                    success: function(data)
                    {
                        alert('Item Excluido com sucesso!');
                        window.location = '/siscacex/usercontrol/default/';
                    },
                    beforeSend: function() {
                        $('#progres-dialog').dialog('open');
                    },
                    error: function(data)
                    {
                        alert('Operação não finalizada, o item possui dados vinculados!');
                        $('#progres-dialog').dialog('close');
                        return false;
                    }
                });
            },       
        }

        ConfigAccess.row_profile.on('dblclick', ConfigAccess.dblclick_row_profile);
        ConfigAccess.row_profile.on('click', ConfigAccess.onclick_row_profile);
        ConfigAccess.user_id.on('change', ConfigAccess.onchange_userid);
         ConfigAccess.btn_remove_profile.on('click', ConfigAccess.onclick_remove_profile);

    })(jQuery);
</script>
