<h1 class='title'>Form Submissions</h1>
<p><?php echo $this->Html->link('Export as CSV', array(
  'action' => 'export/' . $name
)) ?></p>
<table>
  <thead>
    <tr>
      <?php foreach($fields as $field): ?>
      <th><?php echo $this->Paginator->sort($field); ?></th>
      <?php endforeach ?>
      <th>Operations</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($submissions as $submission): ?>
    <tr>
      <?php foreach($fields as $field): ?>
      <td><?php echo $submission[$name][$field] ?></td>
      <?php endforeach ?>
      <td>
        <?php echo $this->Html->link('Delete', array(
            'action' => 'delete/' . $name,
            $submission[$name]['id']
        )) ?>
      </td>
    </tr>
    <?php endforeach; ?> 
  </tbody>
</table>
<div id="page-numbers">
  <?php echo $this->Paginator->numbers(array('before' => 'Pages: ', 'first' => 2, 'last' => 2)); ?>
</div>
