<?php
class ContentsController extends AppController {
  public $name = 'Contents';
  
  public $helpers = array('Html', 'Form', 'Paginator', 'Session', 'Time'); 

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
    $this->Content->set($content);
    $this->set('Content', $this->Content);
    if($content) {
      $type = $content['Content']['type'];
      $this->loadModel($type);
      if($this->$type->name != 'Content') {
        $extended = $this->$type->findByContentId($content['Content']['id']);
        $content = array_merge($content, $extended);
        $this->$type->set($extended);
        $this->set($type, $this->$type);
      }
    }
    return $content;
  }

  public function bindType($content) {
    $type = $content['Content']['type'];
    $this->loadModel($type);
    if($this->$type->name != 'Content') {
      $this->Content->bindModel(
        array('hasOne' => array(
          $type => array(
            'className' => $type,
            'dependent' => true
          )
        )
      ), false);
    }
    return $type;
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
      $this->set('controller', $this);
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
    $content = $this->Content->findByIdOrAlias($id, $id);
    if($content) {
      $this->bindType($content);
      if($this->Content->delete($content['Content']['id'], true)) {
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
      $type = $this->request->query['type'];
      $this->loadModel($type);
      $this->set('name', $this->$type->alias);
      $this->render($type . '/add');
    }
    else if($this->request->is('post')) {
      $this->request->data['Content']['status'] = 'published';
      $this->request->data['Content']['author_id'] = $this->Auth->user('id');

      $this->bindType($this->request->data);
      if($this->Content->saveAll($this->request->data)) {
        $this->Session->setFlash('Content has been added.');
        $this->loadModel('Menu');
        $this->Menu->update_menu_definition($this->Content->id, $this->request->data['Content']['__menu'], $this->request->data['Content']['alias']);
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
      $id = $this->Content->id = $this->request->data['Content']['id'];
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
        $this->Menu->update_menu_definition($id, $this->request->data['Content']['__menu'], $this->request->data['Content']['alias']);
        $this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('Content could not be updated.');
      }
    }
  }
}
?>
