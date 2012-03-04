<?php
class Content extends AppModel {
  public $name = 'Content';
  
  public $belongsTo = array(
    'Author' => array(
      'className' => 'User',
      'foreignKey' => 'author_id',
      'fields' => array('username') 
    )
  );

  public $hasMany = array(
    'Menu' => array(
      'fields' => array('menu_id', 'title', 'index', 'parent_id')
    )
  );
}
?>
