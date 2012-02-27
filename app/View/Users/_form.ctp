<h1><?php echo $this->fetch('title'); ?></h1>
<?php
echo $this->Form->create('User', array(
  'class' => 'basic',
  'inputDefaults' => array(
    'style' => 'width: 25em',
    'label' => array(
      'style' => 'width: 10em'
    )
  )
));
?>
<?php echo $this->fetch('id'); ?>
<div>
  <h2>Personal Information</h2>
  <?php
    echo $this->Form->input('first_name');
    echo $this->Form->input('last_name');
    echo $this->Form->input('city');
    echo $this->Form->input('state');
    echo $this->Form->input('country');
    echo $this->Form->input('email');
  ?>
</div>
<div>
  <h2>Account Information</h2>
  <?php
    echo $this->Form->input('username');
    echo $this->Form->input('password');
    echo $this->Form->input('__confirm_password', array('type' => 'password'));
    echo $this->Form->input('role', array(
      'options' => $role_options,
      'empty' => '(choose role)'
    ));
    echo $this->Form->input('status', array(
      'options' => $status_options,
      'empty' => '(choose status)'
    ));
  ?>
</div>
<?php
echo $this->Form->submit($this->fetch('submit_label'));
echo $this->Html->link('Cancel', array('controller' => 'users', 'action' => 'index'), array('class' => 'cancel'));
echo $this->Form->end();
?>
