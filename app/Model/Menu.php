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

  public function format_menu_item($menu_item) {
    $menu_def = $menu_item[$this->alias]['menu_id'] . '|';
    $title = $menu_item['Parent']['title'];
    if(!empty($title)) {
      $menu_def = $menu_def . $title . '|';
    }
    $menu_def = $menu_def . $menu_item[$this->alias]['title'];
    $index = $menu_item[$this->alias]['index'];
    if($index != null) {
      $menu_def = $menu_def . '[' . $index . ']';
    }
    return $menu_def . '; ';
  }

  public function get_menu_definition($content_id) {
    $menu_items = $this->find('threaded', array(
        'conditions' => array('Menu.content_id' => $content_id)
    ));
    $menu_defs = '';
    foreach($menu_items as $menu_item) {
      if($menu_item[$this->alias]['content_id'] == $content_id) {
        $menu_defs = $menu_defs . $this->format_menu_item($menu_item);
        foreach($menu_item['children'] as $child_menu_item) {
          $menu_defs = $menu_defs . $this->format_menu_item($child_menu_item);
        }
      }
    }
    return rtrim($menu_defs, '; ');
  }
}
?>
