<h1>Add User</h1>
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
    echo $this->Form->input('role', array(
      'options' => $roles,
      'empty' => '(choose role)'
    ));
    echo $this->Form->input('password');
  ?>
</div>
<?php
echo $this->Form->end('Add User');
?>
