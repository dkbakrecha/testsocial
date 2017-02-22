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

            <a class="navbar-brand" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'home')); ?>">
                <?php echo $this->Html->image('The-Most-Complete-Education-Solution.png'); ?>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                if (isset($this->request->params['admin'])) {
                    ?>
                    <li ><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'questions', 'action' => 'index')); ?>"><i class="fa fa-hdd-o"></i> Questions</a></li>
                    <li ><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'tests', 'action' => 'index')); ?>"><i class="fa fa-graduation-cap"></i> Tests</a></li>
                    <li ><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'notes', 'action' => 'index')); ?>"><i class="fa fa-files-o"></i> Notes</a></li>
                    <li class="active"><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'users', 'action' => 'index')); ?>"><i class="fa fa-user"></i> Users</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cogs"></i> Manage CMS <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li ><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'categories', 'action' => 'index')); ?>">Categories</a></li>
                            <li><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'testimonials', 'action' => 'index')); ?>">Testimonials</a></li>
                            <li><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'posts', 'action' => 'index')); ?>">Blogs</a></li>
                            <li><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'media', 'action' => 'index')); ?>">Media</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'cms_pages', 'action' => 'index')); ?>">Site Content</a></li>
                            <li><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'faqs', 'action' => 'index')); ?>">FAQ'S Content</a></li>
                            <li><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'email_contents', 'action' => 'index')); ?>">Email Content</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'users', 'action' => 'setting')); ?>">Site Setting</a></li>
                        </ul>
                    </li>
                    <?php
                } else {
                    if (!empty($sessionUser['name'])) {
                        ?>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index')); ?>"><i class="fa fa-calculator"></i> Practice Tests</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index')); ?>"><i class="fa fa-gamepad"></i> Moke Tests</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index')); ?>"><i class="fa fa-graduation-cap"></i> Leaderboard Tests</a></li>

                        <li><a href="<?php echo $this->Html->url(array('controller' => 'questions', 'action' => 'index')); ?>"><i class="fa fa-hdd-o"></i> Questions</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'notes', 'action' => 'index')); ?>"><i class="fa fa-files-o"></i> Notes</a></li>
                        <?php
                    } else {
                        ?>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'aboutus')); ?>"><i class="fa fa-comments-o"></i> About Us</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'features')); ?>"><i class="fa fa-comments-o"></i> Features</a></li>
                        <!--<li><a href="<?php //echo $this->Html->url(array('controller' => 'testimonials', 'action' => 'index'));    ?>"><i class="fa fa-comments-o"></i> Testimonials</a></li>-->
                        <!--<li><a href="<?php //echo $this->Html->url(array('controller' => 'faqs', 'action' => 'index'));    ?>"><i class="fa fa-list"></i> FAQ'S</a></li>-->
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'posts', 'action' => 'index')); ?>"><i class="fa fa-files-o"></i> Blog</a></li>

                        <?php
                    }
                }
                ?>


            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isset($this->request->params['admin'])) {
                    
                } else {
                    if (empty($sessionUser['name'])) {
                        ?>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>">Login</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'register')); ?>">New Profile</a></li>
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