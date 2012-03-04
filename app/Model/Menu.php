<?php
class Menu extends AppModel {
  public $name = 'Menu';

  public $belongsTo = array(
    'Parent' => array(
      'className' => 'Menu',
      'foreignKey' => 'parent_id',
      'fields' => array('menu_id', 'title', 'index') 
    )
  );

  public function format_menu_item() {
    $menu_def = $this->data[$this->alias]['menu_id'] . '|';
    $title = $this->data['Parent']['title'];
    if(!empty($title)) {
      $menu_def = $menu_def . $title . '|';
    }
    $menu_def = $menu_def . $this->data[$this->alias]['title'];
    $index = $this->data[$this->alias]['index'];
    if($index != null) {
      $menu_def = $menu_def . '[' . $index . ']';
    }
    return $menu_def . '; ';
  }
}
?>
