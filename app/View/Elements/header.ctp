<?php
$sessionUser = $this->Session->read('Auth.User');
?>

<style>
    .breadcrumb-wrapper {
        background-attachment: fixed;
        background-image: url("<?php echo $this->webroot; ?>img/slider-bg.jpg");
        background-position: 50% 0;
        background-repeat: no-repeat;
        background-size: cover;
        /*height: 450px;*/
    }
</style>

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
                Social
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="<?php echo $this->Html->url(array('controller' => 'feed_urls', 'action' => 'index')); ?>">Add feed url</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'lists')); ?>">Add Article</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'social_account', 'action' => 'account_list')); ?>">Social Media List</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'shared_log')); ?>">Shared Log</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'tool_setting')); ?>">Management Panel</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isset($this->request->params['admin'])) {
                    
                } else {
                    if (empty($sessionUser['name'])) {
                        ?>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>"><i class="fa fa-lock"></i> Login</a></li>
                        <?php
                    } else {
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $sessionUser['name']; ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit_profile')); ?>">Edit Profile</a></li>
                                <li><a href="#">Account Settings</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>">LOGOUT</a></li>
                            </ul>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>