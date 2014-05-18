<?php 

$this->breadcrumbs=array(
    'Usuário'=>array('index'),
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

            <a href='<?php echo $this->createUrl('create') ?>' class='btn btn-primary'><i class='icon-file icon-white'></i> Novo </a>
            <hr />

            <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'id'=>'index-grid-profile',
                'enablePagination'=>true,
                'type'=>'striped condensed',
                'enableSorting'=>true,
                'filter'=>$model,
                'ajaxUpdateError'=>'function(xhr,ts,et,err){ alert(xhr.responseText); }',
                'template'=>"{items}{summary}{pager}",
                'dataProvider'=> $model->search(),
                'columns'=>array(
                    array('name'=>'id', 'htmlOptions'=>array('class'=>'span1')),
                    'first_name',
                    'last_name',
                    'email',
                    'username',
                    array(
                       'class'=>'bootstrap.widgets.TbButtonColumn',
                       'template' => '{update}{delete}{select_profile}',
                       'htmlOptions'=>array('style'=>'width: 50px'),
                       'buttons' => array(
                        'select_profile'=>array(
                            'label'=>'',
                            'options'=>array(
                                'class'=>'icon-tag',
                                'rel'=>'tooltip',
                                'title'=>'Adicionar Perfil',
                                'onclick'=>'(function(t){
                                    var id = $(t).parent().parent().children(":first-child").text();
                                    window.location = "/siscacex/usercontrol/associate/" + id; 
                                })(this)',
                            )))))));

                            ?>

