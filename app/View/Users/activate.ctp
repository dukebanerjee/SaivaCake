<?php
echo $this->Form->create('User', array(
  'class' => 'basic',
  'inputDefaults' => array(
    'style' => 'width: 15em;',
    'div' => 'clear: both;',
    'label' => array(
      'style' => 'width: 10em;'
    )
  )
));
echo $this->Form->input('User.id');
echo $this->Form->input('User.password');
echo $this->Form->input('User.__repeat_password', array('type' => 'password'));
echo $this->Form->end(array('name' => 'Submit'));
?>
