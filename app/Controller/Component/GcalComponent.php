<?php
/**
 * Description of GcalComponent
 *
 * @author dharmendra
 */
App::uses('Component', 'Controller');
App::import('Vendor', 'google-api-php-client/autoload');

class GcalComponent extends Component {
    
    var $client = null;
    var $gCal_service = null;
    var $gauth_url = null;
    
    var $c_id = null;
    var $c_secrat = null;
    var $r_url = null; 
    
    public $components = array('Session');

    public function initialize(Controller $controller) {
         $this->Controller = $controller;
    }    
    
    public function googleSettings(){
        $this->client = new Google_Client();
    
        $this->client->setClientId($this->c_id);
        $this->client->setClientSecret($this->c_secrat);
        $this->client->setRedirectUri($this->r_url);
        
        $this->client->setScopes("https://www.googleapis.com/auth/plus.login");
        $this->client->addScope('https://www.googleapis.com/auth/calendar');
        $this->gCal_service = new Google_Service_Calendar($this->client);
    }
    
    
    function makesync(){
        $this->gauth_url = $this->client->createAuthUrl();
        $authToken = $this->Session->read("authToken");
        
        if(isset($authToken) && !empty($authToken)){
            $this->client->setAccessToken($authToken);
            if($this->client->isAccessTokenExpired()) {
                header('Location: ' . filter_var($this->gauth_url, FILTER_SANITIZE_URL));
            }
        }else{
            $this->Controller->redirect($this->gauth_url);
        }
    }
    
    function setAuthCode($code = null){
        $authCode = trim( $code );
        $accessToken = $this->client->authenticate($authCode);
        $this->Session->write("authToken", $accessToken);
    }
    
    function insertEvent($calId = null, $eventDesc = null) {
        $calender_id = $calId;

        $event = new Google_Service_Calendar_Event();
        $event->setSummary($eventDesc['eventSummary']);
        $event->setLocation($eventDesc['eventWhere']);
        $start = new Google_Service_Calendar_EventDateTime();
        $startDate = date("c", strtotime($eventDesc['startDate']));
        $start->setDateTime($startDate);
        $event->setStart($start);
        $end = new Google_Service_Calendar_EventDateTime();
        $endDate = date("c", strtotime($eventDesc['endDate']));
        $end->setDateTime($endDate);
        $event->setEnd($end);
        $createdEvent = $this->gCal_service->events->insert($calender_id, $event);

        return $createdEvent->getId();
    }
    
    function deleteEvent($calId = null, $eventId = null){
        $this->gCal_service->events->delete($calId, $eventId);
    }

}