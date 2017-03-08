<?php

App::uses('AppController', 'Controller');

class CornsController extends AppController {

    protected $social_network_key = array('twitter' => 1, 'linkedin' => 2, 'facebook' => 3, 'google' => 4);
    /*Variable used for processing article start*/

    protected $feedWiseNetworkInfo = array(); //feed wise network information
    protected $articleFormattedData = array(); //article formatted data network wise

    /*Variable used for processing article end*/

    public function updatefeed() {
        $this->loadModel('FeedUrl');

        $feedurls = $this->FeedUrl->find('all', array(
            'conditions' => array(
                'FeedUrl.status' => 1
            )
                ));

        //pr($feedurls);

        if (!empty($feedurls)) {
            foreach ($feedurls as $url) {
                $feedInfo = $this->__getFeed($url['FeedUrl']['rss_url']);
                $this->update_articles($feedInfo, $url['FeedUrl']['id']);
            }
        }

        exit;
    }

    function __getFeed($feed_url) {
        $curl = curl_init();
        curl_setopt_array($curl, Array(
            CURLOPT_URL => $feed_url, //'http://blogs.guggenheim.org/map/feed/',
            CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
            CURLOPT_TIMEOUT => 120,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));


        $content = curl_exec($curl);

        curl_close($curl);
        //prd($content);
        $x = new SimpleXmlElement($content);
        return $x;
    }

    public function update_articles($feedInfo, $feed_id) {
        $this->loadModel('Article');
        if (!empty($feedInfo)) {
            foreach ($feedInfo->channel->item as $entry) {

                $isDuplicate = false;
                //check article exist by guid
                if(isset($entry->guid) && !empty($entry->guid)){
                    $conditions = array('guid' => trim($entry->guid));
                    $article_count = $this->Article->find('count',array(
                            'conditions' => $conditions,
                        )
                    );

                    if($article_count > 0){
                        $isDuplicate = true;
                    }
                }

                if(!$isDuplicate){
                    $article = array();
                    $article['Article']['feed_id'] = $feed_id;
                    $article['Article']['title'] = $entry->title;
                    $article['Article']['description'] = $entry->description;
                    $article['Article']['link'] = $entry->link;
                    $article['Article']['image'] = '';
                    $article['Article']['guid'] = $entry->guid;
                    $article['Article']['pub_date'] = $entry->pubDate;
                    $article['Article']['status'] = 1;
                    $article['Article']['created'] = date('Y-m-d H:i:s');
                    $article['Article']['updated'] = date('Y-m-d H:i:s');
                    //pr($article);
                    $this->Article->create();
                    $this->Article->save($article);
                }
            }
        }
    }

    /**
     * Share articles on social media
     * @param $social_type string [ Twitter, LinkedIn, Facebook, Google]
    **/
    public function share($social_type){

        $fb = Configure::read('Facebook');
        //pr($fb);
        $access_token = 'EAAZAViLxqhkQBAFrs2kEnBea3WgFIpswsERrxbujhMwTbNyY7uS4yYZBBtBAd2J5OmlTfwrcTkP3ksQJXQ45SM6RKKTpm2QrUvhwwtMnlJu0uqodHsiraEzvohlwwPfNywjZCmHQS78badbfIT61R8xUguNDD7lOGZCJ4OufeQZDZD';
        
        $page_access_token = 'https://graph.facebook.com/me/accounts?access_token='.$access_token;

        $publish_post = 'https://graph.facebook.com/v2.8/me/feed?access_token='.$access_token;
    

        $postParams = array(
            'message' => 'This is dummy post text',
            //'privacy' => array('value' => 'EVERYONE'),
        );

        $request_config = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        );

        $response = $this->curlHttpRequest($publish_post, 'POST', $postParams, $request_config);

