<?php
class UsersController extends AppController {
  public $name = 'Users';
  public $helpers = array('Html', 'Form', 'Paginator');

  public $paginate = array(
    'fields' => array('id', 'username', 'status', 'role', 'created', 'last_login'),
    'limit' => 25
  );

  public function index() {
    $data = $this->paginate('User');
    $this->set('data', $data);
  }

  public function delete($id = null) {
    if($this->User->delete($id)) {
      $this->Session->setFlash('User has been deleted');
    }
    else {
      $this->Session->setFlash('User could not be deleted');
    }
    $this->redirect(array('action' => 'index'));
  }

  public function add() {
    $this->init();
    if($this->request->is('post')) {
      if($this->User->save($this->request->data)) {
        $this->Session->setFlash('User has been added.');
        $this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('User could not be added.');
      }
    }
  }

  public function edit($id = null) {
    $this->init($id);
    if($this->request->is('get')) {
      $this->request->data = $this->User->read();
      $this->request->data['User']['password'] = '';
    }
    else if($this->request->is('put')) {
      if($this->User->save($this->request->data)) {
        $this->Session->setFlash('User has been updated.');
        $this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('User could not be updated.');
      }
    }
  }

  private function init($id = null) {
    $this->User->id = $id;
    $this->set_role_options();
    $this->set_status_options();
  }

  private function set_role_options() {
    $role_options = array();
    foreach(User::roles() as $role) {
      $role_options[$role] = $role;
    }
    $this->set('role_options', $role_options);
  }

  private function set_status_options() {
    $status_options = array();
    foreach(User::statuses() as $status) {
      $status_options[$status] = $status;
    }
    $this->set('status_options', $status_options);
  }
}
?>
