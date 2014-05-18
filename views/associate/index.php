<?php 

$this->breadcrumbs=array(
    'Usuário'=>array('user/index'),
    'Index',
    );
?>
        
<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true, // display a larger alert block?
    'fade'=>true, // use transitions?
    'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
    'alerts'=>array( // configurations per alert type
        'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    )); 
?>
<h3> <?php echo isset($user) ? 'Usuário: ' .  ucfirst( $user->getFullName() )   : 'Perfis de Acesso' ; ?> </h3>
    
    <?php  echo CHtml::hiddenField('user_id', $user->id);  ?>
            
    <hr />

        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'grid',
        'enablePagination'=>true,
        'type'=>'striped condensed', 
        'enableSorting'=>false,
        'template'=>'{items}',
        'rowCssClassExpression'=> 'Profile::model()->check_profile_user('   . $_GET['id']  .' , $data->id) ? \'success\' : \'warning\'' ,
        'dataProvider'=> $model->search(),
        
        'columns'=>array(
            array('name'=>'id','htmlOptions'=>array('class'=>'span1')),
            array('name'=>'name', 'header'=>'Perfil'),
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
                                        $.fn.yiiGridView.update("grid"); 
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
                            'title'=>'Remover',
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
                                        $.fn.yiiGridView.update("grid"); 
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
