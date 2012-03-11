<h1 class="title"><?php echo $this->fetch('title'); ?></h1>
<?php
echo $this->Form->create('Form', array(
  'class' => 'basic',
  'inputDefaults' => array(
    'style' => 'width: 25em;',
    'label' => array(
      'style' => 'width: 10em;'
    )
  )
));
echo $this->Form->input('Content.type', array('type' => 'hidden', 'value' => 'Form'));
echo $this->fetch('id');
echo $this->Form->input('Content.title');
echo $this->Form->input('Form.type');
echo $this->Form->input('Form.success_page');
echo $this->Form->input('Form.success_email');
echo $this->Form->input('Content.summary', array('rows' => 1));
echo $this->Form->input('Content.content', array('rows' => 20, 'columns' => 30, 'style' => 'width: 695px'));
echo $this->Form->input('Content.alias');
echo $this->Form->input('Content.__menu', array('type' => 'textarea', 'rows' => 1));
echo $this->Form->submit($this->fetch('submit_label'));
echo $this->Html->link('Cancel', array('controller' => 'contents', 'action' => 'index'), array('class' => 'cancel'));
echo $this->Form->end();
?>
