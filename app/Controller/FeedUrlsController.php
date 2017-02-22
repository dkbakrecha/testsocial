<?php

App::uses('AppController', 'Controller');

class FeedUrlsController extends AppController {

    public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('');
    }

    public function index() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $postData = $this->request->data;
            if (!empty($postData['FeedUrl']['rss_url'])) {
                if ($this->FeedUrl->save($postData)) {
                    $this->Session->setFlash(__('The Url has been Updated'));
                    return $this->redirect(array('action' => 'index'));
                }
                $this->Session->setFlash(
                        __('The url could not be saved. Please, try again.')
                );
            }
        }
    }

    public function data() {
        $this->layout = 'ajax';

        $request = $this->request;
        $data = $request->data;

        $start = $data['start'];
        $limit = $data['length'];

        $colName = $request->data['order'][0]['column'];
        $orderby[$request->data['columns'][$colName]['name']] = $request->data['order'][0]['dir'];

        $condition = array();
        $condition['FeedUrl.status !='] = 2;

        if (isset($request->data['columns'])) {
            foreach ($request->data['columns'] as $column) {
                if (isset($column['searchable']) && $column['searchable'] == 'true') {
                    if (isset($column['name']) && $column['search']['value'] != '') {
                        $condition[$column['name'] . ' LIKE '] = '%' . filter_var($column['search']['value']) . '%';
                    }
                }
            }
        }
        
        $fields = array('*');

        $joins = array();

        //prd($condition);
        $query = $this->FeedUrl->find('all', array(
            'conditions' => $condition,
            'joins' => $joins,
            'fields' => $fields,
            'order' => $orderby,
            'limit' => $limit,
            'offset' => $start
                ));
        //prd($query);
        $total_records = $this->FeedUrl->find('count', array('conditions' => $condition));

        $dataResult = [];
        $totalRecords = $total_records;
        $sr_no = $start;
        foreach ($query as $row) {
            $d['sr_no'] = ++$sr_no;
            $d['title'] = $row['FeedUrl']['title'];
            $d['rss_url'] = $row['FeedUrl']['rss_url'];
            $d['created'] = $row['FeedUrl']['created'];

            $dataResult[] = $d;
        }

        $returnData = [
            'draw' => $data['draw'],
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $dataResult
        ];
        echo json_encode($returnData);
        exit;
    }

}