<?php
  if(array_key_exists($menu_id, $menus)) {  
    $menu_items = $menus[$menu_id];  
?>
<ul class="menu navigation">
<?php foreach($menu_items as $menu_item) { ?>
  <li>
  <?php echo $this->Html->link(
    $this->Html->tag('span', $menu_item['title']), array(
        'controller' => $menu_item['controller'], 
        'action' => $menu_item['action'], 
        $menu_item['parameter']),
      array('escape' => false)
  ); ?>
  </li>
<?php } ?>
</ul>
<?php } ?>
