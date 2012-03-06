<?php
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
  public static function intervalUntilNow($time) {
    if(empty($time)) {
      return "Never";
    }

    $d1 = new DateTime('now');
    $d2 = new DateTime($time);
    $interval = $d1->diff($d2);

    if($interval->y > 0) {
      return $interval->y . ' years ago';
    }
    else if($interval->m > 0) {
      return $interval->m . ' months ago';
    }
    else if($interval->d > 0) {
      return $interval->d . ' days ago';
    }
    else if($interval->h > 0) {
      return $interval->h . ' hours ago';
    }
    else {
      return $interval->i . ' minutes ago';
    }
  }
}
