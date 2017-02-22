<style>
    .m-bottom2 {
        margin-bottom: 20px;
    }
    .m-top4 {
        margin-top: 40px;
    }
</style>

<div class="row">
    <div class="col-lg-8">
        <?php
        if (!empty($all_posts)) {
            foreach ($all_posts as $postData) {
                ?>
                <div class="post-item">

                    <?php $_linkUrl = $this->Html->url(array('controller' => 'posts', 'action' => 'view', $postData['Post']['title_slug'])); ?>
                    <div class="col-lg-4">
                        <?php if (!empty($postData['Post']['cover_image'])) {
                            ?>
                        <div class="row"><?php echo $this->html->image('/files/images/' . $postData['Post']['cover_image'], array('class' => 'img-responsive')); ?></div>
                        <?php }else{
                            ?>
                                <div class="row"><?php echo $this->html->image('/files/images/post_default.png', array('class' => 'img-responsive')); ?></div>
                                <?php
                        }
                        ?>
                    </div>
                    <div class="col-lg-8">
                        <a href="<?php echo $_linkUrl; ?>">
                            <h1 class=""><?php echo $postData['Post']['title']; ?></h1>
                        </a>
                        <div class="post-content">
                        <?php
                        echo $this->Classy->short_description($postData['Post']['content'], $_linkUrl);
                        ?>
                        </div>
                        <div class="post-info m-top2 m-bottom5">
                            <i class="fa fa-user"></i> <a href="#">By <?php echo $postData['User']['name']; ?></a> on <?php echo date(Configure::read('Site.front_date_format'), strtotime($postData['Post']['created'])) ?> 
                            <!--<span class="pull-right"><i class="fa fa-comments"></i> <a href="#">2456</a> &nbsp;/&nbsp; <a href="#">Business</a> - <a href="#">UX</a> - <a href="#">Web Design</a> - <a href="#">UI</a> - <a href="#">Social Media</a></span>--> 
                        </div>
                    </div>

                </div>
                <?php
            }
        }
        ?>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $this->Paginator->counter(); ?>
            </div>
            <div class="col-lg-6">
                <div class=" pull-right">
                    <?php
                    echo $this->Paginator->numbers(array(
                        'before' => '<ul class="pagination">',
                        'separator' => '',
                        'currentTag' => 'a',
                        'currentClass' => 'active',
                        'tag' => 'li',
                        'after' => '</ul>'
                    ));
                    ?>
                </div>
            </div>
        </div>	
    </div>
    <div class="col-lg-4">
        <!--<div class="fb-page" data-href="https://www.facebook.com/cupcherry" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/cupcherry"><a href="https://www.facebook.com/cupcherry">Cupcherry</a></blockquote></div></div>-->
    </div>
</div>