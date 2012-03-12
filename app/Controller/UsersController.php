<?php
App::uses('AuthComponent', 'Controller/Component');
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController {
  public $name = 'Users';
  public $helpers = array('Html', 'Form', 'Paginator', 'Session');

  public $paginate = array(
    'fields' => array('id', 'username', 'status', 'role', 'created', 'last_login'),
    'limit' => 25
  );

  public function login() {
    if($this->request->is('get')) {
      $this->redirect('/');
    }
    else {
      if(!$this->Auth->login()) {
        $this->Session->setFlash('Unknown username and password');
      }
      $this->redirect($this->referer());
    }
  }

  public function logout() {
      $this->redirect($this->Auth->logout());
  }
  
  public function signup() {
      $redirect = '/contents/display/home';
      $this->loadModel('Form');
      $form_config = $this->Form->findByType('User');
      $this->User->set($this->request->data);
      $this->User->initial_signup();
      $user = $this->User->save();
      if($user) {
        if($form_config) {
          if($form_config['Form']['success_page']) {
            $redirect = $form_config['Form']['success_page'];
          }
          if($form_config['Form']['success_email']) {
            $email = new CakeEmail();
            $email->template('signup');
            $email->viewVars(array(
              'first_name' => $user['User']['first_name'],
              'last_name' => $user['User']['last_name'],
              'url' => '/users/verify/' . $this->User->id . '/' . $user['User']['password']
            ));
            $email->emailFormat('text');
            $email->subject('Please activate your account');
            $email->to($user['User']['email']);
            $email->from($form_config['Form']['success_email']);
            $email->send();
          }
        }
      }
      else {
        $this->Session->setFlash('Unable to sign up');
      }
      $this->redirect($redirect);
  }

  public function index() {
    $data = $this->paginate('User');
    $this->set('users', $data);
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
