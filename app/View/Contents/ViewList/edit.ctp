<?php
$this->extend('ViewList/_form'); 
$this->assign('title', 'Update View');
$this->assign('submit_label', 'Update');
$this->start('id');
echo $this->Form->input('ViewList.id', array('type' => 'hidden'));
echo $this->Form->input('Content.id', array('type' => 'hidden'));
echo $this->Form->input('ViewList.content_id', array('type' => 'hidden'));
$this->end();
?>
