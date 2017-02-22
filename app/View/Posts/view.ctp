<style>
    .m-bottom2 {
        margin-bottom: 20px;
    }
    .m-top4 {
        margin-top: 40px;
    }

    .post-info {
        margin: 0 0 20px 0;
    }
</style>

<!--<meta property="og:url"                content="http://www.nytimes.com/2015/02/19/arts/international/when-great-minds-dont-think-alike.html" />-->
<!--<meta property="og:type"               content="article" />-->
<meta property="og:title"              content="CUPCHERRY : <?php echo $postDetail['Post']['title']; ?>" />
<!--<meta property="og:description"        content="How much does culture influence creative thinking?" />-->
<!--<meta property="og:image"              content="http://static01.nyt.com/images/2015/02/19/arts/international/19iht-btnumbers19A/19iht-btnumbers19A-facebookJumbo-v2.jpg" />-->

<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <?php
        if (!empty($postDetail)) {
            //pr($postDetail);
            ?>
            <div class="post-detail"> 
                <div class='post-header'>
                    <h1 class=""><?php echo $postDetail['Post']['title']; ?></h1>
                    <div class="cover-image">
                        <?php if (!empty($postDetail['Post']['cover_image'])) {
                            ?>
                            <?php echo $this->Html->image('/files/images/' . $postDetail['Post']['cover_image'], array('class' => 'img-responsive')); ?>
                        <?php } else {
                            ?>
                            <?php echo $this->Html->image('/files/images/post_default.png', array('class' => 'img-responsive')); ?>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="post-info">
                    <i class="fa fa-user"></i> <a href="#">By <?php echo $postDetail['User']['name']; ?></a> on <?php echo date(Configure::read('Site.front_date_format'), strtotime($postDetail['Post']['created'])) ?> 
                    <!--<span class="pull-right"><i class="fa fa-comments"></i> <a href="#">2456</a> &nbsp;/&nbsp; <a href="#">Business</a> - <a href="#">UX</a> - <a href="#">Web Design</a> - <a href="#">UI</a> - <a href="#">Social Media</a></span>--> 
                </div>
                <div class="post-content">
                    <?php
                    echo $postDetail['Post']['content'];
                    ?>
                </div>
                <div class="social_sharing">
                    <?php
                    $_linkUrl = $this->Html->url(array('controller' => 'posts', 'action' => 'view', $postDetail['Post']['title_slug']), TRUE);
                    //$this->Classy->fbLikeBox_withcount($_linkUrl);
                    ?>
                        
                    <span class="sharing-block"><?php $this->Classy->fbShareBox_withcount($_linkUrl); ?></span>
                    <span class="sharing-block"><?php $this->Classy->gplusShareBubble_withCount($_linkUrl); ?></span>
                        
                    <?php
                    //$this->Classy->fbLikeShareButton_withcount($_linkUrl);
                    //$this->Classy->gplusLikeShareButton_withcount($_linkUrl);
                    
                    ?>
                </div>
            </div>
            <?php
        }
        ?> 	
    </div>

    <div class="">
        <!--<div class="fb-page" data-href="https://www.facebook.com/cupcherry" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/cupcherry"><a href="https://www.facebook.com/cupcherry">Cupcherry</a></blockquote></div></div>-->
    </div>
</div>

<script>
    $(function () {
        var blog_id = '<?php echo $postDetail['Post']['id']; ?>';
        makeCount(blog_id);
    });

    /*
     MAKE Count use for hit calculation on particular grid
     to create sort by popularity feature.
     Dharmendra Bagrecha
     * @param {int} post id
     * @returns {json} success or error message
     *  */
    function makeCount(id) {
        $.ajax({
            url: '<?php echo $this->Html->url(array("controller" => "posts", "action" => "hitview")) ?>',
            type: 'POST',
            data: {id: id},
            success: function (data) {
                try {
                    //console.log(data);
                    var pd = $.parseJSON(data);
                    if (pd.status == 0) {
                        //alert(pd.msg);
                    }
                } catch (e) {
                    window.console && console.log(e);
                }
            },
            error: function (xhr) {
                console.log(xhr);
                //ajaxErrorCallback(xhr);
            }
        });
    }
</script>