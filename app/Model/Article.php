<?php

App::uses('AppModel', 'Model');

class Article extends AppModel {

    public $validate = array(
        'guid' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This id ready exist already exists.'
            ),
        ),
    );

    public function getInfo($email) {
        $conditions = array(
            'conditions' => array('User.email' => $email, 'User.status' => 1), //array of conditions
            'recursive' => -1, //int
            'fields' => array('id', 'name', 'email', 'role', 'status')
        );

        $user_content = $this->find('first', $conditions);
        if (is_array($user_content) && !empty($user_content)) {
            return $user_content;
        } else {
            return false;
        }
    }

}