<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'OAuth');
App::import('Vendor', 'twitteroauth');
App::import('Vendor', 'linkedin');
App::import('Vendor', 'google_client/autoload');

class SocialAccountController extends AppController {

	public function beforeFilter() {
        parent::beforeFilter();
    }

	public function add_account(){
		
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

					$linkedinConnect = $this->linkedinConnect();
					prd($linkedinConnect);
				}elseif($saveAccount['SocialAccount']['social_type'] == 3){

					$this->fbConnect();

				}elseif($saveAccount['SocialAccount']['social_type'] == 4){
					$this->googleConnect();
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

	public function edit_account($id){
		$this->loadModel('FeedUrl');
		$this->FeedUrl->virtualFields['titleUrl'] = 'CONCAT(title, "( ", rss_url, " )")';

		if(empty($id)){
			$this->Session->setFlash(__('Account you are try to access does\'t exist.'), 'default', array('class' => 'alert alert-danger'));
			$this->redirect(array('action' => 'account_list'));
		}

		$this->SocialAccount->id = $id;
		if($this->SocialAccount->exists()){

			$socialAcountData = $this->SocialAccount->find('first', array(
					'conditions' => array('id' => $id),
				)
			);

			if($this->request->is('post')){
				$postData = $this->request->data;
				$saveAccount['SocialAccount']['id'] = $id;
				$saveAccount['SocialAccount']['name'] = $postData['SocialAccount']['name'];
				$saveAccount['SocialAccount']['social_type'] = $socialAcountData['SocialAccount']['social_type'];
				$saveAccount['SocialAccount']['rss_feed_url'] = $postData['SocialAccount']['rss_feed_url'];

				$this->SocialAccount->set($saveAccount);
				
				if($this->SocialAccount->validates()){
					$this->SocialAccount->save($saveAccount);
				}

			}else{
				$this->request->data['SocialAccount'] = $socialAcountData['SocialAccount'];
			}

			$feed_data = array();
			$feed_data = $this->FeedUrl->find('list', array(
					'fields' => array('id', 'titleUrl')
				)
			);
			
			$this->set(compact('feed_data'));
		}else{
			$this->Session->setFlash(__('Account you are try to access does\'t exist.'), 'default', array('class' => 'alert alert-danger'));
			$this->redirect(array('action' => 'account_list'));
		}
	}

	public function delete($id){
		
		if(empty($id)){
			$this->Session->setFlash(__('Account you are trying to delete does\'t exist.'), 'default', array('class' => 'alert alert-danger'));
			$this->redirect(array('action' => 'account_list'));
		}

		$this->SocialAccount->id = $id;
		if($this->SocialAccount->exists()){

			$socialAcountData = $this->SocialAccount->find('first', array(
					'conditions' => array('id' => $id),
				)
			);

			if(isset($socialAcountData) && !empty($socialAcountData)){
				$saveAccount['SocialAccount']['id'] = $id;
				$saveAccount['SocialAccount']['status'] = 2; //2 indicate account soft delete
				$this->SocialAccount->save($saveAccount);

				$this->Session->setFlash(__('Account deleted successfully.'), 'default', array('class' => 'alert alert-success'));
				$this->redirect(array('action' => 'account_list'));
			}
			
		}else{
			$this->Session->setFlash(__('Account you are trying to delete does\'t exist.'), 'default', array('class' => 'alert alert-danger'));
			$this->redirect(array('action' => 'account_list'));
		}
	}

	public function activate($id){
		if(empty($id)){
			$this->Session->setFlash(__('Account you are trying to activate does\'t exist.'), 'default', array('class' => 'alert alert-danger'));
			$this->redirect(array('action' => 'account_list'));
		}

		$this->SocialAccount->id = $id;
		if($this->SocialAccount->exists()){

			$socialAcountData = $this->SocialAccount->find('first', array(
					'conditions' => array('id' => $id),
				)
			);

			if(isset($socialAcountData) && !empty($socialAcountData)){
				$this->Session->write('SocialAccount', $socialAcountData);
				
				if(isset($socialAcountData['SocialAccount']['social_type'])){
					if($socialAcountData['SocialAccount']['social_type'] == 1){
						$twitterConnect = $this->twitterConnect();
					}elseif($socialAcountData['SocialAccount']['social_type'] == 2){
						$linkedinConnect = $this->linkedinConnect();
					}elseif($socialAcountData['SocialAccount']['social_type'] == 3){
						$this->fbConnect();
					}elseif($socialAcountData['SocialAccount']['social_type'] == 4){
						$this->googleConnect();
					}
				}
			}
			
		}

		$this->Session->setFlash(__('Account you are trying to activate does\'t exist.'), 'default', array('class' => 'alert alert-danger'));
		$this->redirect(array('action' => 'account_list'));
		
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
		    $this->Session->delete('SocialAccount');
		    $this->redirect('account_list');

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

	public function linkedinConnect(){
		$linkedinConsumer = Configure::read('Linkedin');
		
		if(isset($_GET['code'])){

		    $accessToken_url = 'https://www.linkedin.com/oauth/v2/accessToken';
		    $req_type = 'POST';
		    $postParameters = http_build_query([
		        'grant_type' => 'authorization_code',
		        'code' => $_GET['code'],
		        'redirect_uri' => Router::url('/social_account/linkedinConnect',true),
		        'client_id' => $linkedinConsumer['CONSUMER_KEY'],
		        'client_secret' => $linkedinConsumer['CONSUMER_SECRET'],
		    ]);

		    $request_config = array(
		    	CURLOPT_SSL_VERIFYHOST => 0,
		    	CURLOPT_SSL_VERIFYPEER => 0,
		    );
		    //$access_token = curl_post($accessToken_url, $postQueryStr);

		    $response = $this->curlHttpRequest($accessToken_url, $req_type, $postParameters, $request_config);
		    pr($response);
		    if(isset($response['data']) && !empty($response['data'])){
		    	$saveAccount = $this->Session->read('SocialAccount');

		    	$responseDecode = json_decode($response['data'],true);

		    	$profileApiUrl = 'https://api.linkedin.com/v1/people/~?format=json';
		    	$req_type1 = 'GET';
		    	
		    	$header = array();
				$header[] = 'Content-type: application/json';
				$header[] = 'Authorization: Bearer '.$responseDecode['access_token'];

		    	$request_config = array(
		    		CURLOPT_SSL_VERIFYHOST => 0,
		    		CURLOPT_SSL_VERIFYPEER => 0,
		    		CURLOPT_HTTPHEADER => $header,
		    	);

		    	$postParameters = array();

				$userInfoResponse = $this->curlHttpRequest($profileApiUrl, $req_type1, $postParameters, $request_config);
				//prd($userInfo);

				if(isset($userInfoResponse['data']) && !empty($userInfoResponse['data'])){
					$userInfoResponseDecoded = json_decode($userInfoResponse['data'], true);
					$saveAccount['SocialAccount']['social_unique_id'] = $userInfoResponseDecoded['id'];
				    $saveAccount['SocialAccount']['access_token'] = $response['data'];

				    $this->SocialAccount->save($saveAccount);
				}
		    }

		    $this->redirect('account_list');

		}else{
		    //getAuthorizationCode();
		    $params = array(
		        'response_type' => 'code',
		        'client_id' => $linkedinConsumer['CONSUMER_KEY'],
		        //'scope' => SCOPE,
		        'state' => uniqid('', true), // unique long string
		        'redirect_uri' => Router::url('/social_account/linkedinConnect',true),
		    );
		    //prd($params);
		    $url = 'https://www.linkedin.com/oauth/v2/authorization?'.http_build_query($params);
		    header("Location:".$url);
		    exit;
		}

		return false;
	}

	public function fbConnect(){
		$fbConsumer = Configure::read('Facebook');
		$redirect_uri = Router::url('fbCallback',true);

		$uniqeKey = md5(uniqid(rand(), TRUE)); // CSRF protection
		$this->Session->write('_fbState', $uniqeKey);

		$fbDialogAuthUrl = 'https://www.facebook.com/dialog/oauth';
		$req_type = 'GET';
		$getParameters = array(
			'client_id' => $fbConsumer['CONSUMER_KEY'],
			'redirect_uri' => $redirect_uri,
			'state' => $uniqeKey,
			'scope' => array('email', 'public_profile', 'manage_pages', 'publish_pages', 'publish_actions'),
		);

		$url = $fbDialogAuthUrl.'?'.http_build_query($getParameters);
		//prd($url);
		header("Location:".$url);
		exit;
	}

	public function fbCallback(){
		$fbConsumer = Configure::read('Facebook');
		$redirect_uri = Router::url('fbCallback',true);

		if(isset($_REQUEST['code']) && !empty($_REQUEST['code'])){
			$code = $_REQUEST['code'];
			$token_url = "https://graph.facebook.com/oauth/access_token";
			$req_type = 'POST';
	  		$postParameters = array(
	  			'client_id' => $fbConsumer['CONSUMER_KEY'],
	  			'client_secret' => $fbConsumer['CONSUMER_SECRET'],
	  			'redirect_uri' => $redirect_uri,
	  			'code' => $code,
	  		);

	  		$request_config = array(
	  			CURLOPT_SSL_VERIFYHOST => 0,
		    	CURLOPT_SSL_VERIFYPEER => 0,
	  		);

			$response = $this->curlHttpRequest($token_url, $req_type, $postParameters, $request_config);

			if(isset($response['data']) && !empty($response['data'])){
				parse_str($response['data'], $access_token);
				pr($access_token);

				$graph_url = "https://graph.facebook.com/me?access_token=". $access_token['access_token'];
				$user = json_decode(file_get_contents($graph_url),true);
				
				$graph_url_account = "https://graph.facebook.com/me/accounts?access_token=". $access_token['access_token'];

				$user_account = json_decode(file_get_contents($graph_url_account),true);

				if(isset($user['id']) && !empty($user['id'])){
					$saveAccount = $this->Session->read('SocialAccount');
					
					$saveAccount['SocialAccount']['social_unique_id'] = $user['id'];
				    $saveAccount['SocialAccount']['access_token'] = $access_token['access_token'];
				    $saveAccount['SocialAccount']['other_extra_info'] = json_encode($user_account);

				    $this->SocialAccount->save($saveAccount);
				}
				//prd($user_account);
				//prd($user);

				$this->redirect('account_list');
			}

		}else{
			$this->redirect('add_account');
		}
	}

	public function googleConnect(){
		$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
		//prd($guzzleClient);
		//Create Client Request to access Google API
		$client = new Google_Client();
		$client->setApplicationName("PHP Google OAuth Login Example");
		$client->setAuthConfig(WWW_ROOT.'../Config/client_id.json');
		//$client->setClientId($client_id);
		//$client->setClientSecret($client_secret);
		$redirect_uri = Router::url('googleConnect',true);
		//prd($redirect_uri);
		$client->setRedirectUri($redirect_uri);
		//$client->setDeveloperKey($simple_api_key);
		$client->addScope("https://www.googleapis.com/auth/userinfo.email");
		$client->addScope("https://www.googleapis.com/auth/plus.me");
		$client->addScope("https://www.googleapis.com/auth/plus.stream.write");
		$client->addScope("https://www.googleapis.com/auth/plus.stream.read");
		$client->addScope("https://www.googleapis.com/auth/plus.circles.read");
		$client->addScope("https://www.googleapis.com/auth/plus.circles.write");
		$client->setHttpClient($guzzleClient);
		//Send Client Request
		$objOAuthService = new Google_Service_Oauth2($client);
		//prd($objOAuthService);
		
		//Authenticate code from Google OAuth Flow
		//Add Access Token to Session
		if (isset($_GET['code'])) {
			pr($_GET['code']);
		  $client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $client->getAccessToken();
		  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		}

		//Set Access Token to make Request
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
			pr($_SESSION['access_token']);
		  $client->setAccessToken($_SESSION['access_token']);
		}

		//Get User Data from Google Plus
		//If New, Insert to Database
		if ($client->getAccessToken()) {
		  $userData = $objOAuthService->userinfo->get();
		  $access_token = $client->getAccessToken();
		  pr($client->getAccessToken());
		  pr($userData);
		  /*if(!empty($userData)) {
			$objDBController = new DBController();
			$existing_member = $objDBController->getUserByOAuthId($userData->id);
			if(empty($existing_member)) {
				$objDBController->insertOAuthUser($userData);
			}
		  }*/

		  	if(isset($access_token['access_token']) && !empty($access_token['access_token'])){
				$saveAccount = $this->Session->read('SocialAccount');
				
				$saveAccount['SocialAccount']['social_unique_id'] = $userData->id;
			    $saveAccount['SocialAccount']['access_token'] = json_encode($access_token);
			    unset($_SESSION['access_token']);
			    $this->SocialAccount->save($saveAccount);
			    $this->redirect('account_list');
			}

		  $_SESSION['access_token'] = $client->getAccessToken();
		} else {
		  $authUrl = $client->createAuthUrl();
		  header("Location:".$authUrl);
		  exit;
		}
		$this->redirect('add_account');
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
                	if(isset($column['name']) && $column['name'] == 'SocialAccount.social_type' && $column['search']['value'] != ''){

                		$condition['SocialAccount.social_type'] = $column['search']['value'];

                	}elseif(isset($column['name']) && $column['name'] == 'SocialAccount.status' && $column['search']['value'] != ''){

                		//prd('hello');
                		$condition['SocialAccount.status'] = $column['search']['value'];

                	}elseif (isset($column['name']) && $column['search']['value'] != '') {
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
        $total_records = $this->SocialAccount->find('count', array('conditions' => $condition, 'joins' => $joins));

        $dataResult = [];
        $totalRecords = $total_records;
        $sr_no = $start;
        $siteUrl = Router::url('/',true);
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
        	if(isset($row['SocialAccount']['status']) && $row['SocialAccount']['status'] != ''){
        		if($row['SocialAccount']['status'] == 0){
        			$connection_status = 'Connection Expire';
        		}elseif ($row['SocialAccount']['status'] == 1) {
        			$connection_status = 'Active';
        		}
        	}

        	$action = '<a href="'.$siteUrl.'social_account/edit_account/'.$row['SocialAccount']['id'].'">Edit</a>&nbsp;&nbsp;';
        	$action .= '<a href="'.$siteUrl.'social_account/delete/'.$row['SocialAccount']['id'].'">Delete</a>&nbsp;&nbsp;';
        	$action .= '<a href="'.$siteUrl.'social_account/activate/'.$row['SocialAccount']['id'].'">Activate</a>&nbsp;&nbsp;';


            $d['sr_no'] = ++$sr_no;
            $d['name'] = $row['SocialAccount']['name'];
            $d['social_type'] = $social_type;
            $d['feed_url'] = $row['FeedUrl']['title'];
            $d['status'] = $connection_status;
            $d['action'] = $action;

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

	public function googleShare(){
		$shareUrl = 'https://www.googleapis.com/plusDomains/v1/people/me/activities';
		//$listUrl = 'https://www.googleapis.com/plus/v1domains/people/me/activities/user';
		$method = 'Post';
		
		$access_token = 'ya29.GlwEBACAcFlU4zjctZISrnznCpn-IIZ3QXkI0SWYIgujvpwo3SyygaurLr6vj3UvdJMk9GiXIWRtshCT2UgYL9DvOm9WS2nRS_eO58EFyntBULrtS4wv_afNGeFRNw';

		//$access_token = 'ya29.GlsEBOcfQqgv04tXmSkTTivWWJfgmKtyX40-sXjFXJqAraiYY_r1bjsxAhB_hmHwmIIh2IX7uTFj4dJ4tvc3u8-nR03Ci55R3n3A5kzE5DUDnfErnySy0AhZTPH7';

		$header = array();
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: OAuth '.$access_token;

        $request_config = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => $header,
        );

        $post_data = array(
        	'object' => array('originalContent' => 'Hey this is my first auto post.'),
        	'access' => array('items' => array('type'=>'myCircles'), 'domainRestricted' => false),
        );

        /*'curl -v -H "Content-Type: application/json" -H "Authorization: OAuth$ACCESS_TOKEN" -d "{"object": {"originalContent": "Happy Monday!#caseofthemondays"},"access":{"kind":"plus#acl","items":[{"type":"domain"}],"domainRestricted":true}}" -X POST https://www.googleapis.com/plusDomains/v1/people/{userId}/activities'*/

        $postParameters = json_encode($post_data);
        $responseData = $this->curlHttpRequest($shareUrl, $method, $postParameters, $request_config);
        prd($responseData);
        return $responseData;
	}

}