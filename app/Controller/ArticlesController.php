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
    	if($this->request->is('post')){
    		$postData = $this->request->data;

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
    	}
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
}