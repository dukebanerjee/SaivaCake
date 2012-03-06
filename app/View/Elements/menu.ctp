<?php
  // Gather the context of the current page
  $params = $this->request->params;
  $controller = $params['controller'];
  $action = $params['action'];
  $parameter = empty($params['pass']) ? null : $params['pass'][0];

  // Generate the menu, if it exists and has menu items
  if(array_key_exists($menu_id, $menus)) {  
    $menu_items = $menus[$menu_id];  
    if(!empty($menu_items)) {

      // Generate each menu item
      $list_items = '';
      $first = true;
      foreach($menu_items as $menu_item) {
        // Generate the child menu, if requested and has menu items
        // If any child menu item is active, then the parent menu is an "active parent"
        $active_parent = false;
        $child_menu = '';
        if($show_children && !empty($menu_item['children'])) {

          // Generate each child menu item
          $child_list_items = '';
          $first_child = true;
          foreach($menu_item['children'] as $child_menu_item) { 
            // Set the class for the child menu item. It is be active if it matches the context 
            // of the current page.
            $active = $controller == $child_menu_item['Menu']['controller'] &&
                    $action == $child_menu_item['Menu']['action'] &&
                    $parameter == $child_menu_item['Menu']['parameter'];
            $child_menu_class = '';
            if($active) {
              $child_menu_class .= 'active';
            }
            if($first_child) {
              $child_menu_class .= 'first';
            }

            // If the child is active, then the parent will be "active parent"
            if($active) $active_parent = true;

            // Generate each child menu item as <li><span><link></span></li>
            $child_list_items .= $this->Html->tag('li',
              $this->Html->link(
                $this->Html->tag('span', $child_menu_item['Menu']['title']), 
                array(
                  'controller' => $child_menu_item['Menu']['controller'], 
                  'action' => $child_menu_item['Menu']['action'], 
                  $child_menu_item['Menu']['parameter']
                ),
                array(
                  'escape' => false,
                  'class' => $child_menu_class
                )
              ),
              array('escape' => false)
            ) . "\n";

            $first_child = false;
          }

          // Generate the complete child menu wrapped in <UL>
          $child_menu = $this->Html->tag('ul', $child_list_items, array('class' => 'menu', 'escape' => false));
        }

        // Set the class for the parent menu item. It is active if it matches the context of the 
        // current page and "active parent" if any of its children is the current page shown.
        $active = $controller == $menu_item['Menu']['controller'] &&
            $action == $menu_item['Menu']['action'] &&
            $parameter == $menu_item['Menu']['parameter'];
        $menu_class = '';
        if($active) {
          $menu_class .= 'active';
        }
        if($active_parent) {
          $menu_class .= ' active-parent';
        }
        if($first) {
          $menu_class .= ' first';
        }

        // Generate each menu item as <li><span><link></span>{child menu}</li>
        $list_items .= $this->Html->tag('li',
          $this->Html->link(
            $this->Html->tag('span', $menu_item['Menu']['title']), 
            array(
              'controller' => $menu_item['Menu']['controller'], 
              'action' => $menu_item['Menu']['action'], 
              $menu_item['Menu']['parameter']
            ),
            array(
              'escape' => false,
              'class' => $menu_class
            )
          ) . "\n" . $child_menu,
          array('escape' => false)
        ) . "\n";

        $first = false;
      }

      // Render the entire menu structure
      echo $this->Html->tag('ul', $list_items, array('class' => 'menu ' . $class, 'escape' => false));
    }
  }
?>
