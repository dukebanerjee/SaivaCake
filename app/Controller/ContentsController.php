<?php
class ContentsController extends AppController {
  public $name = 'Contents';
  public $helpers = array('Html', 'Form', 'Paginator', 'Session');

  public $paginate = array(
    'fields' => array('id', 'title', 'type', 'status', 'author_id', 'created', 'modified', 'Author.username'),
    'limit' => 25
  );

  public function index() {
    $data = $this->paginate();
    $this->set('contents', $data);
  }

  public function delete($id = null) {
    if($this->Content->delete($id)) {
      $this->Session->setFlash('Content has been deleted');
    }
    else {
      $this->Session->setFlash('Content could not be deleted');
    }
    $this->redirect(array('action' => 'index'));
  }

  public function add() {
    if($this->request->is('post')) {
      $this->request->data['Content']['status'] = 'published';
      $this->request->data['Content']['type'] = 'contents';
    
      if($this->Content->save($this->request->data)) {
        $this->Session->setFlash('Content has been added.');
        $this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('Content could not be added.');
      }
    }
  }

  public function edit($id) {
    $this->Content->id = $id;
    if($this->request->is('get')) {
      $this->request->data = $this->Content->read();
    }
    else if($this->request->is('put')) {
      if($this->Content->save($this->request->data)) {
        $this->Session->setFlash('Content has been updated.');
        $this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('Content could not be updated.');
      }
    }
  }
}
?>
