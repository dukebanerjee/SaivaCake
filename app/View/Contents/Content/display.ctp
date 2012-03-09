<?php
$this->Html->css('fullcalendar', null, array('inline' => false));
$this->Html->script('jquery-1.7.1.min', array('inline' => false));
$this->Html->script('fullcalendar.min', array('inline' => false));
?>
<h1 class="title"><?php echo $content['Content']['title'] ?></h1>

<?php echo $content['Content']['content'] ?>
