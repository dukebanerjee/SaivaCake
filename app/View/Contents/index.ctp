<h1 class="title">Contents</h1>
<div id="add-content-form">
  <?php 
    echo $this->Form->create('Content', array(
      'type' => 'GET',
      'action' => 'add',
      'class' => 'basic',
      'inputDefaults' => array(
        'style' => 'width: 15em',
        'label' => array(
          'style' => 'width: 10em'
        )
      )
    ));
    echo $this->Form->input('type', array(
      'label' => array('text' => 'Add Content', 'style' => 'width: 6em'),
      'options' => $content_type_options
    ));
    echo $this->Form->end('Add');
  ?>
</div>
<table>
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('title'); ?></th>
      <th><?php echo $this->Paginator->sort('type'); ?></th>
      <th><?php echo $this->Paginator->sort('status'); ?></th>
      <th><?php echo $this->Paginator->sort('author_id'); ?></th>
      <th><?php echo $this->Paginator->sort('created'); ?></th>
      <th><?php echo $this->Paginator->sort('modified'); ?></th>
      <th>Operations</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($contents as $content): ?>
    <tr>
      <td><?php echo substr($content['Content']['title'], 0, 60); ?></td>
      <td><?php echo $content['Content']['type']; ?></td>
      <td><?php echo $content['Content']['status']; ?></td>
      <td><?php echo $content['Author']['username']; ?></td>
      <td><?php echo AppModel::intervalUntilNow($content['Content']['created']); ?></td>
      <td><?php echo AppModel::intervalUntilNow($content['Content']['modified']); ?></td>
      <td>
        <?php echo $this->Html->link('View', 
          array('controller' => 'contents', 'action' => 'display', $content['Content']['id'])); ?>
        <?php echo $this->Html->link('Edit', 
          array('controller' => 'contents', 'action' => 'edit', $content['Content']['id'])); ?>
        <?php echo $this->Html->link('Delete', 
          array('controller' => 'contents', 'action' => 'delete', $content['Content']['id']),
          array(),
          "Are you sure you want to delete the content?"); ?>
      </td>
    </tr>
    <?php endforeach; ?> 
  </tbody>
</table>
<div id="page-numbers">
  <?php echo $this->Paginator->numbers(array('before' => 'Pages: ', 'first' => 2, 'last' => 2)); ?>
</div>
