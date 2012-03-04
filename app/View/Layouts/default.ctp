<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?> | SAIVA
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
  <div id="skip-link">
    <a href="#main-content">Skip to main content</a>
  </div>
	<div id="container">
		<div id="header">
      <div id="top">
        <div id="top-gutter-left"></div>
        <div id="top-center">  
          <div id="menu-destinations">
            <?php echo $this->element('menu', array("menu_id" => "destinations", "class" => "navigation")); ?>
            <ul class="menu navigation">
            <?php if($this->Session->check('Auth.User')) { ?>
            <li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
            <?php } ?>
            <li><a href="#" class="active">Welcome <?php $user = $this->Session->read('Auth.User'); echo $user['first_name'] ?> </a></li>
            </ul>
          </div>
          <?php echo $this->element('login_panel'); ?>
          <div id="logo">
            <?php echo $this->Html->image('logo.jpg', array('alt' => 'SAIVA', 'border' => '0')) ?>
            <div>Promote friendship, education and well-being through volunteering</div>
          </div>
        </div>
        <div id="top-gutter-right"></div>
      </div>
      <div id="top-bottom-separator"></div>
      <div id="bottom">
        <div id="bottom-gutter-left"></div>
        <div id="bottom-center">
            <?php echo $this->element('menu', array("menu_id" => "main", "class" => "main-menu")); ?>
        </div>
        <div id="bottom-gutter-right"></div>
      </div>
      <div id="highlighted">
        <?php echo $this->Html->image('joinnow.jpg', array('alt' => 'Join SAIVA', 'border' => '0')) ?>
        <div id="search-panel">
          <?php
            echo $this->Form->create('User', array(
              'class' => 'basic search',
              'url' => array('controller' => 'contents', 'action' => 'search')
            ));
            echo $this->Form->input('__search', array('label' => false, 'size' => 15, 'value' => 'Search'));
            echo $this->Form->end(array('label' => 'Go'));
          ?>
        </div>
      </div>
		</div>
    <div id="columns-top"></div>
    <div id="columns">
		  <div id="content">
        <a id="main-content"></a>
			  <?php echo $this->Session->flash(); ?>

			  <?php echo $this->fetch('content'); ?>
		  </div>
    </div>
		<div id="footer">
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
