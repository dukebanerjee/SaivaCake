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

  public function beforeSave() {
    if(isset($this->data[$this->alias]['password'])) {
      $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    }
    return true;
  }
}
?>
