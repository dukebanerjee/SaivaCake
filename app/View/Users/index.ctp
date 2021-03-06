<h1>Users</h1>
<div>
  <?php echo $this->Html->link('Add User', 
    array('controller' => 'users', 'action' => 'add')); ?>
</div>
<table>
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('username'); ?></th>
      <th><?php echo $this->Paginator->sort('status'); ?></th>
      <th><?php echo $this->Paginator->sort('role'); ?></th>
      <th><?php echo $this->Paginator->sort('created', 'Member For'); ?></th>
      <th><?php echo $this->Paginator->sort('last_login'); ?></th>
      <th>Operations</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($users as $user): ?>
    <tr>
      <td><?php echo $user['User']['username']; ?></td>
      <td><?php echo $user['User']['status']; ?></td>
      <td><?php echo $user['User']['role']; ?></td>
      <td><?php echo AppModel::intervalUntilNow($user['User']['created']); ?></td>
      <td><?php echo AppModel::intervalUntilNow($user['User']['last_login']); ?></td>
      <td>
        <?php echo $this->Html->link('Edit', 
          array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?>
        <?php echo $this->Html->link('Delete', 
          array('controller' => 'users', 'action' => 'delete', $user['User']['id']),
          array(),
          "Are you sure you want to delete the user?"); ?>
      </td>
    </tr>
    <?php endforeach; ?> 
  </tbody>
</table>
<div id="page-numbers">
  <?php echo $this->Paginator->numbers(array('before' => 'Pages: ', 'first' => 2, 'last' => 2)); ?>
</div>
