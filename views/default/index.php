<style type="text/css">
    .ui-dialog-titlebar {display: none;}
    .ui-dialog{border:none; background: none}
</style>

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
            <table class="table table-striped" id="tb-perfil">
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
                            <td><?php echo $profile->name; ?></td>
                            <td>
                                <a href="#" class="btn-remove-profile" id="<?php echo $profile->id; ?>"> <i class="icon-trash red"></i> </a>
                                &nbsp;
                                <a href="<?php echo $this->createUrl('updateProfile',array('id'=>$profile->id)) ?>" role="button" data-toggle="modal" class="btn-edit-profile" id="<?php echo $profile->id; ?>"> <i class="icon-edit blue"></i> </a>
                            </td>
                            <td style="display: none"><?php echo $profile->id; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="span8" id="dv-grade-recursos">
            <?php $this->renderPartial('_grid_resource', array()); ?>
        </div>
    </div>
</div>

<?php $this->renderPartial('_profile_form', array('model' => $model)); ?>
<?php $this->renderPartial('_progress_dialog', array()); ?>
<?php $this->renderPartial('_profile_associate', array('model'=>$model,'data_provider'=>$data_provider, 'user_id'=> isset($user_id) ? $user_id : 0 )); ?>

<script>
    (function($) {

        var ConfigAccess = {
            profile: '',
            row_profile: $('#tb-perfil tr'),
            check_resource: $('.checkbox-recurso'),
            btn_remove_profile: $('.btn-remove-profile'),
            btn_edit_profile : $('.btn-edit-profile'),
            grid_resources : $('#dv-grade-recursos'),
            btn_profile_associate : $('#btn-profile-associate'),
            user_id : $('#user_id'),

            onclick_row_profile: function() {

//                if ($(this).hasClass('success')) {
//                    var perfil_atual = $(this).children(':last-child').text().trim();
//                    $(this).removeClass('success');
//                    delete ConfiguracaoAcesso.perfil[perfil_atual];
//                } else {
                var currently_profile = $(this).children(':last-child').text().trim();
                ConfigAccess.profile = currently_profile;

                if ($(this).hasClass('success')) {
                    $(this).removeClass('success');
                    ConfigAccess.profile = '';
                    ConfigAccess.ajax_update_grid_resource();
                } else {

                    $('#tb-perfil').find('tr.success').removeClass('success');

                    $(this).addClass('success');

                    ConfigAccess.ajax_update_grid_resource();
                }
            },

            ajax_update_grid_resource: function() {
                $.ajax({
                    type: 'post',
                    url: '<?php echo $this->createUrl('updateGridResource') ?>',
                    data: {profile: ConfigAccess.profile},
                    success: function(data) {
                        $('#dv-grade-recursos').html(data);
                        $('#progres-dialog').dialog('close');
                        return false;
                    },
                    error: function(data) {
                        $('#progres-dialog').dialog('close');
                        return false;
                    },
                    beforeSend: function() {
                        $('#progres-dialog').dialog('open');
                    },
                });
            },

            onchange_checkbox_resource: function()
            {
                var resource = {};
                var cod = $(this).attr('name');
                var allow = $(this).prop('checked');
                resource[cod] = allow;
                ConfigAccess.ajax_change_resource_from_profile(resource);
            },
            //ajaxAlterarRecursoPerfil
            ajax_change_resource_from_profile: function(resource)
            {
                $.ajax({
                    type: 'post',
                    url: '<?php echo $this->createUrl('updateResource'); ?>',
                    data: {profile_id: ConfigAccess.profile, resource: resource},
                    beforeSend: function() {
                        $('#progres-dialog').dialog('open');
                    },
                    error: function() {
                        $('#progres-dialog').dialog('close');
                        return false;
                    },
                    success: function() {
                        ConfigAccess.ajax_update_grid_resource();
                        $('#progres-dialog').dialog('close');
                        return false;
                    }

                });
            },
            onclick_remove_profile: function()
            {
                ConfigAccess.profile = $(this).attr('id');
                if (window.confirm('Deseja realmente excluir este item?')) {
                    ConfigAccess.ajax_remove_profile();
                }
            },
            //ajaxApagarPerfil
            ajax_remove_profile: function()
            {
                $.ajax({
                    type: 'get',
                    url: '<?php echo $this->createUrl('removeProfile'); ?>',
                    data: {profile_id: ConfigAccess.profile},
                    success: function(data)
                    {
                        window.location = '.';
                    },
                    beforeSend: function() {
                        $('#progres-dialog').dialog('open');
                    },
                    error: function(data)
                    {
                        $('#dv-msg').html(data.responseText);
                        $('#progres-dialog').dialog('close');
                        return false;
                    }
                });
            },            
            onclick_profile_associate: function(e)
            {   
                e.preventDefault();

                var profiles =  $("#grid-profile").yiiGridView('getSelection');
                var user_id  =  $("#user_id").val();
                console.log(profiles);
                if (user_id == "")
                {
                    alert('Selecione um usuário!');
                    $('#usuario').focus();
                    return false;
                }

                if (profiles.length == 0)
                {
                    alert('Selecione um ou mais perfis!');
                    return false;
                }
               ConfigAccess.ajax_profile_associate(user_id, profiles);       
            },
            
            ajax_profile_associate: function(user_id, profiles)
            {                
                $.ajax({
                    type: 'post',
                    url: '<?php echo $this->createUrl('associateProfile'); ?>',
                    data: {user_id: user_id, profiles: profiles},
                    success: function(data)
                    {
                        window.location = '.';
                    },
                    beforeSend: function() {
                        $('#progres-dialog').dialog('open');
                    },
                    error: function(data)
                    {
                        $('#dv-msg').html(data.responseText);
                        $('#progres-dialog').dialog('close');
                        return false;
                    }
                });
            },
            onchange_userid: function()
            {
                var user_id = ConfigAccess.user_id.val();
                window.location = '.' + '/' + user_id;
            }
        }

        ConfigAccess.row_profile.on('click', ConfigAccess.onclick_row_profile);
        ConfigAccess.grid_resources.on('change', '.checkbox-recurso', ConfigAccess.onchange_checkbox_resource);
        ConfigAccess.btn_remove_profile.on('click', ConfigAccess.onclick_remove_profile);
        ConfigAccess.btn_edit_profile.on('click', ConfigAccess.onclick_edit_profile);
        ConfigAccess.btn_profile_associate.on('click', ConfigAccess.onclick_profile_associate);
        ConfigAccess.user_id.on('change', ConfigAccess.onchange_userid);

        var opts = {
            lines: 12, // The number of lines to draw
            length: 6, // The length of each line
            width: 4, // The line thickness
            radius: 9, // The radius of the inner circle
            color: '#fff', // #rbg or #rrggbb
            speed: 1.5, // Rounds per second
            trail: 50, // Afterglow percentage
            shadow: false // Whether to render a shadow
        };

        var target = document.getElementById('spin-dialog');
        var spinner = new Spinner(opts).spin(target);


    })(jQuery);
</script>
