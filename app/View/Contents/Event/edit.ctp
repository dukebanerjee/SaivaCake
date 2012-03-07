<?php
$this->extend('Event/_form'); 
$this->assign('title', 'Update Event');
$this->assign('submit_label', 'Update');
$this->start('id');
echo $this->Form->input('Event.id', array('type' => 'hidden'));
echo $this->Form->input('Content.id', array('type' => 'hidden'));
echo $this->Form->input('Event.content_id', array('type' => 'hidden'));
$this->end();
?>
