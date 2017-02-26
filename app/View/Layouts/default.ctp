<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $this->fetch('title'); ?>
        </title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=Bubblegum+Sans" rel="stylesheet">

        <!--<link href='https://fonts.googleapis.com/css?family=Roboto:500,400' rel='stylesheet' type='text/css'>-->
        <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css(array(
            'bootstrap/bootstrap.min',
            'fullcalendar.min',
            'fullcalendar.print',
            '/js/iCheck/all',
            'fontawesome/font-awesome.min',
            'site_ui',
            'dataTable_custom',
            'some_admin',
        ));

        echo $this->Html->script(array(
            'moment',
            'jquery.min',
            'bootstrap.min',
            'jquery.form',
            'jquery.validate.min',
            'fullcalendar.min',
            'lib/jquery-ui.custom.min',
            'iCheck/icheck.min',
            'admin/jquery.dataTables.min',
            'admin/dataTables.bootstrap.min',
        ));

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body>

        <?php 
        if (isset($this->request->params['admin'])) {
            echo $this->element('header_admin'); 
        }else{
            echo $this->element('header'); 
			if(empty($removeBreadcrumb)){
				echo $this->element('breadcrumb');
			}
        }
        ?>

        <div class="site-content <?php echo (!empty($content_class)) ? $content_class : ""; ?>" id="content">
            <div class="container">
                <?php
                echo $this->Session->flash();
                echo $this->fetch('content');
                ?>
            </div>
        </div>

        <?php
        if (!isset($this->request->params['admin'])) {
            echo $this->element('social');
            echo $this->element('footer');
            echo $this->element('_site_js');
        }
        //echo $this->element('sql_dump');
        ?>
    </body>
</html>
