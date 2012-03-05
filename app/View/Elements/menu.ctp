<?php
  $params = $this->request->params;
  $controller = $params['controller'];
  $action = $params['action'];
  $parameter = empty($params['pass']) ? null : $params['pass'][0];

  if(array_key_exists($menu_id, $menus)) {  
    $menu_items = $menus[$menu_id];  
?>
<ul class="menu <?php if(isset($class)) { echo $class; } ?>">
<?php $first = true; foreach($menu_items as $menu_item) { 
$active = $controller == $menu_item['Menu']['controller'] &&
          $action == $menu_item['Menu']['action'] &&
          $parameter == $menu_item['Menu']['parameter'];
?>
  <li class="<?php if($first) { echo 'first'; $first = false; } ?>">
    <?php echo $this->Html->link(
      $this->Html->tag('span', $menu_item['Menu']['title']), 
      array(
        'controller' => $menu_item['Menu']['controller'], 
        'action' => $menu_item['Menu']['action'], 
        $menu_item['Menu']['parameter']
      ),
      array(
        'escape' => false,
        'class' => $active ? "active" : ""
      )
    ); 
    if($show_children && !empty($menu_item['children'])) { ?>
    <ul class="menu">
      <?php $first_child = true; foreach($menu_item['children'] as $child_menu_item) { 
          $active = $controller == $child_menu_item['Menu']['controller'] &&
                    $action == $child_menu_item['Menu']['action'] &&
                    $parameter == $child_menu_item['Menu']['parameter'];
      ?>
        <li class="<?php if($first_child) { echo 'first'; $first_child = false; } ?>">
          <?php echo $this->Html->link(
            $this->Html->tag('span', $child_menu_item['Menu']['title']), 
            array(
              'controller' => $child_menu_item['Menu']['controller'], 
              'action' => $child_menu_item['Menu']['action'], 
              $child_menu_item['Menu']['parameter']
            ),
            array(
              'escape' => false,
              'class' => $active ? "active" : ""
            )
          ); ?> 
        </li>
      <?php } ?>
    </ul>
    <?php } ?>
  </li>
<?php } ?>
</ul>
<?php } ?>
