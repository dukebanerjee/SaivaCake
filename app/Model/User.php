<?php
App::uses('AuthComponent', 'Controller/Component');
App::uses('CakeTime', 'Utility');

class User extends AppModel {
  public $name = 'User';

  public $findMethods = array('byCredentials' => true);

  public static function roles() {
    return array('admin', 'author', 'user');
  }

  public static function statuses() {
    return array('pending', 'active', 'blocked');
  }

  public function login($username, $password) {
    $user = $this->find('first', array(
      'conditions' => array(
        'username' => $username,
        'password' => AuthComponent::password($password)
      )
    ));
    if($user) {
      $this->set($user);
      $this->saveField('last_login', date('Y-m-d H:i:s', strtotime('now')));
      return $user;
    }
    return false;
  }

  public function beforeSave() {
    if(isset($this->data[$this->alias]['password'])) {
      $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    }
    return true;
  }
}
?>
