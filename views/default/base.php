<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    
    <style type="text/css" media="screen">
        ul.breadcrumbs{ margin-top: 43px; }    
    </style>
    <!-- blueprint CSS framework -->
    <?php echo Yii::app()->bootstrap->register(); ?>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

    <div class='header'>
        <?php
        $this->widget('bootstrap.widgets.TbNavbar', array(
                'type' => 'inverse', // null or 'inverse'
                'brand' => Yii::app()->controller->module->brand,
                'brandUrl' => array('resource/index'),
                'collapse' => true, // requires bootstrap-responsive.css
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'items' => array(
                            array('label' => 'Home', 'url' => array('/site/index')),
                            array('label' => 'Cadastro', 'url' => '#', 'items' => array(
                                array('label' => 'Usuario', 'url' => $this->createUrl('/usercontrol/user/index')),
                                array('label' => 'Perfil', 'url' => $this->createUrl('/usercontrol/profile/index')),
                                )),
                            array('label'=>'Logout' , 'url'=>array('/site/logout')),
                            )))));
                            ?>

    </div>

    <?php if (isset($this->breadcrumbs)): ?>

    <?php
        $this->widget('bootstrap.widgets.TbBreadcrumbs', array(//zii.widgets.CBreadcrumbs
            'links' => $this->breadcrumbs,
            'homeLink'=>CHtml::link('Home', array('resource/index'))
        ));
        ?><!-- breadcrumbs -->

    <?php endif ?>        
    
    <div class='container'>
        <div class="row">
            <div  class='span12'>
                <?php echo $content; ?>
            </div>
        </div>
    </div>
</body>
</html>