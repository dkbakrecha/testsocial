<?php
$sessionUser = $this->Session->read('Auth.User');
?>

<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'dashboard')); ?>">
                Social Tool
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <!--Left Navigation menu start-->
            <ul class="nav navbar-nav">
                <li><a href="<?php echo $this->Html->url(array('controller' => 'feed_urls', 'action' => 'index')); ?>">Add feed url</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'lists')); ?>">Add Article</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'social_account', 'action' => 'account_list')); ?>">Social Media List</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'shared_log')); ?>">Shared Log</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'tool_setting')); ?>">Management Panel</a></li>
            </ul>
            <!--Left Navigation menu end-->

            <!--Right Navigation menu start-->
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (!$this->Session->check('Auth.User.id')) {
                    ?>
                    <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>"><i class="fa fa-lock"></i> Login</a></li>
                    <?php
                } else {
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $sessionUser['name']; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit_profile')); ?>">Edit Profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>">LOGOUT</a></li>
                        </ul>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <!--Right Navigation menu end-->
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>