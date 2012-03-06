<h1 class="title"><?php echo $this->fetch('title'); ?></h1>
<?php
echo $this->Form->create('Content', array(
  'class' => 'basic',
  'inputDefaults' => array(
    'style' => 'width: 25em',
    'label' => array(
      'style' => 'width: 10em'
    )
  )
));
echo $this->fetch('id');
echo $this->Form->input('title');
echo $this->Form->input('summary', array('rows' => 1));
echo $this->Form->input('content', array('rows' => 20, 'columns' => 30, 'style' => 'width: 695px'));
echo $this->Form->input('alias');
echo $this->Form->input('__menu', array('type' => 'textarea', 'rows' => 1));
echo $this->Form->submit($this->fetch('submit_label'));
echo $this->Html->link('Cancel', array('controller' => 'contents', 'action' => 'index'), array('class' => 'cancel'));
echo $this->Form->end();
?>
