<?php 
$controller->loadModel('User');
$controller->User->alias = 'Author';
foreach($ViewList->items($controller) as $content): ?>
<div class="news-item">
  <h2>
    <?php echo $this->Html->link($content['News']['title'], array(
          'controller' => 'contents',
          'action' => 'display',
          $content['News']['id']
        ),
        array(
          'class' => 'toplink'
        )) ?>
  </h2>
  <div>
    <p><?php echo $this->Time->format('F jS, Y h:i A', $content['News']['created']); ?> | 
       author: 
        <?php 
          $controller->User->set($content);
          echo $controller->User->get_name();
        ?>
    </p>
    <?php echo $content['News']['summary'] ? 
          $content['News']['summary'] : 
          substr($content['News']['content'], 0, 255) ?>
  </div>
</div>
<?php endforeach; ?> 
