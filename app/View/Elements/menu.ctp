<?php
  $params = $this->request->params;
  $controller = $params['controller'];
  $action = $params['action'];
  $parameter = empty($params['pass']) ? null : $params['pass'][0];

  if(array_key_exists($menu_id, $menus)) {  
    $menu_items = $menus[$menu_id];  
    if(!empty($menu_items)) {
      $list_items = '';
      $first = true;
      foreach($menu_items as $menu_item) {
        $active = $controller == $menu_item['Menu']['controller'] &&
            $action == $menu_item['Menu']['action'] &&
            $parameter == $menu_item['Menu']['parameter'];
        $active_parent = false;        
        $child_menu = '';
        if($show_children && !empty($menu_item['children'])) {
          $child_list_items = '';
          $first_child = true;
          foreach($menu_item['children'] as $child_menu_item) { 
            $active = $controller == $child_menu_item['Menu']['controller'] &&
                    $action == $child_menu_item['Menu']['action'] &&
                    $parameter == $child_menu_item['Menu']['parameter'];
            if($active) $active_parent = true;
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
                  'class' => $active ? 'active' : ''
                )
              ),
              array('escape' => false)
            ) . "\n";
            $child_menu = $this->Html->tag('ul', $child_list_items, array('class' => 'menu', 'escape' => false));
          }
        }

        $menu_class = '';
        if($active) {
          $menu_class .= 'active';
        }
        if($active_parent) {
          $menu_class .= ' active-parent';
        }

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
      echo $this->Html->tag('ul', $list_items, array('class' => 'menu ' . $class, 'escape' => false));
    }
  }
?>
