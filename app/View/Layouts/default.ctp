<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
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
      <div id="top-gutter-left"></div>
      <div id="top-center">  
        <?php echo $this->Html->image('logo.jpg', array('alt' => 'SAIVA', 'border' => '0', 'id' => 'logo')) ?>
        <div id="menu-destinations">
          <ul class="menu navigation">
            <li><a href="#" title=""><span>Blog</span></a></li>
            <li><a href="#" title=""><span>Photos</span></a></li>
            <li><a href="#" title=""><span>Videos</span></a></li>
            <li><a href="#" title=""><span>Recipes</span></a></li>
            <li><a href="#" title=""><span>Testimonials</span></a></li>
          </ul>
          <ul class="menu navigation">
          <li><a href="#">Logout</a></li>
          <li><a href="#" class="active">Welcome <?php $user = $this->Session->read('Auth.User'); echo $user['first_name'] ?> </a></li>
          </ul>
        </div>
      </div>
      <div id="top-gutter-right"></div>
      <?php echo $this->element('login_panel'); ?>
		</div>
		<div id="content">
      <a id="main-content"></a>
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
