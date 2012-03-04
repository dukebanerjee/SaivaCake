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

  public function format_menu_definition($content_id) {
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

  public function menu_map_key($menu_id, $title, $parent_id) {
    return $menu_id . '|' . $title . '|' . $parent_id;
  }

  public function update_menu_definition($content_id, $all_menu_defs) {
    $menu_map = array();
    $ids_to_delete = array(); 
    $existing_menu_items = $this->find('all');
    foreach($existing_menu_items as $menu_item) {
      $menu_id = $menu_item[$this->alias]['menu_id'];
      $title = $menu_item[$this->alias]['title'];
      $parent_id = $menu_item[$this->alias]['parent_id'];
      $menu_map[$this->menu_map_key($menu_id, $title, $parent_id)] = $menu_item[$this->alias]['id'];

      if($menu_item[$this->alias]['content_id'] == $content_id) {
        $ids_to_delete[$menu_item[$this->alias]['id']] = true;
      }
    }

    // menu definition is: <menu-id>|{<title> or <title>|<subtitle>}[index]
    $menu_def_count = preg_match_all('/' .
      '\s*([^|;\[\]]*)\s*' .            // menu ID
      '\|' .                            // separator
      '\s*([^|;\[\]]*)\s*' .            // menu item name
      '(?:\|\s*([^|;\[\]]*)\s*)?' .     // optional second level
      '(?:\[\s*([0-9]*)\s*\])?\s*;?' .  // optional index
      '/', $all_menu_defs, $matches);
    if($menu_def_count) {
      for($i = 0; $i < $menu_def_count; $i++) {
        $menu_id = $matches[1][$i];
        $index = $matches[4][$i];
        $title = $matches[2][$i];
        $subtitle = $matches[3][$i];
        $parent_id = null;
        $menu = array($this->alias => array(
          'controller' => 'contents',
          'action' => 'display',
          'parameter' => $content_id,
          'menu_id' => $menu_id,
          'index' => $index,
          'title' => $title,
          'parent_id' => $parent_id,
          'content_id' => $content_id
        ));

        if(!empty($subtitle)) {
          $parent_key = $this->menu_map_key($menu_id, $title, null);
          if(!array_key_exists($parent_key, $menu_map)) {
            $parent_menu = array($this->alias => array(
              'controller' => 'contents',
              'action' => 'display',
              'parameter' => 'home',
              'menu_id' => $menu_id,
              'title' => $title,
              'parent_id' => null));
            $this->save($parent_menu);
            $menu_map[$parent_key] = $this->id;
            unset($this->id);
          }
          $parent_id = $menu_map[$parent_key];
          $title = $subtitle;
          $menu[$this->alias]['parent_id'] = $parent_id;
          $menu[$this->alias]['title'] = $title;
        }

        $key = $this->menu_map_key($menu_id, $title, $parent_id);
        if(array_key_exists($key, $menu_map)) {
          $this->id = $menu_map[$key];  
        }
        $this->save($menu);
        $menu_map[$key] = $this->id;
        unset($ids_to_delete[$this->id]);
        unset($this->id);
      }
    }
    $ids_to_delete = array_keys($ids_to_delete);
    if(!empty($ids_to_delete)) {
      $this->deleteAll(array(
        $this->alias . '.id' => $ids_to_delete, 
        $this->alias . '.parent_id !=' => null), 
        false, false);
  
      $menu_ids_with_children = $this->find('all', array(
        'fields' => array('parent_id'),
        'conditions' => array(
          $this->alias . '.parent_id' => $ids_to_delete
        )
      ));
      $ids_with_children = array();
      foreach($menu_ids_with_children as $menu_id) {
        $ids_with_children[] = $menu_id['Menu']['parent_id'];      
      }
      $ids_with_children[] = 0;

      $this->deleteAll(array(
        'NOT' => array($this->alias . '.id' => $ids_with_children),
        $this->alias . '.id' => $ids_to_delete    
      ), false, false);

      $this->updateAll(array(
          $this->alias . '.controller' => "'contents'",
          $this->alias . '.action' => "'display'",
          $this->alias . '.parameter' => "'home'",
          $this->alias . '.content_id' => null),
        array(
          $this->alias . '.id' => $ids_with_children,
          $this->alias . '.id' => $ids_to_delete
        ));
    }
  }
}
?>
