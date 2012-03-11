<?php
echo $this->Form->create('Newsletter', array(
  'url' => '/forms/submit',
  'class' => 'basic',
  'id' => 'newsletter-form',
  'inputDefaults' => array(
    'style' => 'width: 25em;',
    'label' => array(
      'style' => 'width: 20em;'
    )
  )
));
echo $this->Form->input('Newsletter.email', array(
  'label' => array(
    'text' => 'Sign up for your newsletter:',
    'style' => 'margin-right: 5px')
));
echo $this->Form->end(array('name' => 'Submit'));
?>
