
<?php if (!isset($profile)): ?>

    <table class="table table-striped" id="tb-recurso">
        <thead>
        <th>Descrição</th>
        <th>Link de acesso</th>
        <th>Publico</th>
        <th>Permitir</th>
    </thead>
    <tbody>
        <?php foreach (Resource::model()->findAll() as $resource): ?>
            <tr>
                <td><?php echo $resource->description; ?></td>
                <td><?php echo $resource->url; ?></td>
                <td>
                    <?php

                    echo CHtml::link(!empty(!$resource->private) ? '<label class="label label-success">Sim</label>' : '<label class="label label-warning">Não</label>', '#', array('class' => 'btn-private'));
                    ?> 
                </td>
                <td style="column-span2" class="span2"> 
                    <label>
                        <input type="checkbox" name='<?php echo $resource->id; ?>' <?php echo !empty($resource->public) ? 'checked' : null; ?> class="ace checkbox-recurso"><span class="lbl"></span>
                    </label> 
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>

<?php else: ?>

    <table class="table table-striped" id="tb-recurso">
        <thead>
        <th>Descrição</th>
        <th>Link de acesso</th>
        <th>Publico</th>
        <th>Permitir</th>
    </thead>
    <tbody>
        <?php foreach (Resource::model()->findAll() as $resource): ?>
            <tr>
                <td><?php echo $resource->description; ?></td>
                <td><?php echo $resource->url; ?></td>
                <td>
                    <?php
                     echo CHtml::link(!empty(!$resource->private) ? '<label class="label label-success">Sim</label>' : '<label class="label label-warning">Não</label>', '#', array('class' => 'btn-private'));
                    ?> 
                </td>
                <td style="column-span:2" class="span2"> 
                    <label>
                        <input type="checkbox" name='<?php echo $resource->id; ?>' <?php echo Resource::model()->check_resource_from_profile($profile->id, $resource->id) === true ? 'checked' : null; ?> class="ace checkbox-recurso"><span class="lbl"></span>
                    </label> 
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>


<?php endif; ?>