<?php

App::uses('AppModel', 'Model');

class Note extends AppModel {

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields' => array('id', 'name', 'email', 'image')
        )
    );

}
