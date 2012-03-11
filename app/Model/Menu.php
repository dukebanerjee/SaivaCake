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

  public function update_menu_definition($content_id, $all_menu_defs, $alias = null) {
    $menu_map = array();
    $ids_to_delete = array(); 

    // Construct a map of existing menu items so we don't recreate menu items that already exist
    $existing_menu_items = $this->find('all');
    foreach($existing_menu_items as $menu_item) {
      $menu_id = $menu_item[$this->alias]['menu_id'];
      $title = $menu_item[$this->alias]['title'];
      $parent_id = $menu_item[$this->alias]['parent_id'];
      $menu_map[$this->menu_map_key($menu_id, $title, $parent_id)] = $menu_item[$this->alias]['id'];

      // Also keep track of all menu items that belongs to $content_id, in case we have to delete them
      if($menu_item[$this->alias]['content_id'] == $content_id) {
        $ids_to_delete[$menu_item[$this->alias]['id']] = true;
      }
    }

    // Menu definition is: <menu-id>|{<title> or <title>|<subtitle>}[index]
    $menu_def_count = preg_match_all('/' .
      '\s*([^|;\[\]]*)\s*' .            // menu ID
      '\|' .                            // separator
      '\s*([^|;\[\]]*)\s*' .            // title
      '(?:\|\s*([^|;\[\]]*)\s*)?' .     // subtitle (optional)
      '(?:\[\s*([0-9]*)\s*\])?\s*;?' .  // index (optional)
      '/', $all_menu_defs, $matches);
    // Parse the menu definitions (if valid or not empty), creating or updating menu definitions in
    // the database.
    if($menu_def_count) {
      for($i = 0; $i < $menu_def_count; $i++) {
        // Create basic menu definition from menu definition string        
        $menu_id = $matches[1][$i];
        $index = $matches[4][$i];
        $title = $matches[2][$i];
        $subtitle = $matches[3][$i];
        $parent_id = null;
        $menu = array($this->alias => array(
          'controller' => 'contents',
          'action' => 'display',
          'parameter' => $alias ? $alias : $content_id,
          'menu_id' => $menu_id,
          'index' => $index,
          'title' => $title,
          'parent_id' => $parent_id,
          'content_id' => $content_id
        ));

        // If the menu definition string has a subtitle, then it is a child menu definition and we 
        // will have to deal with the parent menu definition.
        if(!empty($subtitle)) {
          // Create the parent menu definition if it does not already exist
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
            // Back to insert mode
            unset($this->id);
          }
          $parent_id = $menu_map[$parent_key];
          $title = $subtitle;
          $menu[$this->alias]['parent_id'] = $parent_id;
          $menu[$this->alias]['title'] = $title;
        }

        // Create the menu definition, or update it if it already exists
        $key = $this->menu_map_key($menu_id, $title, $parent_id);
        if(array_key_exists($key, $menu_map)) {
          // Switch to update mode
          $this->id = $menu_map[$key];  
        }
        $this->save($menu);
        $menu_map[$key] = $this->id;
        // Remove this menu definition from consideration of deletion, it's configuration is still
        // in the menu definition string
        unset($ids_to_delete[$this->id]);
        // Back to insert mode
        unset($this->id);
      }
    }

    // Now, remove menu definitions that exist in the database but no longer in the menu definition string
    if(!empty($ids_to_delete)) {
      // Flatten the map so it is now a true array
      $ids_to_delete = array_keys($ids_to_delete);

      // Child menu definitions are always safe to delete
      $this->deleteAll(array(
        $this->alias . '.id' => $ids_to_delete, 
        $this->alias . '.parent_id !=' => null), 
        false, false);
  
      // We can't delete parent menu definitions that still have child menu definitions.
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
      // Add an ID that will never match to ensure that the array doesn't coalesce into a NULL in
      // the SQL query - in PHP, an empty array is equivalent to null.
      $ids_with_children[] = 0;

      // Delete the parent menu definitions that belong to content_id that have no child menu definitions
      $this->deleteAll(array(
        'NOT' => array($this->alias . '.id' => $ids_with_children),
        $this->alias . '.id' => $ids_to_delete    
      ), false, false);

      // Disown parent menu definitions that belong to content_id but still have child menu definitions
      $this->updateAll(array(
          $this->alias . '.controller' => "'contents'",
          $this->alias . '.action' => "'display'",
          $this->alias . '.parameter' => "'home'",
          $this->alias . '.content_id' => null),
        array(
          $this->alias . '.id' => $ids_with_children,
          $this->alias . '.id' => $ids_to_delete)
      );
    }
  }
}
?>
