<?php
App::uses('AppController', 'Controller');

class FeedController extends AppController {

	public $uses = array();
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index','insta');
    }
    
    public function index() {
        
        $client_id = "455135fcef484d8f830464cb2af2ebf2";
        $redirect_uri = "http://dynamicwebsite.co.in/testnew/feed/insta";
        $res_type = "token"; //code
        $insta_url = "https://api.instagram.com/oauth/authorize/?client_id=". $client_id ."&redirect_uri=". $redirect_uri ."&response_type=".$res_type;
        echo $insta_url;
        exit;
    }
    
    public function insta() {
        $data = $this->request;
        prd($data);
    }
    
    
    /*  ==========  ADMIN SECTION  ==========  */
    
    public function admin_index() {

	}
    
    public function admin_add() {

	}
}