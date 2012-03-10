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

  public function loadContent($id) {
    $content = $this->Content->findByIdOrAlias($id, $id);
    if($content) {
      $type = $content['Content']['type'];
      if($type != 'Content') {
        $this->loadModel($type);
        $content = array_merge($content, $this->$type->findByContentId($id));
      }
    }
    return $content;
  }

  public function bindType($content) {
    $type = $content['Content']['type'];
    if($type != 'Content') {
      $this->Content->bindModel(
        array('hasOne' => array(
          $type => array(
            'className' => $type,
            'dependent' => true
          )
        )
      ), false);
    }
  }

  public function display($id = 'home') {
    $this->request->data = $this->loadContent($id);
    if($this->request->data) {
      $view = new View($this);
      $html = $view->loadHelper('Html');
      $this->Content->set($this->request->data);
      $this->Content->replace_tokens($html);
      $this->request->data = array_merge($this->request->data, 
        $this->Content->data);

      $type = $this->request->data['Content']['type'];
      $this->set('title_for_layout', $this->request->data['Content']['title']);
      $this->set('id', $this->request->data['Content']['alias'] ? 
        $this->request->data['Content']['alias'] : 
        $this->request->data['Content']['id']);
      $this->set('content', $this->request->data);
      $this->render($type . '/display');
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
    $content = $this->Content->findById($id);
    if($content) {
      $this->bindType($content);
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

      $this->bindType($this->request->data);
      if($this->Content->saveAll($this->request->data)) {
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
      $this->request->data = $this->loadContent($id);
      if(!$this->request->data) {
        throw new NotFoundException();
      }
      $type = $this->request->data['Content']['type'];
      $this->request->data['Content']['__menu'] = $this->Menu->format_menu_definition($id);
      $this->render($type . '/edit');
    }
    else if($this->request->is('put')) {
      $this->bindType($this->request->data);
      if($this->Content->saveAll($this->request->data)) {
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
