<?php
$controller->loadModel('User');
$controller->User->alias = 'Author';

$this->Html->css('fullcalendar', null, array('inline' => false));
$this->Html->script('jquery-1.7.1.min', array('inline' => false));
$this->Html->script('fullcalendar.min', array('inline' => false));
?>
<h1 class="title">Documents</h1>

<h2><?php echo $Content->data['Content']['title'] ?></h2>

<?php echo $Content->data['Content']['content'] ?>
