<?php

App::uses('AppModel', 'Model');

class FeedUrl extends AppModel {

    public $validate = array(
        'rss_url' => array(
            'validateRssUrl' => array(
                'rule' => 'validateRssUrl',
                'message' => 'This is not a vaild rss url'
            ),
        )
    );

    //validate rss feed url
    public function validateRssUrl(){
        if(isset($this->data[$this->alias]['rss_url']) && !empty($this->data[$this->alias]['rss_url'])){
            $feedUrl = $this->data[$this->alias]['rss_url'];

            if (!filter_var($feedUrl, FILTER_VALIDATE_URL) === false) {
                try { 
                    $content = @file_get_contents($this->data[$this->alias]['rss_url']); 
                    $rss = new SimpleXmlElement($content);

                    if(isset($rss->channel->item) && $rss->channel->item->count() > 0){
                        return true;
                    }
                }catch(Exception $e){ 
                    /* the data provided is not valid XML */ 
                    return false;
                }

                return false;
            }
        }
    }

}