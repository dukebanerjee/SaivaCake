<?php
$this->extend('_form'); 
$this->assign('title', 'Update Content');
$this->assign('submit_label', 'Update');
$this->start('id');
echo $this->Form->input('id', array('type' => 'hidden'));
$this->end();
?>
