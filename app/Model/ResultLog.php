<?php
App::uses('AppModel', 'Model');

class ResultLog extends AppModel {
    
    
	public $belongsTo = array(
        'Quiz' => array(
            'className' => 'Quiz',
            'foreignKey' => 'quiz_id'
        )
    );
	
	
}