<?php
$this->extend('News/_form'); 
$this->assign('title', 'Update News');
$this->assign('submit_label', 'Update');
$this->start('id');
echo $this->Form->input('Content.id', array('type' => 'hidden'));
$this->end();
?>
