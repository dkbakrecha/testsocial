<footer id="footer">
    <?php /*
      <div class="footer-top-links">
      <div class="container">
      <ul class="menu" id="menu-another-menu">
      <li class="menu-item"><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'home')); ?>">Home</a></li>
      <!--<li class="menu-item"><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'aboutus')); ?>">About</a></li>-->
      <li class="menu-item"><a href="<?php echo $this->Html->url(array('controller' => 'test_types', 'action' => 'index')); ?>">Tests</a></li>
      <li class="menu-item"><a href="<?php echo $this->Html->url(array('controller' => 'quiz', 'action' => 'leaderboard')); ?>">Leaderboard</a></li>
      <li class="menu-item"><a href="<?php echo $this->Html->url(array('controller' => 'posts', 'action' => 'index')); ?>">Blog Posts</a></li>
      <!--<li class="menu-item"><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'contactus')); ?>">Contact</a></li>-->
      </ul>

      </div>
      </div>
     */ ?>

    <div class="footer-widgets">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-3 col-lg-12" style="padding-top: 10px;">
                        <a class="navbar-brand" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'home')); ?>">
                            <?php echo $this->Html->image('The-Most-Complete-Education-Solution.png'); ?>
                        </a>
                    </div>



                    <div class="col-lg-3 col-lg-offset-2 col-md-6">
                        <h3 class="widget-title">Contact Us</h3>
                        <div class="textwidget">
                            <p> 
                                <span class="fa fa-map-marker"> </span>
                                <b>Address: </b>
                                Jodhpur Rajasthan, INDIA
                            </p> 
                            <p> 
                                <span class="fa fa-envelope"> </span>
                                <b>Mail: </b>
                                <a href="mailto:sayhello@cupcherry.com">sayhello@cupcherry.com</a> 
                            </p> 
<!--							<p> 
                                    <span class="fa fa-phone"> </span>
                                    <b>Phone: </b>
                                    +91 94612 71720 (Whatapp Only)
                            </p>-->
                        </div>
                    </div>

                    <div class="col-lg-2 col-lg-12">
                        <h3 class="widget-title">
                            Navigation
                        </h3>
                        <div class="textwidget">
                            <ul class="side-nav">
                                <li ><a href="<?php echo $this->Html->url(array('controller' => 'notes', 'action' => 'add')); ?>">Submit a Note</a></li>
                                <li ><a href="<?php echo $this->Html->url(array('controller' => 'blogs', 'action' => 'index')); ?>">Blog</a></li>
                                <li ><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'about')); ?>">About Us</a></li>
                                <li ><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'contact')); ?>">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <h3 class="widget-title">We are social</h3>
                        <div class="textwidget">
                            <ul class="social-media">
                                <?php
                                $_url_facebook = Configure::read('Site.facebook');
                                $_url_twitter = Configure::read('Site.twitter');
                                $_url_pinterest = Configure::read('Site.pinterest');
                                $_url_linkedin = Configure::read('Site.linkedin');
                                $_url_google_plus = Configure::read('Site.google_plus');
                                $_url_youtube = Configure::read('Site.youtube');

                                if (!empty($_url_facebook)) {
                                    ?> <li><a href="<?php echo $_url_facebook; ?>" target="_BLANK" class="fa fa-facebook"></a></li> <?php
                                }

                                if (!empty($_url_twitter)) {
                                    ?> <li><a href="<?php echo $_url_twitter; ?>" target="_BLANK" class="fa fa-twitter"></a></li> <?php
                                    }

                                    if (!empty($_url_youtube)) {
                                        ?> <li><a href="<?php echo $_url_youtube; ?>" target="_BLANK" class="fa fa-youtube"></a></li> <?php
                                    }
                                    ?>
                            </ul>

                            <p class="social-media-text" style="display: none;">We can be supported here!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-info">
        <div class="container"> 
            <div class="row">
                <div class="col-lg-6">
                    <p class="copyright">
                        &copy; 2014 - <?php echo date('Y'); ?> : CUPCHERRY.COM - By 
                        <a title="ClassyArea | Your Business Helper" href="http://classyarea.in">ClassyArea.IN</a>
                    </p> 
                </div>
                <div class="col-lg-6">
                    <!--					<ul class="footer-bottom-links pull-right" id="footer-menu">
                                                                    <li class="menu-item">
                                                                            <a href="http://wedesignthemes.com/themes/dt-guru/news/">News</a>
                                                                    </li> 
                                                                    <li class="menu-item">
                                                                            <a href="http://wedesignthemes.com/themes/dt-guru/gallery/">Gallery</a>
                                                                    </li> 
                                                                    <li class="menu-item">
                                                                            <a href="http://wedesignthemes.com/themes/dt-guru/contact/">Contact</a>
                                                                    </li> 
                                                            </ul>-->
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    CupCherry.com is a private non-lawyer website, and only offers general information, not legal advices. We are not affiliated with any government agency.
                </div>
            </div>
        </div>
    </div>

</footer>