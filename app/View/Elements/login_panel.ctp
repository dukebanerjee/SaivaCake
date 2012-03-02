<div id="login-panel">
<?php
if($this->Session->check('Auth.User')) {
  echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'));  
}
else {
  echo $this->Form->create('User', array(
    'class' => 'basic login',
    'url' => array('controller' => 'users', 'action' => 'login')
  ));
  echo $this->Form->input('username', array('label' => 'Login here:'));
  echo $this->Form->input('password', array('label' => false));
  echo $this->Form->end(array('label' => 'Login', 'div' => array('class' => 'login')));
}
?>
</div>
