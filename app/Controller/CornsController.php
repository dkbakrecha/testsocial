<?php

App::uses('AppController', 'Controller');

class CornsController extends AppController {

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
                
                $this->Article->create();
                $this->Article->save($article);
            }
        }
    }

}
