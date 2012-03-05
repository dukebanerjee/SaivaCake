<?php
class MenuDisplayComponent extends Component {
  public function beforeRender($controller) {
    $controller->loadModel('Menu');
    $menus = $controller->Menu->find('all', array(
      'conditions' => array('Menu.parent_id' => null),
      'order' => array('Menu.menu_id', 'Menu.index')
    ));
    
    $all_menu_links = array();
    foreach($menus as $menu) {
      $menu_id = $menu['Menu']['menu_id'];
      if(!array_key_exists($menu_id, $all_menu_links)) {
        $all_menu_links[$menu_id] = array(
          'ordered' => array(),
          'unordered' => array()          
        );
      }
      $menu_links = &$all_menu_links[$menu_id];

      if($menu['Menu']['index'] == null) {
        $menu_links['unordered'][] = $menu;
      }
      else {
        $menu_links['ordered'][] = $menu;
      }
    }

    foreach($all_menu_links as $id => &$menu_links) {
      foreach($menu_links['ordered'] as $menu) {
        $menu_links[] = $menu;
      }
      foreach($menu_links['unordered'] as $menu) {
        $menu_links[] = $menu;
      }
      unset($menu_links['ordered']);
      unset($menu_links['unordered']);
    }

    $controller->set('menus', $all_menu_links);
  }
}
?>
