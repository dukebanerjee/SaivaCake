<?php
echo $this->Form->create('User', array(
  'url' => '/users/signup',
  'class' => 'basic',
  'id' => 'signup-form',
  'inputDefaults' => array(
    'style' => 'width: 25em;',
    'label' => array(
      'style' => 'width: 7em;'
    )
  )
));

echo $this->Html->tag('fieldset',
  $this->Html->tag('legend', 'Personal Information') .
  $this->Form->input('User.first_name') .
  $this->Form->input('User.last_name') .
  $this->Form->input('User.city') .
  $this->Form->input('User.state') .
  $this->Form->input('User.country'),
  array('escape' => false, 'id' => 'emergency')
);

echo $this->Html->tag('fieldset',
  $this->Html->tag('legend', 'Account Information') .
  $this->Form->input('User.username') .
  $this->Form->input('User.email'),
  array('escape' => false, 'id' => 'emergency')
);

echo $this->Form->end(array('name' => 'Sign Up'));
?>
