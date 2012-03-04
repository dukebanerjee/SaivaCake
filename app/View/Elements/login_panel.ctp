<?php
if(!$this->Session->check('Auth.User')) {
?>
<div id="login-panel">
<?php
  echo $this->Form->create('User', array(
    'class' => 'basic login',
    'url' => array('controller' => 'users', 'action' => 'login')
  ));
  echo $this->Form->input('username', array('label' => 'Login here:', 'size' => 15));
  echo $this->Form->input('password', array('label' => false, 'size' => 15));
  echo $this->Form->end(array('label' => 'Enter', 'div' => array('class' => 'login')));
?>
<div id="reset-link">
<?php
  echo $this->Html->link('&raquo; Forgot your password?', 
    array('controller' => 'user', 'action' => 'reset'),
    array('escape' => false)
  );
?>
</div>
</div>
<? } ?>
