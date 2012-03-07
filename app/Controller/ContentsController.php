<?php
class ContentsController extends AppController {
  public $name = 'Contents';
  
  public $helpers = array('Html', 'Form', 'Paginator', 'Session'); 

  public $paginate = array(
    'fields' => array('id', 'title', 'type', 'status', 'author_id', 'created', 'modified', 'Author.username'),
    'limit' => 25
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->deny();
    $this->Auth->allow('display');
  }

  public function get_type($id) {
      return $this->Content->find('first', array(
        'conditions' => array('OR' => array('Content.id' => $id, 'Content.alias' => $id)),
        'fields' => array('type')
      ));
  }

  public function display($id = 'home') {
    $content = false;    
    $content = $this->get_type($id);
    if($content) {
      $type = $content['Content']['type'];
      $this->loadModel($type);
      $content = $type == 'Content' ? 
        $this->Content->findByIdOrAlias($id, $id) : 
        $this->$type->findByContentId($id);
    }
    if($content) {
      $view = new View($this);
      $html = $view->loadHelper('Html');
      $this->Content->set($content);
      $this->Content->replace_tokens($html);

      $this->set('title_for_layout', $content['Content']['title']);
      $this->set('id', empty($content['Content']['alias']) ? $content['Content']['id'] : $content['Content']['alias']);
      $this->set('content', $this->Content->data);
      $this->render($content['Content']['type'] . '/display');
    }
    else if($id == 'home') {
      $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
    }
    else {
      throw new NotFoundException();    
    }
  }

  public function index() {
    $content_types = array();
    foreach($this->Content->content_types() as $content_type) {
      $content_types[$content_type] = $content_type;
    }

    $data = $this->paginate();
    $this->set('contents', $data);
    $this->set('content_type_options', $content_types);
  }

  public function delete($id = null) {
    $deleted = false;    
    $content = $this->get_type($id);
    if($content) {
      if($content['Content']['type'] != 'Content') {
        $this->Content->bindModel(
          array('hasOne' => array(
            $content['Content']['type'] => array(
              'className' => $content['Content']['type'],
              'dependent' => true
            )
          )
        ), false);
      }
      $this->Content->bindModel(
        array('hasOne' => array(
          'Menu' => array(
            'className' => 'Menu',
            'dependent' => true
          )
        )
      ), false);
      if($this->Content->delete($id, true)) {
        $this->Session->setFlash('Content has been deleted');
        $deleted = true;
      }
    }
    if(!$deleted) {
      $this->Session->setFlash('Content could not be deleted');
    }
    $this->redirect(array('action' => 'index'));
  }

  public function add() {
    if($this->request->is('get')) {
      $model = $this->request->query['type'];
      $this->loadModel($model);
      $this->render($model . '/add');
    }
    else if($this->request->is('post')) {
      $this->request->data['Content']['status'] = 'published';
      $this->request->data['Content']['author_id'] = $this->Auth->user('id');

      $type = $this->request->data['Content']['type'];
      $this->loadModel($type);
      if($this->$type->saveAll($this->request->data)) {
        $this->Session->setFlash('Content has been added.');
        $this->loadModel('Menu');
        $this->Menu->update_menu_definition($this->Content->id, $this->request->data['Content']['__menu']);
        $this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('Content could not be added.');
      }
    }
  }

  public function edit($id) {
    $this->loadModel('Menu');

    $this->Content->id = $id;
    if($this->request->is('get')) {
      $this->request->data = $this->get_type($id);
      if(!$this->request->data) {
        throw new NotFoundException();
      }

      $type = $this->request->data['Content']['type'];
      $this->loadModel($type);
      $this->request->data = $type == 'Content' ? 
        $this->Content->findById($id) : 
        $this->$type->findByContentId($id);
      if(!$this->request->data) {
        throw new NotFoundException();
      }

      $this->request->data['Content']['__menu'] = $this->Menu->format_menu_definition($id);
      $this->render($type . '/edit');
    }
    else if($this->request->is('put')) {
      $type = $this->request->data['Content']['type'];
      $this->loadModel($type);
      if($this->$type->saveAll($this->request->data)) {
        $this->Session->setFlash('Content has been updated.');
        $this->Menu->update_menu_definition($id, $this->request->data['Content']['__menu']);
        $this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('Content could not be updated.');
      }
    }
  }
}
?>
