<?php
$this->extend('Document/_form'); 
$this->assign('title', 'Update Document');
$this->assign('submit_label', 'Update');
$this->start('id');
echo $this->Form->input('Content.id', array('type' => 'hidden'));
$this->end();
?>
