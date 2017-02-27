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
        //prd($x);
        /* echo "<ul>";

          foreach ($x->channel->item as $entry) {
          //pr($entry);
          //echo "<li><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
          echo '<h1>' . $entry->title . '</h1>';
          echo '<p>';
          echo '<strong>Url:- </strong>' . $entry->title;
          echo '</p>';
          echo '<p>';
          echo '<strong>Description:- </strong>' . $entry->description;
          echo '</p>';
          }
          echo "</ul>"; */
    }

    public function update_articles($feedInfo, $feed_id) {
        $this->loadModel('Article');
        if (!empty($feedInfo)) {
            foreach ($feedInfo->channel->item as $entry) {
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
                
                $this->Article->create();
                $this->Article->save($article);
            }
        }
    }

    /**
     * Share articles on social media
     * @param $social_type string [ Twitter, LinkedIn, Facebook, Google]
    **/
    public function share($social_type){
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
        $this->loadModel('ShareLog');

        $social_type = strtolower($social_type);

        $articleDataList = $this->Article->find('all', array(
                'conditions' => array(
                    'share_status' => 0,
                    'feed_id' => 2,
                ),
                'limit' => 5
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
                                'fields' => array('id', 'name', 'social_unique_id','access_token'),
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
                                $access_token = json_decode($socialAccount['SocialAccount']['access_token'], true);
                                //prd($access_token);

                                if($social_type == 'twitter'){
                                    $response = $this->twitter_share($access_token,$share_data);
                                }elseif ($social_type == 'linkedin') {
                                    $response = $this->linkedin_share($access_token,$share_data);
                                }


                                if(isset($response)){

                                    if($social_type == 'twitter'){
                                        $responseDecode = json_decode($response, true);
                                    }elseif ($social_type == 'linkedin') {
                                        $responseDecode = $response;
                                    }

                                    if(isset($responseDecode['created_at']) && !empty($responseDecode['created_at'])){
                                        $saveShareLog['ShareLog']['share_status'] = 1;
                                        $saveShareLog['ShareLog']['response'] = json_encode($response);
                                    }else{
                                        $saveShareLog['ShareLog']['share_status'] = 3;
                                        $saveShareLog['ShareLog']['response'] = json_encode($response);
                                    }

                                    $saveShareLog['ShareLog']['feed_url_id'] = $feed_id;
                                    $saveShareLog['ShareLog']['article_id'] = $article['Article']['id'];
                                    $saveShareLog['ShareLog']['social_account_id'] = $socialAccount['SocialAccount']['id'];
                                    $saveShareLog['ShareLog']['social_type'] = $this->social_network_key[$social_type];
                                    $saveShareLog['ShareLog']['share_text'] = json_encode($share_data);
                                    $saveShareLog['ShareLog']['created'] = date("Y-m-d H:i:s");
                                    $saveShareLog['ShareLog']['updated'] = date("Y-m-d H:i:s");
                                    pr($saveShareLog);
                                    $this->ShareLog->save($saveShareLog);

                                    $updArticle['Article']['id'] = $article['Article']['id'];
                                    $updArticle['Article']['share_status'] = 1;
                                    $this->Article->save($updArticle);
                                }

                            }
                        }
                    }
                }
            } 

        }
        exit;
    }
}
