<?php
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
  public $name = 'User';

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
  
  public function get_name() {
    if(array_key_exists('first_name', $this->data[$this->alias]) && 
       array_key_exists('last_name', $this->data[$this->alias])) {
      return $this->data[$this->alias]['first_name'] . ' ' . $this->data[$this->alias]['last_name'];
    } else {
      return $this->data[$this->alias]['username'];
    }
  }
  
  public function random_password() {
    $chars = "0123456789abcdefghijklmnopqrstuvwyz$%_!";
    $password = '';
    for($i = 0; $i < 28; $i++) {
      $password .= substr($chars, rand(0, strlen($chars)), 1);
    }
    return $password;
  }
  
  public function initial_signup() {
    $this->data[$this->alias]['password'] = $this->random_password();
    $this->data[$this->alias]['status'] = 'pending';
  }
  
  public function activate($request) {
    $this->set($request->data);
    $this->data[$this->alias]['status'] = 'active';
  }
}
?>
