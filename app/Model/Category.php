<?php

App::uses('AppModel', 'Model');

class Category extends AppModel {

    /*
    public $virtualFields = array(
        'questionsCount' => 'SELECT COUNT(*) FROM questions as ques WHERE ques.sub_category_id = Category.id'
    );
     * 
     */
    
    /*public $hasMany = array(
        'SubCategories' => array(
            'className' => 'Category',
            'foreignKey' => 'parent_id'
        )
    );*/

}
