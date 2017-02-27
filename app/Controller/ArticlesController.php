<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'OAuth');
App::import('Vendor', 'twitteroauth');
App::import('Vendor', 'linkedin');
App::import('Vendor', 'simple_html_dom');
App::import('Vendor', 'UrlToAbsolute');

class ArticlesController extends AppController {
	public function beforeFilter() {
        parent::beforeFilter();
    }

    public function add(){
        $this->loadModel('SocialAccount');
        $twitterAccount = $this->SocialAccount->find('list', array(
                'conditions' => array('social_type' => 1),
                'fields' => array('id', 'Name'),
            )
        );

        $linkedinAccount = $this->SocialAccount->find('list', array(
                'conditions' => array('social_type' => 2),
                'fields' => array('id', 'Name'),
            )
        );

        $fbAccount = $this->SocialAccount->find('list', array(
                'conditions' => array('social_type' => 3),
                'fields' => array('id', 'Name'),
            )
        );

        $googleAccount = $this->SocialAccount->find('list', array(
                'conditions' => array('social_type' => 4),
                'fields' => array('id', 'Name'),
            )
        );

        $this->set(compact('twitterAccount', 'linkedinAccount', 'fbAccount', 'googleAccount'));

    	if($this->request->is('post')){
    		$postData = $this->request->data;

            prd($postData);

            if(isset($postData['Article']['schedule_date']) && !empty($postData['Article']['schedule_date'])){
                $postData['Article']['schedule_date'] = date('Y-m-d',strtotime($postData['Article']['schedule_date']));
            }
    		//prd($postData);

    		$allowedExtension = array("image/jpeg", "image/png");
            $imgDirPath = WWW_ROOT.'img/article_img/';

            if(isset($postData['Article']['media']['name']) && !empty($postData['Article']['media']['name'])){
                $imgInfo = @getimagesize($postData['Article']['media']['tmp_name']);
                $isValidImg = !empty($imgInfo) && isset($imgInfo['mime']) && in_array($imgInfo['mime'], $allowedExtension);

                if($isValidImg){

                    $imgExtension = explode('/', $imgInfo['mime']);
                    $image_name = 'Article_' . strtotime(date("Y-m-d H:i:s")) . '.' . $imgExtension[1];
                    $uploadPath = $imgDirPath . $image_name;

                }else{

                    if (empty($imgInfo['mime'])) {

                        $validationMsg = 'Corrupt or Invalid file type. Allowed file type are:- jpeg, jpg and png.';
                    } elseif (!empty($imgInfo['mime']) && ($imgInfo['mime'] != $this->request->data['Post']['image_arr']['type'])) {

                        $validationMsg = 'Invalid file mime type. Allowed file type are:- jpeg, jpg and png.';
                    } else {

                        $validationMsg = 'Please upload valid image file type are:- jpeg, jpg and png.';
                    }

                    $this->Article->validate['media'] = array(
                        'rule' => array('notEmpty'),
                        'message' => $validationMsg,
                    );
                }

            }elseif(isset($postData['Article']['previewImg']) && !empty($postData['Article']['previewImg'])){

                $imgInfo = @getimagesize($postData['Article']['previewImg']);
                $isValidImg = !empty($imgInfo) && isset($imgInfo['mime']) && in_array($imgInfo['mime'], $allowedExtension);
                
                if($isValidImg){

                    $imgExtension = explode('/', $imgInfo['mime']);
                    $image_name = 'Article_' . strtotime(date("Y-m-d H:i:s")) . '.' . $imgExtension[1];
                    $uploadPath = $imgDirPath . $image_name;

                    $fileContent = file_get_contents($postData['Article']['previewImg']);
                    file_put_contents($uploadPath, $fileContent);
                    $postData['Article']['media'] = $image_name;

                }else{

                    if (empty($imgInfo['mime'])) {

                        $validationMsg = 'Corrupt or Invalid file type. Allowed file type are:- jpeg, jpg and png.';
                    } elseif (!empty($imgInfo['mime']) && ($imgInfo['mime'] != $this->request->data['Post']['image_arr']['type'])) {

                        $validationMsg = 'Invalid file mime type. Allowed file type are:- jpeg, jpg and png.';
                    } else {

                        $validationMsg = 'Please upload valid image file type are:- jpeg, jpg and png.';
                    }

                    $this->Article->validate['media'] = array(
                        'rule' => array('notEmpty'),
                        'message' => $validationMsg,
                    );
                }                
            }

            $this->Article->set($postData);

            if($this->Article->validates()){
                
                if(isset($postData['Article']['media']['name']) && !empty($postData['Article']['media']['name'])){
                    $img_status = move_uploaded_file($this->request->data['Article']['media']['tmp_name'], $uploadPath);

                    $postData['Article']['media'] = $image_name;
                }   
                
                $this->Article->save($postData, false);
                $this->redirect(array('action' => 'add'));
            }else{
                $this->request->data['Article'] = $postData['Article'];
                $this->Session->setFlash("Please fill all required fields.", 'flash_error');
            }
    	}else{
            $selectedTwitterAcc = array_keys($twitterAccount);
            $selectedLinkedinAcc = array_keys($linkedinAccount);
            $selectedFbAcc = array_keys($fbAccount);
            $selectedGoogleAcc = array_keys($googleAccount);
        }

        $this->set(compact('selectedTwitterAcc', 'selectedLinkedinAcc', 'selectedFbAcc', 'selectedGoogleAcc'));
    }

