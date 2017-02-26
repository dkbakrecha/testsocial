<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController {

    public $uses = array();
    public $components = array('Gcal', 'RequestHandler');
    var $helpers = array('MagickConvert');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', "aboutus", "features", 'req_complete', 'sync', 'getEvents'
                , 'add_lesson_opening', 'addr', 'search', 'getlibraries', 'getlist', 'apiindex', 'addbooking', 'contact');

        $this->Gcal->c_id = "406644858249-sa671ja4v9uc9td5cbclfqmcpci5sm42.apps.googleusercontent.com";
        $this->Gcal->c_secrat = "q3PZCxUtP862JwTWVkTnEJEX";
        $this->Gcal->r_url = "http://www.dynamicwebsite.co.in/testnew/pages/req_complete/";

        $this->Gcal->googleSettings();
    }

    public function add_lesson_opening() {
        $this->layout = 'ajax';
        $request = $this->request;
        $currUserId = $this->Session->read('Auth.User.id');
        $this->loadModel("Event");

        if ($request->isAjax()) {

            if (($request->isPost() || $request->isPut()) && !empty($request->data)) {
                $dataHere = $request->data;

                $eventDetail = $this->whatEvent($dataHere['pages']['what'], $dataHere['pages']['cDate']);
                //pr($eventDetail);
                // prd($dataHere);
                $dataHere['pages']['coach_id'] = $currUserId;

                $dataHere['Event']['from_time'] = $eventDetail['eventStart'];
                $dataHere['Event']['to_time'] = $eventDetail['eventEnd'];
                $dataHere['Event']['summary'] = $eventDetail['eventSummary'];
                $dataHere['Event']['where'] = $eventDetail['eventWhere'];


                // Validate the data               
                $this->Event->set($dataHere);
                //prd($dataHere);
                if ($this->Event->validates()) {
                    // Set the schedule
                    $dataHere['Event']['created_by'] = 1;
                    $dataHere['Event']['status'] = 1;
                    $this->Event->create();
                    if ($this->Event->save($dataHere)) {
                        echo '1';
                        exit;
                    } else {
                        echo '0';
                        exit;
                    }
                }
            } else {
                $cdate = $this->request->query['cDate'];
                $this->set('clickDate', $cdate);
            }
        } else {
            $this->render('../nodirecturl');
        }
    }

    public function index() {
        $data = $this->request->data;
        //$this->googleSettings();
        //$this->makesync();

        if (isset($data) && !empty($data)) {
            if (isset($data['calenderAdd']['flag']) && $data['calenderAdd']['flag'] == 'insert') {

                $calender_id = "4srvknenrofdpunohccs1u3akc@group.calendar.google.com";

                //$calender_id = "cgtdharm@gmail.com";
                $calender_id = "dtest786@gmail.com"; //Test Prinary
                $calender_id = "fche971pa3ooq69o9lqoaq8e30@group.calendar.google.com"; //Test Secondary

                $event = new Google_Service_Calendar_Event();
                $event->setSummary('Appointment Testttttttt');
                $event->setLocation('Somewhere');
                $start = new Google_Service_Calendar_EventDateTime();
                $start->setDateTime('2015-01-17T10:00:00.000-07:00');
                $event->setStart($start);
                $end = new Google_Service_Calendar_EventDateTime();
                $end->setDateTime('2015-01-20T10:25:00.000-07:00');
                $event->setEnd($end);
                $createdEvent = $this->gCal_service->events->insert($calender_id, $event);

                echo $createdEvent->getId();
            }
        }
        //echo $authUrl;
    }

    public function contact() {
		$this->loadModel('Contact');
        $this->loadModel('EmailContent');
        if ($this->request->is('post')) {
            $data = $this->request->data;
            //prd($data);
            $this->Contact->create($data);
            if ($this->Contact->validates()) {
                $this->Contact->save();
				$data['Contact']['name'] = $data['Contact']['first_name'] . " " . $data['Contact']['last_name'];
				$data['Contact']['subject'] = "Contact From Cupcherry";
				
                $this->EmailContent->contactUsMail($data['Contact']['name'], $data['Contact']['email'], $data['Contact']['subject'], $data['Contact']['message']);
                $this->Session->setFlash(__('Thanks for contact us. Soon one of our team member replay you back :)'), 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('controller' => 'pages', 'action' => 'contact'));
            } else {
                $this->Session->setFlash(
                        __('Error! While saving. Please fill all required field.'), 'default', array('class' => 'alert alert-danger')
                );
            }
        }        
    }

    public function sync() {
        $this->loadModel('Event');
        $this->Gcal->makesync();
        $skip = 1;

        if ($skip == 0) {
            /* FIND NEW EVENTS */
            $eventList = $this->Event->find('all', array(
                'conditions' => array(
                    'Event.status' => 1,
                    'Event.gcal_id IS NULL',
                ),
            ));

            $calender_id = "fche971pa3ooq69o9lqoaq8e30@group.calendar.google.com"; //Test Secondary

            if (!empty($eventList)) {
                foreach ($eventList as $event) {
                    $eventDesc = array();
                    $eventDesc['eventSummary'] = $event['Event']['summary'];
                    $eventDesc['eventWhere'] = $event['Event']['where'];
                    $eventDesc['startDate'] = $event['Event']['from_time'];
                    $eventDesc['endDate'] = $event['Event']['to_time'];

                    $gEvent_id = $this->Gcal->insertEvent($calender_id, $eventDesc);


                    $this->Event->read(null, $event['Event']['id']);
                    $this->Event->set(array(
                        'gcal_id' => "$gEvent_id",
                        'gstatus' => 1,
                    ));
                    $this->Event->save();
                }
            }

            /* FIND DELETED EVENT and delete it from google calender */
            $eventTrush = $this->Event->find('all', array(
                'conditions' => array(
                    'Event.status' => 2,
                    'Event.gstatus' => 1,
                ),
            ));

            if (!empty($eventTrush)) {
                foreach ($eventTrush as $event) {
                    $this->Gcal->deleteEvent($calender_id, $event['Event']['gcal_id']);

                    $this->Event->read(null, $event['Event']['id']);
                    $this->Event->set(array(
                        'gstatus' => 2,
                    ));
                    $this->Event->save();
                }
            }
        }

        $this->redirect(array('action' => 'index'));
    }

    public function getEvents() {
        $request = $this->request;
        $this->layout = 'ajax';
        $this->autoRender = FALSE;
        $this->loadModel('Event');

        if ($request->isAjax()) {
            if ($request->isGet() && !empty($request->query)) {
                //$currUserId = $this->Session->read('Auth.User.id');
                //$currUserType = $this->Session->read('Auth.User.user_type');
                //if ($currUserType == '1') {
                //prd($request->query);
                // Get the Schedules
                $eventList = $this->Event->find('all', array(
                    'conditions' => array(
                        'Event.status' => '1',
                        //'LessonOpening.coach_id' => $currUserId,
                        'Event.from_time >= ' => date('Y-m-d 00:00:00', $request->query['start']),
                        'Event.from_time <= ' => date('Y-m-d 23:59:59', $request->query['end']),
                    ),
                    'fields' => array('Event.*')
                ));

                //prd($eventList);

                if (isset($eventList) && !empty($eventList)) {

                    $all_events = array();
                    foreach ($eventList as $lessons) {
                        $openings = array();
                        $openings['id'] = $lessons['Event']['id'];
                        $openings['group_id'] = $lessons['Event']['group_id'];
                        $openings['start'] = $lessons['Event']['from_time'];
                        $openings['end'] = $lessons['Event']['to_time'];
                        $openings['type'] = $lessons['Event']['type'];

                        switch ($lessons['Event']['type']) {
                            case '1' : $openings['title'] = "Private Lesson";
                                $openings['className'] = "private_lesson"; //#8C7D9E
                                break;
                            case '2' : $openings['title'] = "Group Lesson : " . $lessons['Group']['title'];
                                $openings['className'] = "group_lesson"; //#1A93D8
                                break;
                            case '3' : $openings['title'] = "Match Play";
                                $openings['className'] = "match_lesson"; //#54BFB7
                                break;
                        }

                        $all_events[] = $openings;
                    }

                    if (isset($all_events) && !empty($all_events)) {
                        echo json_encode($all_events);
                    } else {
                        echo 'NF';
                        exit;
                    }
                } else {
                    echo 'NF';
                    exit;
                }
//                } else {
//                    echo '0';
//                    exit;
//                }
            } else {
                $this->render('../nodirecturl');
            }
        } else {
            $this->render('../nodirecturl');
        }
    }

    public function req_complete() {
        $this->autoRender = false;
        //prd($this->request);
        //prd($this->request->query("code"));
        $this->Gcal->googleSettings();
        $this->Gcal->setAuthCode($this->request->query("code"));
        //$this->googleSettings();
//        $authCode = trim( $this->request->query("code") );
//        $accessToken = $this->client->authenticate($authCode);
//        $this->Session->write("authToken", $accessToken);
        //prd($accessToken);
        $this->redirect(array('action' => 'sync'));
    }

    /*
      function googleSettings(){
      // OAuth2 client ID and secret can be found in the Google Developers Console.
      $this->client = new Google_Client();
      //$this->client->setApplicationName("dharmclassy");
      //$this->client->setDeveloperKey("AIzaSyBWDP3iPaeXTwBSCuxx47SRWpLRexDAeHw");

      $this->client->setClientId('406644858249-sa671ja4v9uc9td5cbclfqmcpci5sm42.apps.googleusercontent.com');
      $this->client->setClientSecret('q3PZCxUtP862JwTWVkTnEJEX');
      $this->client->setRedirectUri('http://www.dynamicwebsite.co.in/testnew/pages/req_complete/');

      $this->client->setScopes("https://www.googleapis.com/auth/plus.login");
      $this->client->addScope('https://www.googleapis.com/auth/calendar');
      //  pr($this->client);
      $this->gCal_service = new Google_Service_Calendar($this->client);
      //prd($service);
      }


      function makesync(){
      $authUrl = $this->client->createAuthUrl();

      $authToken = $this->Session->read("authToken");
      if(isset($authToken) && !empty($authToken)){
      $this->client->setAccessToken($authToken);

      if($this->client->isAccessTokenExpired()) {
      header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
      }
      }else{
      $this->redirect($authUrl);
      }
      }
     * 
     */

    /*
      LOGIC FOR EVENT PERSE FROM STRING LIKE
      GOOGLE CALENDER QUICK EVENT ADD
      APPLY FOR ONLY SINGLE DAY EVENT

      @Input
      $str [required] 		: String summary of event
      $cdate [required] 		: Current date in any Format
      @Output
      $array contain
      ["eventStart"] 		: Starting Time of EVENT
      ["eventEnd"] 		: Ending Time of EVENT
      ["eventSummary"] 	: Summary Of EVENT
      ["eventWhere"] 		: ADDRESS of Event if defind

      @Date : Saturday January 17, 2015
      @Author : Dharmendra Bakrecha
     */

    function whatEvent($str, $cdate) {
        $strCompose = explode(" ", $str);
        $newDate = date("d-m-Y", strtotime($cdate));

        $resultEvent = array();

        $substringsTime = array('am', 'pm');
        /*   	
          $resultTime = array_filter($strCompose, function($item) use($substringsTime) {
          foreach($substringsTime as $substring)
          if(strpos($item, $substring) !== FALSE) return TRUE;
          return FALSE;
          });
         */

        $resultTime = array();
        foreach ($strCompose as $key => $newarray) {
            foreach ($substringsTime as $substring) {
                if (strpos($newarray, $substring) !== FALSE) {
                    $resultTime[$key] = $newarray;
                }
            }
        }


        if (!empty($resultTime)) {
            $i = 1;
            foreach ($resultTime as $timeIndex => $timeValue) {
                /*
                  if($timeValue == 'am' || $timeValue == 'pm'){
                  $timeValue = $strCompose[$timeIndex -1] . $strCompose[$timeIndex];
                  }
                 */

                if ($i == 1) {
                    $resultEvent['eventStart'] = date("Y-m-d H:i:s A", strtotime($newDate . " " . $timeValue));
                    unset($strCompose[$timeIndex]);
                }

                if ($i == 2) {
                    $resultEvent['eventEnd'] = date("Y-m-d H:i:s A", strtotime($newDate . " " . $timeValue));

                    /* If to value is small then from */
                    if ($resultEvent['eventStart'] > $resultEvent['eventEnd']) {
                        $resultEvent['eventEnd'] = date("Y-m-d H:i:s A", strtotime($resultEvent['eventEnd'] . " + 1 day"));
                    }

                    unset($strCompose[$timeIndex]);
                    if (strtolower($strCompose[$timeIndex - 1]) == "to") {
                        unset($strCompose[$timeIndex - 1]);
                    }
                }
                $i++;
            }

            /* WHEN no to time is define */
            if (count($resultTime) == 1) {
                $resultEvent['eventEnd'] = date("Y-m-d H:i:s A", (strtotime($newDate . " " . $timeValue) + 3600));
            }
        } else {
            /* WHEN no Date define */
            $resultEvent['eventStart'] = date("Y-m-d H:i:s A", strtotime($newDate));
            $resultEvent['eventEnd'] = date("Y-m-d H:i:s A", strtotime($newDate . " " . "12:59:00 PM"));
        }

        $substringsWhere = array('at');
        /*
          $resultWhere = array_filter($strCompose, function($item) use($substringsWhere) {
          foreach($substringsWhere as $substring)
          if(strpos($item, $substring) !== FALSE) return TRUE;
          return FALSE;
          });
         */

        $resultWhere = array();
        foreach ($strCompose as $key => $newarray) {
            foreach ($substringsWhere as $substring) {
                if (strpos($newarray, $substring) !== FALSE) {
                    $resultWhere[$key] = $newarray;
                }
            }
        }

        $resultEvent['eventSummary'] = "";
        $resultEvent['eventWhere'] = "";

        $newAddr = array();
        if (!empty($resultWhere)) {
            $keyWhere = array_search('at', $strCompose);

            foreach ($strCompose as $strIndex => $strValue) {
                unset($strCompose[$keyWhere]);
                if ($strIndex > $keyWhere) {
                    array_push($newAddr, $strValue);
                    unset($strCompose[$strIndex]);
                }
            }
        }

        if (!empty($newAddr)) {
            $resultEvent['eventWhere'] = implode(" ", $newAddr);
        }

        $resultEvent['eventSummary'] = implode(" ", $strCompose);
        return $resultEvent;
    }

    public function addr() {
        $request = $this->request;
        $data = $request->data;
        if (!empty($data)) {
            $letlng = '(25.769108534982895, -80.26654243469238)(25.743753031802985, -80.27169227600098)(25.738650355647824, -80.23375511169434)(25.772664056765723, -80.22860527038574)';
            $this->saveGridImage($letlng, 1);
        }
    }

    public function search() {
        
    }

    public function getlibraries() {
        $this->loadModel('Library');
        $libraries = $this->Library->find('all');
        //$res = array('records' => $libdata);
        //$posts = $this->Post->find('all');
        $this->set(array(
            'libraries' => $libraries,
            '_serialize' => array('libraries')
        ));

        //echo json_encode($res);
        //exit;
        //prd($libdata);
    }

    public function getlist($searchterm) {
        $this->loadModel('Post');
        $this->loadModel('Library');
        $libraries = $this->Library->find('all');

        $resList = $this->Post->find('all', array(
            'conditions' => array('title like' => '%' . $searchterm . '%'),
            'fields' => array('count(id) as r_count', 'lib_id'),
            'group' => array('lib_id'),
        ));

        $countArr = array();
        foreach ($resList as $res) {
            $countArr[$res['Post']['lib_id']] = $res[0]['r_count'];
        }

        $finalArray = array();
        $i = 1;
        foreach ($libraries as $lib) {
            //pr($lib);
            $finalArray[$i]['lib_title'] = $lib['Library']['title'];
            $finalArray[$i]['search_ount'] = (!empty($countArr[$lib['Library']['id']])) ? $countArr[$lib['Library']['id']] : '0';
            $i++;
        }
        //pr($finalArray);
        //prd($resList);

        $this->set(array(
            'finalArray' => $finalArray,
            '_serialize' => array('finalArray')
        ));
    }

    public function apiindex() {
        $apiKey = 'eff2f1d2-e148-4ca3-8d40-9104fdcd0970';

        $json = file_get_contents('http://connect.bookt.com/ws/?method=get&entity=property&apikey=' . $apiKey . '&ids=197687');
        $data = json_decode($json);

        //prd($data);
        $this->set("listingData", $data);
    }

    public function addbooking() {
        $apiKey = 'eff2f1d2-e148-4ca3-8d40-9104fdcd0970';
        $request = $this->request;
        if ($request->is('ajax')) {
            $response = array();
            $response['status'] = 0;
            $response['msg'] = __('Invalid Request Type');
            $response['data'] = '';

            $reqData = $this->request->query;

            if ($request->is('post')) {
                $propData = $this->request->data;

                if (isset($propData['Booking']) && !empty($propData['Booking'])) {
                    /*
                      http://connect.bookt.com/ws/?
                      method=get&
                      entity=property&
                      apikey=eff2f1d2-e148-4ca3-8d40-9104fdcd0970&
                      ids=197688 */
                    ///connect/v1/ws/?method=save&entity=booking&apikey=xxxxxx-xxxxxx-xxxxxxx-xxxxxxxx
                    //extract data from the post
                    //set POST variables
                    $url = 'http://connect.bookt.com/ws/?method=save&entity=booking&apikey=' . $apiKey;
                    $fields_string = "";
                    $fields = array(
                        'PropertyID' => urlencode($propData['Booking']['prop_id']),
                        'CheckIn' => urlencode("2015-12-20"),
                        'CheckOut' => urlencode("2015-12-25"),
                        'Renter' => array(
                            'FirstName' => urlencode($propData['Booking']['FirstName']),
                            'LastName' => urlencode($propData['Booking']['LastName']),
                            'PrimaryEmail' => urlencode($propData['Booking']['PrimaryEmail']),
                        ),
                    );

                    //url-ify the data for the POST
                    $fields_string = http_build_query($fields);
                    /*
                      foreach ($fields as $key => $value)
                      {
                      $fields_string .= $key . '=' . $value . '&';
                      }
                      rtrim($fields_string, '&');
                     */
                    //open connection
                    $ch = curl_init();


                    //set the url, number of POST vars, POST data
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, count($fields));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/x-www-form-urlencoded',
                    ));
                    //execute post
                    $result = curl_exec($ch);
                    //prd($result);
                    //close connection
                    curl_close($ch);
                    $this->set('result', $result);
                    $this->set('prop_id', $propData['Booking']['prop_id']);
                }

                //prd($propData);
            }

            if (isset($reqData['prop_id']) && !empty($reqData['prop_id'])) {
                $prop_id = $reqData['prop_id'];
            }

            if ($request->is('get')) {
                $reqData = $this->request->query;
                if (isset($reqData['prop_id']) && !empty($reqData['prop_id'])) {
                    $this->set('prop_id', $reqData['prop_id']);
                }
            }

            $this->set('apiKey', $apiKey);
        } else {
            $this->render('/nodirecturl');
        }
    }

    public function home() {
        
    }

    public function aboutus() {
        $this->loadModel('CmsPage');
        $homeContent = $this->CmsPage->find('first', array('conditions' => array(
                'unique_key' => 'ABOUT_US'
        )));

        $this->set('homeContent', $homeContent);
    }

    public function features() {
        $this->loadModel('CmsPage');
        
        $featuresContent = $this->CmsPage->find('all', array('conditions' => array(
                'parent_key' => 'FEATURES'
        )));
        
		$this->set('removeBreadcrumb', 1);
        $this->set('featuresContent', $featuresContent);
    }

}
