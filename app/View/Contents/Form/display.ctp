<h1 class="title"><?php echo $Content->data['Content']['title'] ?></h1>

<p>
  <?php echo $Content->data['Content']['content'] ?>
</p>

<p>
  <?php echo $this->element('Forms/' . $Form->data['Form']['type']); ?>
</p>
