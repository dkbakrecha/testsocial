<footer class="container-fluid footer">
    Copyright © <?php echo date("Y"); ?>. <a href="http://classyarea.in/" target="_blank">ClassyAREA.com</a>
    <a href="#" class="pull-right scrollToTop"><i class="fa fa-chevron-up"></i></a>
</footer>

<?php
if ($_SERVER['HTTP_HOST'] == 'localhost')
{
    ?>
    <div class="clear-both">
        <?php echo $this->element('sql_dump'); ?>
    </div>
    <?php
}
?>