<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'progres-dialog',
            // additional javascript options for the dialog plugin
    'options' => array(
        'modal' => true,
        'tittle' => false,
        'autoOpen' => false,
        'height' => 'auto',
        'width' => 'auto',
        'buttons' => false,
        'resizable' => false
        ),
    ));
    ?>
    <div class="span4" style="position: relative; height: 40px"  id="spin-dialog">
    </div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>