<h1>Users</h1>
<table>
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('username'); ?></th>
      <th><?php echo $this->Paginator->sort('role'); ?></th>
      <th><?php echo $this->Paginator->sort('created', 'Member For'); ?></th>
      <th>Operations</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($data as $user): ?>
    <tr>
      <td><?php echo $user['User']['username']; ?></td>
      <td><?php echo $user['User']['role']; ?></td>
      <td><?php echo User::memberFor($user['User']['created']); ?></td>
      <td>
        <?php echo $this->Html->link('Edit', 
          array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?>
      </td>
    </tr>
    <?php endforeach; ?> 
  </tbody>
  <div>
    <?php echo $this->Paginator->numbers(array('first' => 'First', 'last' => 'Last')); ?>
  </div>
</table>
