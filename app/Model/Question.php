<?php
App::uses('AppModel', 'Model');

class Question extends AppModel {
    
    /*
	public $belongsTo = array(
        'Quiz' => array(
            'className' => 'Quiz',
            'foreignKey' => 'quiz_id'
        )
    );*/
	
	public $hasMany = array(
        'Answers' => array(
            'className' => 'Answer',
            'foreignKey' => 'question_id'
        )
    );
}