<?php
  if(array_key_exists($menu_id, $menus)) {  
    $menu_items = $menus[$menu_id];  
?>
<ul class="menu <?php if(isset($class)) { echo $class; } ?>">
<?php $first = true; foreach($menu_items as $menu_item) { ?>
  <li class="<?php if($first) { echo 'first'; $first = false; } ?>">
  <?php echo $this->Html->link(
      $this->Html->tag('span', $menu_item['Menu']['title']), 
      array(
        'controller' => $menu_item['Menu']['controller'], 
        'action' => $menu_item['Menu']['action'], 
        $menu_item['Menu']['parameter']
      ),
      array('escape' => false)
  ); ?>
  </li>
<?php } ?>
</ul>
<?php } ?>
