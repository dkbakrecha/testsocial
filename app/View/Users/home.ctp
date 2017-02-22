<?php
echo $this->Html->css('/js/owl-carousel/owl.carousel');
echo $this->Html->script('owl-carousel/owl.carousel.min');
?>

<style>
    .home_section
    {
        background-attachment: fixed;
        background-image: url("<?php echo $this->webroot; ?>img/slider-bg.jpg");
        background-position: 50% 0;
        background-repeat: no-repeat;
        background-size: cover;
        height: 450px;
    }

    
</style>

<div class="home_section">
    <div class="container">
        <div class="intro-text type1"> 
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <!--
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>
                -->
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <h4>Helping you to achieve your aims & dreams in future</h4> 
                        <h5>One of the best E-learning platform available.</h5> 
                    </div>

                    <div class="item">
                        <h4>Online Learning made Easy</h4> 
                        <h5>Non stop learning whenever you want wherever you want.</h5> 
                    </div>

                    <div class="item">
                        <h4>The Best questions like Elsewhere. Come get trained.</h4> 
                        <h5>You’ll get what you came for!</h5> 
                    </div>


                </div>

                <!-- Left and right controls -->
                <!--
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a> 
                -->
            </div>

            <?php if (empty($LoggedinUser)) { ?>
                <a class="btn btn-primary btn-home-slider btn-lg register_open" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>" id="">Login</a>
                <a class="btn btn-primary btn-home-slider btn-lg register_open" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'register')); ?>" id="">New Profile</a>
                            <!--<a class="dt-sc-button small " href="<?php //echo $this->Html->url(array('controller' => 'pages', 'action' => 'about'));                   ?>">Read More</a>-->
            <?php } ?>
        </div>
    </div>
</div>






<div class="category-section">
    <div class="container">
        <h3 class="sub-title be-center">Category List</h3>
        <div class="section-description">The content matter is divided into many different categories and their sub categories based on the pattern of the examination and latest competitive tests.</div>
        <?php
        foreach ($cateList as $category) {
            ?>
            <div class="col-lg-3 col-md-6">
                <h3 class="category-title"><?php echo $category['Category']['title']; ?></h3>
                <?php
                $i = 1;
                foreach ($category['SubCategories'] as $subCate) {
                    ?>
                    <h4 class="subcategory-title"> 
                        <?php
                        echo $subCate['title'];
                        if ($i == 4) {
                            echo "...";
                        }
                        ?> </h4>
                    <?php
                    $i++;
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>    
</div>

<div class="recent-blogs" >
    <div class="container">
        <h3 class="sub-title be-center">Recent Posts</h3>
        <div class="section-description">Blog are for user to spread their thoughts and collection of matter to users and updates for currents news.</div>
        <div id="resentPost">
            <?php
            foreach ($blogList as $blogpost) {
                ?>

                <div class="card">
                    <a href="<?php echo $this->Html->url(array('controller' => 'posts', 'action' => 'view', $blogpost['Post']['title_slug'])); ?>">
                        <?php
                        if (!empty($blogpost['Post']['cover_image'])) {
                            echo $this->Html->image('/files/images/' . $blogpost['Post']['cover_image'], array('class' => 'img-responsive'));
                        } else {
                            echo $this->Html->image('/files/images/post_default.png', array('class' => 'img-responsive'));
                        }
                        ?>
                        <div class="cardcontainer">
                            <p><?php echo $blogpost['Post']['title']; ?></p>
                            <div class="post-author">
                                By <?php echo $blogpost['User']['name']; ?>
                            </div>
                        </div>
                    </a>
                </div>

                <?php
            }
            ?>
        </div>
    </div>
</div>