<?php
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
  public $name = 'User';

  public static function roles() {
    return array('admin', 'author', 'user');
  }

  public static function memberFor($created) {
    $d1 = new DateTime('now');
    $d2 = new DateTime($created);
    $interval = $d1->diff($d2);
    if($interval->y > 0) {
      return $interval->y . ' years';
    }
    else if($interval->m > 0) {
      return $interval->m . ' months';
    }
    else if($interval->d > 0) {
      return $interval->d . ' days';
    }
    else if($interval->h > 0) {
      return $interval->h . ' hours';
    }
    else {
      return $interval->i . ' minutes';
    }
  }

  public function beforeSave() {
    if(isset($this->data[$this->alias]['password'])) {
      $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    }
    return true;
  }
}
?>
