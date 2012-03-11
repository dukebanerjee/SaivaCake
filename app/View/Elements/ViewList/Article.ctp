<?php 
$controller->loadModel('User');
$controller->User->alias = 'Author';
foreach($ViewList->items($controller) as $content): ?>
<div class="news-item">
  <h2>
    <?php echo $this->Html->link($content['Article']['title'], array(
          'controller' => 'contents',
          'action' => 'display',
          $content['Article']['id']
        ),
        array(
          'class' => 'toplink'
        )) ?>
  </h2>
  <div>
    <?php echo $content['Article']['summary'] ? 
          $content['Article']['summary'] : 
          substr($content['Article']['content'], 0, 255) ?>
  </div>
</div>
<?php endforeach; ?> 
