<?php

class CommonHelper extends AppHelper {
    /*
     * For use db Value directly in View 
     */

    function get_category_by_id($cate_id) {
        App::import("Model", "Category");
        $model = new Category();
        $categoryInfo = $model->findById($cate_id);
        return $categoryInfo['Category']['title'];
    }

}
