<?php
class ViewList extends AppModel {
  public $name = 'ViewList';
  
  public function items($controller) {
    preg_match_all('/\s*([^\s,*]+)\s*,?/',
      $this->data[$this->alias]['fields'],
      $matches);
    $fields = array();
    foreach($matches[1] as $field) {
      $fields[] = $field;
    }
    $type = $this->data[$this->alias]['type'];
    $controller->loadModel($type);
    $controller->paginate = array(
      'limit' => $this->data[$this->alias]['limit'],
      'order' => $this->data[$this->alias]['order'],
      'fields' => $fields,
      'conditions' => array(
        'type' => $type,
        $this->data[$this->alias]['conditions'])
    );
    return $controller->paginate($type);
  }
}
?>
