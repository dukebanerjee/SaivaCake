<?php
class UsersController extends AppController {
  public $name = 'Users';
  public $helpers = array('Html', 'Form', 'Paginator');

  public $paginate = array(
    'fields' => array('id', 'username', 'role', 'created'),
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
    $this->User->id = null;
    $this->set_roles();
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
    $this->User->id = $id;
    $this->set_roles();
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

  private function set_roles() {
    $role_options = array();
    foreach(User::roles() as $role) {
      $role_options[$role] = $role;
    }
    $this->set('roles', $role_options);
  }
}
?>
