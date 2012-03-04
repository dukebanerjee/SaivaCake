<?php
App::uses('Controller', 'Controller');

  class AppController extends Controller {
  public $components = array(
    'MenuDisplay',
    'Session',
    'Auth' => array(
      'logoutRedirect' => array('controller' => 'contents', 'action' => 'display', 'home')           
    )
  );

  public function beforeFilter() {
    $this->Auth->allow('*');
  }
}
