<?php
$this->extend('Form/_form'); 
$this->assign('title', 'Update Form');
$this->assign('submit_label', 'Update');
$this->start('id');
echo $this->Form->input('Form.id', array('type' => 'hidden'));
echo $this->Form->input('Content.id', array('type' => 'hidden'));
echo $this->Form->input('Form.content_id', array('type' => 'hidden'));
$this->end();
?>