    public function getHtml(){    	
    	$this->layout = false;
    	$response = array();
        if (isset($this->request->query['url'])) {
            $url = $this->request->query['url'];
            $title = ''; 
            $description = '';
            $biggest_img = ''; // Is returned when no images are found.
            $content = '';
            // process
            $maxSize = -1;
            $visited = array();
            //echo date("H:i:s").'<br/>';
            $html = file_get_html($url);
            //prd($html);

            @$xp = new domxpath($html);

            if (isset($xp) && !empty($xp)) {

            	//check for title
            	foreach ($xp->query("//meta[@property='og:title']") as $meta_title) {
					$title = $meta_title->getAttribute("content");
            	}

            	//check for description
            	foreach ($xp->query("//meta[@property='og:description']") as $meta_desc) {
					$description = $meta_desc->getAttribute("content");
            	}

            	//check for image
                foreach ($xp->query("//meta[@property='og:image']") as $meta_element) {

                    $imageurlContent = $meta_element->getAttribute("content");

                    // ignore already seen images, add new images
                    if ($imageurlContent == '')
                            continue; // it happens on your test url

                    $absUrl = new UrlToAbsolute();
                    $imageurl = $absUrl->url_to_absolute($url, $imageurlContent); //get image absolute url
                    // ignore already seen images, add new images
                    if (in_array($imageurl, $visited))
                        continue;
                    $visited[] = $imageurl;
                    
                    $image = @getimagesize($imageurl); // get the rest images width and height
                    //$biggest_img = $meta_element->content;
                    if (($image[0] * $image[1]) > $maxSize && ($image[0] > 100 && $image[1] > 100)) {
                        $maxSize = $image[0] * $image[1];  //compare sizes
                        $biggest_img = $imageurl;
                    }
                }

                if (empty($biggest_img)) {
                    foreach ($xp->query("//meta[@property='og:image:secure_url']") as $meta_element) {

                        $imageurlContent = $meta_element->getAttribute("content");
                        if ($imageurlContent == '')
                            continue; // it happens on your test url

                        $absUrl = new UrlToAbsolute();
                        $imageurl = $absUrl->url_to_absolute($url, $imageurlContent); //get image absolute url
                        // ignore already seen images, add new images
                        if (in_array($imageurl, $visited))
                            continue;
                        $visited[] = $imageurl;

                        $image = @getimagesize($imageurl); // get the rest images width and height
                        //$biggest_img = $meta_element->content;
                        if (($image[0] * $image[1]) > $maxSize && ($image[0] > 150 && $image[1] > 150)) {
                            $maxSize = $image[0] * $image[1];  //compare sizes
                            $biggest_img = $imageurl;
                        }
                    }
                }

                if (empty($biggest_img)) {
                    foreach ($xp->query("//meta[@name='twitter:image']") as $meta_element) {

                        $imageurlContent = $meta_element->getAttribute("content");
                        if ($imageurlContent == '')
                            continue; // it happens on your test url

                        $absUrl = new UrlToAbsolute();
                        $imageurl = $absUrl->url_to_absolute($url, $imageurlContent); //get image absolute url
                        // ignore already seen images, add new images
                        if (in_array($imageurl, $visited))
                            continue;
                        $visited[] = $imageurl;

                        $image = @getimagesize($imageurl); // get the rest images width and height
                        //$biggest_img = $meta_element->content;
                        if (($image[0] * $image[1]) > $maxSize && ($image[0] > 100 && $image[1] > 100)) {
                            $maxSize = $image[0] * $image[1];  //compare sizes
                            $biggest_img = $imageurl;
                        }
                    }
                }

                if(empty($title)){
                	foreach ($html->getElementsByTagName('h1') as $h1Tag) {
                		$content .= $h1Tag->nodeValue.'\n\n';
                	}
                }

                if(empty($description)){
                	foreach ($html->getElementsByTagName('p') as $pTag) {
                		$content .= $pTag->nodeValue.'\n\n';
                	}
                }

                $response['error'] = 0;
				$response['msg'] = 'Data scrape successfully.';
				$response['data'] = [
					'title' => $title,
					'description' => $description,
					'image' => $biggest_img,
					'content' => $content,
				];
            }else{
            	$response['error'] = 2;
				$response['msg'] = 'No data found.';	
            }

            //echo $biggest_img; //return the biggest found image
            
        }else{
			$response['error'] = 1;
			$response['msg'] = 'Please enter valid url.';
        }

    	echo json_encode($response);
    	exit;
    }

