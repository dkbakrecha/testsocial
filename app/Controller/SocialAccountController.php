<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'OAuth');
App::import('Vendor', 'twitteroauth');

class SocialAccountController extends AppController {

	public function beforeFilter() {
        parent::beforeFilter();
    }

	public function add_account(){
		//prd('hello');
		//$this->Session->setFlash(__('Invalid Key.'), 'default', array('class' => 'alert alert-danger'));
		//pr($this->SocialAccount);
		$this->loadModel('FeedUrl');
		$this->FeedUrl->virtualFields['titleUrl'] = 'CONCAT(title, "( ", rss_url, " )")';

		if($this->request->is('post')){
			$postData = $this->request->data;
			$saveAccount['SocialAccount']['name'] = $postData['SocialAccount']['name'];
			$saveAccount['SocialAccount']['social_type'] = $postData['SocialAccount']['social_type'];
			$saveAccount['SocialAccount']['rss_feed_url'] = $postData['SocialAccount']['rss_feed_url'];

			$this->SocialAccount->set($saveAccount);

			if($this->SocialAccount->validates()){
				$this->Session->write('SocialAccount', $saveAccount);

				if($saveAccount['SocialAccount']['social_type'] == 1){

					$twitterConnect = $this->twitterConnect();

				}elseif($saveAccount['SocialAccount']['social_type'] == 2){

				}elseif($saveAccount['SocialAccount']['social_type'] == 3){

				}elseif($saveAccount['SocialAccount']['social_type'] == 4){

				}

			}else{

			}
		}

		$feed_data = array();
		$feed_data = $this->FeedUrl->find('list', array(
				'fields' => array('id', 'titleUrl')
			)
		);
		
		$this->set(compact('feed_data'));
	}

	public function twitterConnect(){
		$twitterConsumer = Configure::read('Twitter');
		
		if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {
			//prd('hold1');
			$oauth_token = $this->Session->read('oauth_token');
			$oauth_token_secret = $this->Session->write('oauth_token_secret');

			$twitteroauth = new TwitterOAuth($twitterConsumer['CONSUMER_KEY'], $twitterConsumer['CONSUMER_SECRET'], $oauth_token, $oauth_token_secret);
    
		    $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
		    //prd($access_token);
		    $this->Session->write('access_token', $access_token);

		    $saveAccount = $this->Session->read('SocialAccount');
		    $saveAccount['SocialAccount']['social_unique_id'] = $access_token['user_id'];
		    $saveAccount['SocialAccount']['access_token'] = json_encode($access_token);

		    $this->SocialAccount->save($saveAccount);

		    $this->redirect('add_account');

		}else{

			$twitteroauth = new TwitterOAuth($twitterConsumer['CONSUMER_KEY'], $twitterConsumer['CONSUMER_SECRET']); // No need to change anything in this line.
			//pr($twitteroauth);

			$callback_url = Router::url('/',true).'social_account/twitterConnect';
			//prd($callback_url);
			$request_token = $twitteroauth->getRequestToken($callback_url);

			
			$this->Session->write('oauth_token', $request_token['oauth_token']);
			$this->Session->write('oauth_token_secret', $request_token['oauth_token_secret']);

			if ($twitteroauth->http_code == 200) {
				$url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
			    header('Location: ' . $url);
			}
			exit;
		}

		return false;
	}

	public function account_list(){
		$this->set('title_for_layout', 'Manage Social Account');
	}

	public function account_grid(){
		
        $this->layout = 'ajax';

        $request = $this->request;
        $data = $request->data;

        $start = $data['start'];
        $limit = $data['length'];

        $colName = $request->data['order'][0]['column'];
        $orderby[$request->data['columns'][$colName]['name']] = $request->data['order'][0]['dir'];

        $condition = array();
        $condition['SocialAccount.status !='] = 2;

        if (isset($request->data['columns'])) {
            foreach ($request->data['columns'] as $column) {
                if (isset($column['searchable']) && $column['searchable'] == 'true') {
                    if (isset($column['name']) && $column['search']['value'] != '') {
                        $condition[$column['name'] . ' LIKE '] = '%' . filter_var($column['search']['value']) . '%';
                    }
                }
            }
        }
        
        $fields = array('*');

        $joins = array(
        	array(
	            'table' => 'feed_urls',
	            'alias' => 'FeedUrl',
	            'type' => 'INNER',
	            'conditions'=> array(
	                'SocialAccount.rss_feed_url = FeedUrl.id', 
	            )
	        ),
        );

        //prd($condition);
        $query = $this->SocialAccount->find('all', array(
            'conditions' => $condition,
            'joins' => $joins,
            'fields' => $fields,
            'order' => $orderby,
            'limit' => $limit,
            'offset' => $start
                ));
        //prd($query);
        $total_records = $this->SocialAccount->find('count', array('conditions' => $condition));

        $dataResult = [];
        $totalRecords = $total_records;
        $sr_no = $start;
        foreach ($query as $row) {

        	$social_type = '-';
        	if(isset($row['SocialAccount']['social_type']) && !empty($row['SocialAccount']['social_type'])){
        		if($row['SocialAccount']['social_type'] == 1){
        			$social_type = 'Twitter';
        		}elseif($row['SocialAccount']['social_type'] == 2){
        			$social_type = 'LinkedIn';
        		}elseif($row['SocialAccount']['social_type'] == 3){
        			$social_type = 'Facebook';
        		}elseif($row['SocialAccount']['social_type'] == 4){
        			$social_type = 'Google';
        		}
        	}

        	$connection_status = '-';
        	if(isset($row['SocialAccount']['status']) && !empty($row['SocialAccount']['status'])){
        		if($row['SocialAccount']['status'] == 0){
        			$connection_status = 'Connection Expire';
        		}elseif ($row['SocialAccount']['status'] == 1) {
        			$connection_status = 'Active';
        		}
        	}

            $d['sr_no'] = ++$sr_no;
            $d['name'] = $row['SocialAccount']['name'];
            $d['social_type'] = $social_type;
            $d['feed_url'] = $row['FeedUrl']['title'];
            $d['status'] = $connection_status;
            $d['action'] = 'Edit-Delete-Activate';

            $dataResult[] = $d;
        }

        $returnData = [
            'draw' => $data['draw'],
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $dataResult
        ];
        echo json_encode($returnData);
        exit;

	}
}