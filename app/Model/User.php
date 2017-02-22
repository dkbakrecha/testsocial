<?php

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {

    public $validate = array(
        'email' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => ' This field is required.'
            ),
            'pattern' => array(
                //'rule' => array('email', true),
                //'rule' => '/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/',
                'rule' => '/^[A-Za-z0-9._%+-]+@([A-Za-z0-9-]+\.)+([A-Za-z0-9]{2,4}|museum)$/',
                'message' => 'Please enter valid email.'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This email address already exists.'
            ),
        ),
        'first_name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'This field is required.'
            ),
        ),
        'last_name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'This field is required.'
            ),
        ),
        'phone_number' => array(
            'required' => array(
                'rule' => array('notEmpty', 'numeric'),
                'message' => 'This Field is required.'
            ),
            'rule1' => array(
                'rule' => 'naturalNumber',
                'message' => 'Please enter valid phone number.'
            ),
            'rule2' => array(
                'rule' => array('between', 8, 25),
                'message' => 'Phone number limit should be 6 to 25.'
            ),
        ),
        'password' => array(
            'passlength' => array(
                'rule' => array('between', 6, 15),
                'message' => 'Password should consist of 6-15 characters.'
            )
        ),
        'confirm_password' => array(
            'passlength' => array(
                'rule' => array('between', 6, 15),
                'message' => 'Passwords do not match'
            ),
            'match' => array(
                'rule' => 'checkpasswords',
                'message' => 'Passwords do not match'
            )
        ),
    );

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }

        return true;
    }
	
	public function getInfo($email){
		$conditions = array(
            'conditions' => array('User.email' => $email, 'User.status' => 1), //array of conditions
            'recursive' => -1, //int
			'fields' => array('id','name','email','role','status')
        );
		
        $user_content = $this->find('first', $conditions);
        if (is_array($user_content) && !empty($user_content)) {
            return $user_content;
        } else {
            return false;
        }
	}

}