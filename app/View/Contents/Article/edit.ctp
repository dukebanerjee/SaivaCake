<?php
$this->extend('Article/_form'); 
$this->assign('title', 'Update Article');
$this->assign('submit_label', 'Update');
$this->start('id');
echo $this->Form->input('Content.id', array('type' => 'hidden'));
$this->end();
?>
