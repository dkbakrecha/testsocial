<?php

class ClassyHelper extends AppHelper {
    /*
     * Description 
     */

    function short_description($string, $linkurl) {
        $string = strip_tags($string);

        if (strlen($string) > 250) {
            // truncate string
            $stringCut = substr($string, 0, 250);
            // make sure it ends in a word so assassinate doesn't become ass...
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '... <a href="' . $linkurl . '">Read More</a>';
        }
        return $string;
    }

    function fbLikeBox_withcount($page_url) {
        ?>
        <div class="fb-like" data-href="<?php echo $page_url; ?>" data-layout="box_count" data-action="like" data-show-faces="true" data-share="false"></div>
        <?php
    }

    function fbShareBox_withcount($page_url) {
        ?>
        <div class="fb-share-button" data-href="<?php echo $page_url; ?>" data-layout="box_count"></div>
        <?php
    }

    function fbLikeShareButton_withcount($page_url) {
        ?>
        <div class="fb-like" data-href="<?php echo $page_url; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
        <?php
    }

    function gplusLikeShareButton_withcount($page_url) {
        ?>
        <div class="g-plusone" data-annotation="inline" data-width="300"></div>
        <?php
    }

    function gplusShareBubble_withCount($page_url){
        ?>
        <div class="g-plus" data-action="share" data-annotation="vertical-bubble" data-height="60" data-href="<?php echo $page_url; ?>"></div>
        <?php
    }
}
