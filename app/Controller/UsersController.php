<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {

    public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('home', 'register', 'verification', 'admin_login', 'lost_password');
    }

    public function opauth_complete() {
        debug($this->data);
    }

    public function req_complete() {
        $authCode = trim($this->request->query("code"));


        // Exchange authorization code for access token
        $accessToken = $this->client->authenticate($authCode);
        $this->client->setAccessToken($accessToken);


        pr($accessToken);
        prd($oAuthToken);
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                    __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function edit_profile() {
        if ($this->request->is('post')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                    __('The user could not be saved. Please, try again.')
            );
        }

        $this->request->data = $this->Session->read('Auth');
    }

    public function register() {
        $this->loadModel('EmailContent');
		$this->set('removeBreadcrumb', 1);

        if ($this->request->is('post')) {
            $this->User->create();
            $data = $this->request->data;

            $parts = explode("@", $data['User']['email']);
            $data['User']['name'] = $parts[0];

            $data['User']['role'] = '2';
            $data['User']['verification_code'] = substr(md5(microtime()), rand(0, 26), 6);
            $data['User']['status'] = '3';



            if ($this->User->save($data)) {

                $name = $data['User']['name'];
                $email = $data['User']['email'];
                $key = $data['User']['verification_code'];
                // Initializing Email Model.
                //$emailObj = new EmailContent();
                //$emailObj->registrationMail($name, $email, $key);
                $this->EmailContent->registrationMail($name, $email, $key);

                $this->Session->setFlash(__('User is created successfully. Please check your mail for activication link.'));
                return $this->redirect(array('action' => 'home'));
            }
            $this->Session->setFlash(
                    __('The user could not be saved. Please, try again.')
            );
        }
    }
	
	public function lost_password() {
        $this->loadModel('EmailContent');
		$this->set('removeBreadcrumb', 1);

        if ($this->request->is('post')) {
            $this->User->create();
            $data = $this->request->data;
            
			$_info = $this->User->getInfo($data['User']['email']);
			$_info['User']['verification_code'] = substr(md5(microtime()), rand(0, 26), 6);
			//prd($_info);
			//prd($this->User->save($_info));
            if ($this->User->save($_info)) {
                $name = $_info['User']['name'];
				$email = $_info['User']['email'];
                $key = $_info['User']['verification_code'];
                
				$this->EmailContent->forgetPassword($name, $email, $key);

                $this->Session->setFlash(__('Please check your mail for reset password link.'));
                return $this->redirect(array('action' => 'home'));
            }
            $this->Session->setFlash(
                    __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function verification($key) {

        if (!$key) {

            $this->Session->setFlash(__('Invalid Key.'), 'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('eshop' => true, 'controller' => 'users', 'action' => 'login'));
        }

        $data = array();
        $data = $this->User->find('first', array(
            'conditions' => array('User.verification_code' => $key),
            'fields' => array('id', 'email', 'status', 'verification_code')
        ));
        // prd($data);
        $saveData = array();

        if (isset($data) && !empty($data)) {
            $saveData['User']['id'] = $data['User']['id'];
            $saveData['User']['status'] = 1;
            $saveData['User']['verification_code'] = 1;  // Verified by user

            if ($this->User->save($saveData)) {
                $this->Session->write('Auth.User.status', 1);

                $this->Session->setFlash(__('Verification successful.'), 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }

            $this->Session->setFlash(__('Verification unsuccessful. Please try again Or contact to team@cupcherry.com'), 'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        } else {
            $this->Session->setFlash(__('Verification unsuccessful. Please try again Or contact to team@cupcherry.com'), 'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
    }

    public function index() {
        if (!empty($this->loggedinUser)) {
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
    }

    public function dashboard() {
        
    }

    public function home() {
        $this->layout = "home";
        if (!empty($this->loggedinUser)) {
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        $this->loadModel('CmsPage');
        $this->loadModel('Category');
        $this->loadModel('Post');




        $this->Category->bindModel(
                array('hasMany' => array(
                        'SubCategories' => array(
                            'className' => 'Category',
                            'foreignKey' => 'parent_id',
                            'limit' => 4
                        )
                    )
                )
        );

        $cateList = $this->Category->find('all', array(
            'conditions' => array(
                'Category.status' => 1,
                'Category.parent_id' => 0,
            ),
            'limit' => 4
        ));

        $blogList = $this->Post->find('all', array(
            'conditions' => array(
                'Post.status' => 1,
            ),
            'order' => array('Post.id DESC'),
            'limits' => array('6'),
            'fields' => array('*')
        ));
        //pr($blogList);


        $this->set('cateList', $cateList);
        $this->set('blogList', $blogList);
    }

    public function login() {
		$this->set('removeBreadcrumb', 1);
		
        if (!empty($this->loggedinUser)) {
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        if ($this->request->is('Post')) {
            //prd($this->Auth);
            if ($this->Auth->login()) {
                $userData = array();
                $userData['User']['id'] = $this->Session->read('Auth.User.id');
                $userData['User']['last_login'] = date("Y-m-d H:i:s");
                $this->User->save($userData);


                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Session->setFlash(__('Invalid username or password, try again'));
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    /*     * ******** ADMIN SECTION  ********* */

    public function admin_login() {
        $this->set('title_for_layout', 'Admin Login');

        if (isset($this->Session) && $this->Session->read('Auth.Admin.id') != '') {
            $this->redirect($this->Auth->loginRedirect);
        }

        AuthComponent::$sessionKey = 'Auth.Admin';

        $this->layout = "admin_login";

        if ($this->request->is('post')) {
            $data = $this->request->data;

            if (empty($data['User']['email']) || empty($data['User']['password'])) {
                $this->Session->setFlash('Invalid Login.');
                $this->redirect($this->Auth->loginAction);
            }

            $username_email = $data['User']['email'];

            //$conditions['OR'][0]['email'] = $username_email;
            //$conditions['OR'][1]['username'] = $username_email;
            $conditions['email'] = $username_email;
            $conditions['password'] = AuthComponent::password($data['User']['password']);
            $conditions['role'] = 1;

            $userDetail = $this->User->find("first", array('conditions' => $conditions));

            if (isset($userDetail['User'])) {
                $this->Auth->login($userDetail['User']);

                /*
                  if ($data['User']['Remember_me'] == 1) {
                  $expire = time() + 3600 * 24 * 30;

                  setcookie("username_email", $data['User']['username_email'], $expire);
                  setcookie("password", $data['User']['password'], $expire);
                  setcookie("remember_me", true, $expire);
                  } else {
                  setcookie("username_email", '', -1);
                  setcookie("password", '', -1);
                  setcookie("remember_me", false, -1);
                  }
                 */

                $this->Session->setFlash('Welcom Admin', 'default', array('class' => 'alert alert-success'));
                $this->redirect($this->Auth->loginRedirect);
            } else {
                $this->Session->setFlash('Invalid Login', 'default', array('class' => 'alert alert-danger'));
                $this->redirect($this->Auth->loginAction);
            }
        }
    }

    public function admin_logout() {
        $user = $this->Auth->user();
        $this->Session->destroy();
        $this->Session->setFlash(sprintf(__d('users', '%s you have successfully logged out'), $user[$this->{$this->modelClass}->displayField]), 'default', array('class' => 'alert alert-success'));
        $this->redirect($this->Auth->logout());
    }

    public function admin_dashboard() {
        $this->loadModel('Test');
        $this->loadModel('Note');
        $this->loadModel('Question');
        $_user_statics = array();
        /* Active users */
        $_user_active = $this->User->find('count', array('conditions' => array(
                'role' => 2, 'status' => 1)));

        $_user_inactive = $this->User->find('count', array('conditions' => array(
                'role' => 2, 'status' => 0)));

        $_user_pending = $this->User->find('count', array('conditions' => array(
                'role' => 2, 'status' => 3)));

        /* Notes and Questions */
        $notes_pending = $this->Note->find('count',array('conditions' => array(
            'Note.status' => 3)));

        $question_pending = $this->Question->find('count',array('conditions' => array(
            'Question.status' => 3)));

        $_user_statics['active'] = $_user_active;
        $_user_statics['inactive'] = $_user_inactive;
        $_user_statics['pending'] = $_user_pending;


        $_user_statics['notes_pending'] = $notes_pending;
        $_user_statics['question_pending'] = $question_pending;

        $this->set('user_statics', $_user_statics);


        $_lastlogin_list = $this->User->find('all', array(
            'conditions' => array(
                'role' => 2, 'status' => 1),
            'limit' => 5,
            'order' => array('last_login desc'),
            'fields' => array('email', 'last_login')
        ));

        $this->set('lastlogin_list', $_lastlogin_list);


        $_lastTest_list = $this->Test->find('all', array(
            /* 'conditions' => array(), */
            'limit' => 5,
            'order' => array('Test.id desc'),
        ));
        //pr($_lastTest_list);
        $this->set('lastTest_list', $_lastTest_list);
    }

    public function admin_index() {
        
    }

    public function admin_userGridData($user_type = 2) {

        $request = $this->request;
        $this->autoRender = false;

        if ($request->is('ajax')) {
            $this->layout = 'ajax';

            $page = $request->query('draw');
            $limit = $request->query('length');
            $start = $request->query('start');

            //for order
            $colName = $this->request->query['order'][0]['column'];
            $orderby[$this->request->query['columns'][$colName]['name']] = $this->request->query['order'][0]['dir'];
            //prd($this->request);          
            $condition = array();
            $condition ['User.status !='] = 2;
            $condition ['User.role'] = $user_type;

            //pr($this->request->query['columns']);
            foreach ($this->request->query['columns'] as $column) {

                if (isset($column['searchable']) && $column['searchable'] == 'true') {
                    //pr($column);
                    if ($column['name'] == 'User.date_added' && !empty($column['search']['value'])) {
                        $condition['User.date_added LIKE '] = '%' . Sanitize::clean(date('Y-m-d', strtotime($column['search']['value']))) . '%';
                    } elseif (isset($column['name']) && $column['search']['value'] != '') {
                        $condition[$column['name'] . ' LIKE '] = '%' . Sanitize::clean($column['search']['value']) . '%';
                    }
                }
            }

            //prd($condition);
            $total_records = $this->User->find('count', array('conditions' => $condition));


            $fields = array('User.id', 'User.first_name', 'User.status', 'User.last_name', 'User.email', 'User.created');
            $userData = $this->User->find('all', array(
                'conditions' => $condition,
                'fields' => $fields,
                'order' => $orderby,
                'limit' => $limit,
                'offset' => $start
            ));

            $return_result['draw'] = $page;
            $return_result['recordsTotal'] = $total_records;
            $return_result['recordsFiltered'] = $total_records;


            $return_result['data'] = array();
            if (isset($userData[0])) {
                $i = $start + 1;
                foreach ($userData as $row) {

                    $action = '';
                    $status = '';

                    if ($row['User']['status'] == 0) {
                        $status .= '<i class="fa fa-dot-circle-o fa-lg clr-red" onclick="changeUserStatus(' . $row['User']['id'] . ',0)" title="Change Status"></i>';
                    } else if ($row['User']['status'] == 1) {
                        $status .= '<i class="fa fa-dot-circle-o fa-lg clr-green" onclick="changeUserStatus(' . $row['User']['id'] . ',1)" title="Change Status"></i>';
                    } else if ($row['User']['status'] == 3) {
                        $status .= '<i class="fa fa-dot-circle-o fa-lg clr-orange" onclick="changeUserStatus(' . $row['User']['id'] . ',0)" title="Change Status"></i>';
                    }

                    //$action .= '&nbsp;&nbsp;&nbsp;<a href="#"><i class="fa fa-eye fa-lg"></i></a> ';

                    $action .= '&nbsp;&nbsp;&nbsp;<a href="' . $this->webroot . 'admin/users/edit/' . $row['User']['id'] . '" title="Edit uSER"><i class="fa fa-pencil fa-lg"></i></a> ';

                    $action .= '&nbsp;&nbsp;&nbsp; <a href="#" onclick="delete_user(' . $row['User']['id'] . ')" title="Delete User"><i class="fa fa-trash fa-lg"></i></a>';

                    $chk = '<td><input type="checkbox" name="selected[]" class="chkBox" value="' . $row['User']['id'] . '"/></td>';

                    $return_result['data'][] = array(
                        $chk, //$row['User']['id'],
                        $row['User']['first_name'],
                        $row['User']['last_name'],
                        $row['User']['email'],
                        date(Configure::read('Site.admin_date_format'), strtotime($row['User']['created'])),
                        $status,
                        $action
                    );
                    $i++;
                }
            }
            // pr($return_result);
            echo json_encode($return_result);
            exit;
        } else {
            $this->set('title_for_layout', __('Access Denied'));
            $this->render('/nodirecturl');
        }
    }

    public function admin_deleteUser() {

        if ($this->request->is('ajax')) {

            //$data['User']['status'] = 2;
            //$data['User']['id'] = $this->request->data['id'];   

            if ($this->User->updateAll(array("User.status" => 2), array('User.id' => $this->request->data['id']))) {
                echo '1';
            } else {
                echo '0';
            }
            exit;
        } else {
            $this->set('title_for_layout', __('Access Denied'));
            $this->render('/nodirecturl');
        }
    }

    public function admin_change_status() {

        if ($this->request->is('ajax')) {

            $data['User']['id'] = $this->request->data['id'];
            $userdata = $this->User->find('first', array('conditions' => array(
                    'User.id' => $data['User']['id'])
            ));

            if ($this->request->data['status'] == 2) {
                $this->loadModel("EmailContent");

                $name = ucwords(strtolower($userdata['User']['fname']) . ' ' . strtolower($userdata['User']['lname']));
                $user_name = @$userdata['User']['user_name'];
                $name = empty($user_name) ? $name : $user_name;

                $email = strtolower($userdata['User']['email']);

                $link = Router::url('/', true);

                $modelObj = new EmailContent();
                $modelObj->registrationMailSuccess($name, $email, $link);
            }

            $data['User']['status'] = $this->request->data['status'] == 2 ? 1 : ($this->request->data['status'] == 1 ? 0 : 1);

            if ($this->User->save($data)) {
                echo '1';
            } else {
                echo '0';
            }
            exit;
        } else {
            
        }
    }

    public function admin_setting() {
        $this->set('title_for_layout', 'Manage Setting');
    }

    public function admin_settingData() {

        $request = $this->request;
        $this->autoRender = false;
        $this->loadModel('Sitesetting');
        if ($request->is('ajax')) {
            $this->layout = 'ajax';

            $page = $request->query('draw');
            $limit = $request->query('length');
            $start = $request->query('start');

            //for order
            // $colName=$this->request->query['order'][0]['column'];
            // $orderby[$this->request->query['columns'][$colName]['name']]=$this->request->query['order'][0]['dir'];

            $orderby['Sitesetting.title'] = "ASC";

            $condition = array();
            $condition ['Sitesetting.status'] = 1;
            //pr($this->request->query['columns']);
            foreach ($this->request->query['columns'] as $column) {
                if (isset($column['searchable']) && $column['searchable'] == 'true') {
                    if (isset($column['name']) && $column['search']['value'] != '') {
                        $condition[$column['name'] . ' LIKE '] = '%' . Sanitize::clean($column['search']['value']) . '%';
                    }
                }
            }

            $total_records = $this->Sitesetting->find('count', array('conditions' => $condition));

            $userData = $this->Sitesetting->find('all', array(
                'conditions' => $condition,
                'order' => $orderby,
                'limit' => $limit,
                'offset' => $start
            ));

            $return_result['draw'] = $page;
            $return_result['recordsTotal'] = $total_records;
            $return_result['recordsFiltered'] = $total_records;

            $return_result['data'] = array();
            if (isset($userData[0])) {
                $i = $start + 1;
                foreach ($userData as $row) {

                    $action = '';

                    $action .= '&nbsp;&nbsp;&nbsp;<a href="' . $this->webroot . 'admin/users/editsetting/' . $row['Sitesetting']['id'] . '" title="Edit"><i class="fa fa-edit fa-lg"></i></a> ';

                    $return_result['data'][] = array(
                        $i, //$row['User']['id'],
                        $row['Sitesetting']['title'],
                        $row['Sitesetting']['value'],
                        $action
                    );
                    $i++;
                }
            }
            echo json_encode($return_result);
            exit;
        } else {
            $this->set('title_for_layout', __('Access Denied'));
            $this->render('/nodirecturl');
        }
    }

    public function admin_editsetting($id = '') {
        $this->set('title_for_layout', 'Edit Setting');
        $id == "" ? $this->redirect(array('controller' => 'users', 'action' => 'setting', 'admin' => true)) : "";
        $user_info = $this->Sitesetting->find('first', array('conditions' => array('Sitesetting.id' => $id)));
        if ($this->request->is('post') || $this->request->is('put')) {
            $user_data = $this->request->data;
            if (empty($user_data)) {
                $this->Session->setFlash(__('Setting does not exist'), 'default', array('class' => 'alert alert-danger'));
                $this->redirect(array('controller' => 'users', 'action' => 'setting', 'admin' => true));
            }
            // /$user_data['Partner']['id'] = $this->Session->read('Auth.Admin.id');
            //prd($user_data);

            if ($this->Sitesetting->save($user_data)) {
                $this->Session->setFlash(__('Setting successfully updated.'), 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('controller' => 'users', 'action' => 'setting', 'admin' => true));
            } else {
                $this->Session->setFlash(__('Setting could not be updated. Please try again.'), 'default', array('class' => 'alert alert-danger'));
            }
        } else {
            $this->request->data = $user_info;
        }
    }

}