        if(isset($response)){
            $postData = json_decode($response['data'], true);

            $post_id = $postData['id'];

            $read_post = 'https://graph.facebook.com/v2.8/'.$post_id.'?access_token='.$access_token;
            $getParams = array();
            $request_config = array(
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
            );

            $response1 = $this->curlHttpRequest($read_post, 'GET', $getParams, $request_config);            
            prd($response1);
        }
        pr($response1);
        //pr($url);
        prd('hold');

        $this->loadModel('Article');
        $this->loadModel('SocialAccount');
        $social_network = array('twitter' => 1, 'linkedin' => 2, 'facebook' => 3, 'google' => 4);

        $socialAccountData = $this->SocialAccount->find('all', array(
                'conditions' => array(
                    'social_type' => $social_network[$social_type],
                    'status' => 1,
                )
            )
        );

        prd($socialAccountData);

    }

    public function processArticle($social_type){
        $this->loadModel('Article');
        $this->loadModel('SocialAccount');
        $this->loadModel('SharedLog');
        $this->loadModel('ToolSetting');

        $social_type = strtolower($social_type);

        $toolSetting = $this->ToolSetting->findById(1);

        if(isset($toolSetting['ToolSetting']['setting_value']) && !empty($toolSetting['ToolSetting']['setting_value'])){
            $limit = $toolSetting['ToolSetting']['setting_value'];
        }else{
            $limit = 2;
        }

        $articleDataList = $this->Article->find('all', array(
                'conditions' => array(
                    'share_status' => 0,
                    //'feed_id' => 2,
                ),
                'limit' => $limit,
            )
        );
        //prd($articleDataList);
        if(!empty($articleDataList)){

            //set article data feed wise
            foreach ($articleDataList as $article) {
                $feed_id = $article['Article']['feed_id'];
                
                if(isset($this->feedWiseNetworkInfo[$feed_id]) && !empty($this->feedWiseNetworkInfo[$feed_id])){
                    $this->feedWiseNetworkInfo[$feed_id]['article'][] = $article;
                }else{
                    
                    $account_available = $this->SocialAccount->find('count',array(
                            'conditions' => array(
                                'rss_feed_url' => $feed_id,
                                'social_type' => $this->social_network_key[$social_type],
                            )
                        )
                    );

                    $this->feedWiseNetworkInfo[$feed_id][$social_type] = $account_available;
                    $this->feedWiseNetworkInfo[$feed_id]['article'][] = $article;

                }
            }
            //prd($this->feedWiseNetworkInfo);
            //check feedwise article data exist or not
            if(!empty($this->feedWiseNetworkInfo)){
                //pr($this->feedWiseNetworkInfo);
                foreach ($this->feedWiseNetworkInfo as $feed_id => $feed_data) {
                    //prd($feed_data);
                    //check social network availabilty is greater than 0
                    if($feed_data[$social_type] > 0 && !empty($feed_data['article'])){

                        //list of available social account
                        $SocialAccountList = $this->SocialAccount->find('all', array(
                                'conditions' => array(
                                    'rss_feed_url' => $feed_id,
                                    'social_type' => $this->social_network_key[$social_type],
                                    'status' => 1,
                                ),
                                'fields' => array('id', 'name', 'social_unique_id','access_token', 'other_extra_info'),
                            )
                        );
                        //prd($SocialAccountList);
                        pr($feed_data);
                        //format article and share data
                        foreach ($feed_data['article'] as $article) {
                            pr($article);
                            pr($SocialAccountList);
                            $share_data = $this->formatArticleData($social_type, $article['Article']);
                            foreach ($SocialAccountList as $socialAccount) {
                                //prd($socialAccount);
                                if($social_type == 'twitter' || $social_type == 'linkedin'){
                                    $access_token = json_decode($socialAccount['SocialAccount']['access_token'], true);
                                }else{
                                    $access_token = $socialAccount['SocialAccount']['access_token'];
                                }

                                if($social_type == 'twitter'){
                                    $response = $this->twitter_share($access_token,$share_data);
                                }elseif ($social_type == 'linkedin') {
                                    $response = $this->linkedin_share($access_token,$share_data);
                                }elseif ($social_type == 'facebook'){
                                    $response = $this->fb_post_share($access_token,$share_data);
                                    $response_1 = $this->fb_page_post_share($access_token,$share_data, $socialAccount['SocialAccount']['other_extra_info']);

                                    pr($response);
                                    pr($response_1);
                                }


                                if(isset($response)){

                                    if($social_type == 'twitter' || $social_type == 'facebook'){
                                        $responseDecode = json_decode($response, true);
                                    }elseif ($social_type == 'linkedin') {
                                        $responseDecode = $response;
                                    }

                                    if($social_type == 'twitter'){
                                        if(isset($responseDecode['created_at']) && !empty($responseDecode['created_at'])){
                                            $saveShareLog['SharedLog']['share_status'] = 1;
                                            $saveShareLog['SharedLog']['response'] = json_encode($response);
                                        }else{
                                            $saveShareLog['SharedLog']['share_status'] = 3;
                                            $saveShareLog['SharedLog']['response'] = json_encode($response);
                                        }
                                    }elseif($social_type == 'facebook'){
                                        if(isset($responseDecode['id']) && !empty($responseDecode['id'])){
                                            $saveShareLog['SharedLog']['share_status'] = 1;
                                            $saveShareLog['SharedLog']['response'] = json_encode($response);
                                        }else{
                                            $saveShareLog['SharedLog']['share_status'] = 3;
                                            $saveShareLog['SharedLog']['response'] = json_encode($response);
                                        }
                                    }

                                    $saveShareLog['SharedLog']['feed_url_id'] = $feed_id;
                                    $saveShareLog['SharedLog']['article_id'] = $article['Article']['id'];
                                    $saveShareLog['SharedLog']['social_account_id'] = $socialAccount['SocialAccount']['id'];
                                    $saveShareLog['SharedLog']['social_type'] = $this->social_network_key[$social_type];
                                    $saveShareLog['SharedLog']['share_text'] = json_encode($share_data);
                                    $saveShareLog['SharedLog']['created'] = date("Y-m-d H:i:s");
                                    $saveShareLog['SharedLog']['updated'] = date("Y-m-d H:i:s");
                                    pr($saveShareLog);
                                    $this->SharedLog->save($saveShareLog);
                                }
                                sleep(10);


                                if(isset($response_1)){


                                    $responseDecode = json_decode($response, true);
                                    
                                    if($social_type == 'facebook'){
                                        if(isset($responseDecode['id']) && !empty($responseDecode['id'])){
                                            $saveShareLog['SharedLog']['share_status'] = 1;
                                            $saveShareLog['SharedLog']['response'] = json_encode($response);
                                        }else{
                                            $saveShareLog['SharedLog']['share_status'] = 3;
                                            $saveShareLog['SharedLog']['response'] = json_encode($response);
                                        }
                                    }

                                    $saveShareLog['SharedLog']['feed_url_id'] = $feed_id;
                                    $saveShareLog['SharedLog']['article_id'] = $article['Article']['id'];
                                    $saveShareLog['SharedLog']['social_account_id'] = $socialAccount['SocialAccount']['id'];
                                    $saveShareLog['SharedLog']['social_type'] = 5;
                                    $saveShareLog['SharedLog']['share_text'] = json_encode($share_data);
                                    $saveShareLog['SharedLog']['created'] = date("Y-m-d H:i:s");
                                    $saveShareLog['SharedLog']['updated'] = date("Y-m-d H:i:s");
                                    
                                    $this->SharedLog->save($saveShareLog);
                                    sleep(10);
                                }
                                
                                //prd('hold');
                            }

                            $updArticle['Article']['id'] = $article['Article']['id'];
                            $updArticle['Article']['share_status'] = 1;
                            $this->Article->save($updArticle);
                        }
                    }
                }
            } 

        }
        exit;
    }
}