    public function lists(){
        $this->set('title_for_layout', 'Manage Article');
    }

    public function article_grid(){
        
        $this->layout = 'ajax';

        $request = $this->request;
        $data = $request->data;

        $start = $data['start'];
        $limit = $data['length'];

        $colName = $request->data['order'][0]['column'];
        $orderby[$request->data['columns'][$colName]['name']] = $request->data['order'][0]['dir'];

        $condition = array();
        $condition['Article.status !='] = 2;

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
                    'Article.feed_id = FeedUrl.id', 
                )
            ),
        );

        //prd($condition);
        $query = $this->Article->find('all', array(
            'conditions' => $condition,
            'joins' => $joins,
            'fields' => $fields,
            'order' => $orderby,
            'limit' => $limit,
            'offset' => $start
                ));
        //prd($query);
        $total_records = $this->Article->find('count', array('conditions' => $condition, 'joins' => $joins));

        $dataResult = [];
        $totalRecords = $total_records;
        $sr_no = $start;
        $siteUrl = Router::url('/',true);
        foreach ($query as $row) {

            $action = '<a href="'.$siteUrl.'articles/delete/'.$row['Article']['id'].'">Delete</a>&nbsp;&nbsp;';


            $d['sr_no'] = ++$sr_no;
            $d['title'] = $row['Article']['title'];
            $d['feed_url'] = $row['FeedUrl']['title'];
            $d['created_on'] = '-';
            $d['action'] = $action;

            $dataResult[] = $d;
        }
        //prd($dataResult);
        $returnData = [
            'draw' => $data['draw'],
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $dataResult
        ];
        echo json_encode($returnData);
        exit;

    }

    public function shared_log(){
        $this->set('title_for_layout', 'Shared Article Log');
    }

    public function shared_grid(){
        $this->layout = 'ajax';
        $this->loadModel('SharedLog');

        $request = $this->request;
        $data = $request->data;

        $start = $data['start'];
        $limit = $data['length'];

        $colName = $request->data['order'][0]['column'];
        $orderby[$request->data['columns'][$colName]['name']] = $request->data['order'][0]['dir'];

        $condition = array();

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
                    'SharedLog.feed_url_id = FeedUrl.id', 
                )
            ),
            array(
                'table' => 'articles',
                'alias' => 'Article',
                'type' => 'INNER',
                'conditions'=> array(
                    'SharedLog.article_id = Article.id', 
                )
            ),
            array(
                'table' => 'social_accounts',
                'alias' => 'SocialAccount',
                'type' => 'INNER',
                'conditions'=> array(
                    'SharedLog.social_account_id = SocialAccount.id', 
                )
            ),
        );

        //prd($condition);
        $query = $this->SharedLog->find('all', array(
            'conditions' => $condition,
            'joins' => $joins,
            'fields' => $fields,
            'order' => $orderby,
            'limit' => $limit,
            'offset' => $start
                ));
        //prd($query);
        $total_records = $this->SharedLog->find('count', array('conditions' => $condition, 'joins' => $joins));

        $dataResult = [];
        $totalRecords = $total_records;
        $sr_no = $start;
        $siteUrl = Router::url('/',true);
        foreach ($query as $row) {

            $social_type = '-';
            if(isset($row['SharedLog']['social_type']) && !empty($row['SharedLog']['social_type'])){
                if($row['SharedLog']['social_type'] == 1){
                    $social_type = 'Twitter';
                }elseif($row['SharedLog']['social_type'] == 2){
                    $social_type = 'LinkedIn';
                }elseif($row['SharedLog']['social_type'] == 3){
                    $social_type = 'Facebook';
                }elseif($row['SharedLog']['social_type'] == 4){
                    $social_type = 'Google';
                }
            }

            $share_status = '-';
            if(isset($row['SharedLog']['share_status']) && !empty($row['SharedLog']['share_status'])){
                if($row['SharedLog']['share_status'] == 1){
                    $share_status = 'Shared';
                }elseif($row['SharedLog']['share_status'] == 2){
                    $share_status = 'Sheduled';
                }elseif($row['SharedLog']['share_status'] == 3){
                    $share_status = 'Error';
                }
            }


            $d['sr_no'] = ++$sr_no;
            $d['feed_title'] = $row['FeedUrl']['title'];
            $d['article_title'] = $row['Article']['title'];
            $d['shared_on'] = $row['SocialAccount']['name'];
            $d['social_type'] = $social_type;
            $d['status'] = $share_status;
            $d['updated'] = date('d-m-Y',strtotime($row['SharedLog']['updated']));

            $dataResult[] = $d;
        }
        //prd($dataResult);
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