<div class="page-content">

    <div class="page-header position-relative">
        <h1>
            Configuração de Acesso
        </h1>
        <i class="icon-paste green"></i><a href="#modal-form" role="button" class="blue" data-toggle="modal"> Novo Perfil </a>
        <br />
    </div><!--/.page-header-->
    
    <div id="dv-msg" class="span12"></div>

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
                            <td><?php echo $perfil->name; ?></td>
                            <td>
                                <a href="#" class="btn-apagar-perfil" id="<?php echo $perfil->id; ?>"> <i class="icon-trash red"></i> </a>
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

<script>
    (function($) {

        var ConfiguracaoAcesso = {
            perfil: '',
            tb_perfil_linha: $('#tb-perfil tr'),
            check_recurso: $('.checkbox-recurso'),
            btn_apagar_perfil: $('.btn-apagar-perfil'),
            onClickLinhaPerfil: function() {

//                if ($(this).hasClass('success')) {
//                    var perfil_atual = $(this).children(':last-child').text().trim();
//                    $(this).removeClass('success');
//                    delete ConfiguracaoAcesso.perfil[perfil_atual];
//                } else {
                var perfil_atual = $(this).children(':last-child').text().trim();
                ConfiguracaoAcesso.perfil = perfil_atual;
                if ($(this).hasClass('success')) {
                    $(this).removeClass('success');
                    ConfiguracaoAcesso.perfil = '';
                    ConfiguracaoAcesso.ajaxAtualizaGradeRecurso();
                } else {

                    $('#tb-perfil').find('tr.success').removeClass('success');

                    $(this).addClass('success');

                    ConfiguracaoAcesso.ajaxAtualizaGradeRecurso();
                }
            },
            ajaxAtualizaGradeRecurso: function() {
                $.ajax({
                    type: 'post',
                    url: '<?php echo $this->createUrl('atualizaGradeRecurso') ?>',
                    data: {perfil: ConfiguracaoAcesso.perfil},
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
            onChangeCheckBoxRecurso: function()
            {
                var recurso = {};
                var codigo = $(this).attr('name');
                var permitir = $(this).prop('checked');
                recurso[codigo] = permitir;
                ConfiguracaoAcesso.ajaxAlterarRecursoPerfil(recurso);
            },
            ajaxAlterarRecursoPerfil: function(recurso)
            {
                $.ajax({
                    type: 'post',
                    url: '<?php echo $this->createUrl('permitirRecurso'); ?>',
                    data: {perfil: ConfiguracaoAcesso.perfil, recurso: recurso},
                    beforeSend: function() {
                        $('#progres-dialog').dialog('open');
                    },
                    error: function() {
                        $('#progres-dialog').dialog('close');
                        return false;
                    },
                    success: function() {
                        ConfiguracaoAcesso.ajaxAtualizaGradeRecurso();
                        $('#progres-dialog').dialog('close');
                        return false;
                    }

                });
            },
            onClickApagarPerfil: function()
            {
                ConfiguracaoAcesso.perfil = $(this).attr('id');
                if (window.confirm('Deseja realmente excluir este item?')) {
                    ConfiguracaoAcesso.ajaxApagarPerfil();
                }
            },
            ajaxApagarPerfil: function()
            {
                $.ajax({
                    type: 'get',
                    url: '<?php echo $this->createUrl('apagarItem'); ?>',
                    data: {perfil: ConfiguracaoAcesso.perfil},
                    success: function(data)
                    {
                        window.location = 'index';
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
            }
        }

        ConfiguracaoAcesso.tb_perfil_linha.on('click', ConfiguracaoAcesso.onClickLinhaPerfil);
        $('#dv-grade-recursos').on('change', '.checkbox-recurso', ConfiguracaoAcesso.onChangeCheckBoxRecurso);
        ConfiguracaoAcesso.btn_apagar_perfil.on('click', ConfiguracaoAcesso.onClickApagarPerfil);

    })(jQuery);
</script>