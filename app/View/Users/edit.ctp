<?php
$this->extend('_form'); 
$this->assign('title', 'Update User');
$this->start('id');
echo $this->Form->input('id', array('type' => 'hidden'));
$this->end();
$this->assign('submit_label', 'Update');
?>
