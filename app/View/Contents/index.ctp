<h1>Content</h1>
<div>
  <?php echo $this->Html->link('Add Content', 
    array('controller' => 'contents', 'action' => 'add')); ?>
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
      <td><?php echo $content['Content']['title']; ?></td>
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
