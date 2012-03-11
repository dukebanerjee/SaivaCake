<h1 class="title"><?php echo $Content->data['Content']['title'] ?></h1>

<?php 
echo $Content->data['Content']['content'];
echo $this->element('ViewList/' . $Content->data['ViewList']['type']);
?>
