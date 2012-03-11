<?php 
$controller->loadModel('User');
$controller->User->alias = 'Author';
foreach($ViewList->items($controller) as $content): ?>
<div class="news-item">
  <h2>
    <?php echo $this->Html->link($content['Document']['title'], array(
          'controller' => 'contents',
          'action' => 'display',
          $content['Document']['id']
        ),
        array(
          'class' => 'toplink'
        )) ?>
  </h2>
  <div>
    <?php echo $content['Document']['summary'] ? 
          $content['Document']['summary'] : 
          substr($content['Document']['content'], 0, 255) ?>
  </div>
</div>
<?php endforeach; ?> 
