<div id="modal-form" class="modal hide" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="blue bigger">Novo perfíl</h4>
    </div>
    
    <?php $form = $this->beginWidget('CActiveForm', array('id' => 'frm-profile')); ?>

    <div class="modal-body overflow-visible">
        <div class="row-fluid">
            <div class="vspace"></div>
            <div class="span4">

                <div class="control-group">
                    <label for="#Perfil_id">Codigo</label>
                    <div class="controls">
                        <?php echo $form->textField($model, 'id', array('disabled' => true, 'class' => 'input-small')); ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="form-field-username">Descrição</label>

                    <div class="controls">
                        <?php echo $form->textField($model, 'name', array('class' => 'input-xlarge')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn btn-small" data-dismiss="modal" id="btn-close-modal">
            <i class="icon-remove"></i>
            Cancelar
        </button>

        <button class="btn btn-small btn-primary" id="btn-novo-perfil">
            <i class="icon-ok"></i>
            Salvar
        </button>
    </div>
    <?php $this->endWidget(); ?>
</div>


<script type="text/javascript">
    (function($) {
        var Perfil = {
            btn_novo_perfil: $('#btn-novo-perfil'),
            descricao: $('#Perfil_nome'),
            onClickNovoPerfil: function(e)
            {
                e.preventDefault();
                Perfil.ajaxNovoPerfil();
            },
            ajaxNovoPerfil: function()
            {
                $.ajax({
                    type: 'post',
                    url: '<?php echo $this->createUrl('create_perfil'); ?>',
                    data: $('#frm-perfil').serialize(),
                    error: function(data) {
                    },
                    beforeSend: function() {
                    },
                    success: function(data)
                    {
                        alert('Registro gravado com sucesso!');
                        $('#btn-close-modal').trigger('click');
                        window.location = "index";
                    }
                });
            }

        };

        Perfil.btn_novo_perfil.on('click', Perfil.onClickNovoPerfil);

    })(jQuery);
</script>