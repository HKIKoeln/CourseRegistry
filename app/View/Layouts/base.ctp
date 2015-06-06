<!-- 
 * Copyright 2014 Hendrik Schmeer on behalf of DARIAH-EU, VCC2 and DARIAH-DE,
 * Credits to Erasmus University Rotterdam, University of Cologne, PIREH / University Paris 1
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License. 
-->

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php
		$page = $this->fetch('title');
		$title = 'DH Registry';
		if($page == 'Courses') $title = 'DH Course Registry';
		elseif($page == 'Projects') $title = 'DH Project Registry - Beta';
		echo $title;
		?>
	</title>
	<?php
	if(Configure::read('debug') > 0) {
		echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex'));
		echo $this->Html->meta(array('name' => 'robots', 'content' => 'nofollow'));
	}else{
		echo $this->Html->meta(array('name' => 'robots', 'content' => 'index'));
		echo $this->Html->meta(array('name' => 'robots', 'content' => 'follow'));
	}
	echo $this->Html->meta('keywords', 'digital humanities, research, programs, courses');
	echo $this->Html->meta('description', 'European platform for digital humanity related research, courses and programs.');
	echo $this->Html->meta('icon');
	
	echo $this->Html->css('dhcourse.css');
	if(Configure::read('debug') > 0) {
		echo $this->Html->css('cake_debugging.css');
	}
	
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>
	
	<script type="text/javascript">
		window.onload = function() {
			<?php echo $this->fetch('onload'); ?>
		}
	</script>
</head>


<body>
	<div id="container">
		
		<?php echo $this->fetch('header'); ?>
		
		<div class="columns">
			<div id="menu">
				<?php
				echo $this->element('Users.login_info');
				?>
				<ul>
				<?php
				echo '<li>' . $this->Html->link('Courses', array('controller' => 'courses', 'action' => 'index', 'plugin' => null)) . '</li>';
				echo '<li>' . $this->Html->link('Projects', array('controller' => 'projects', 'action' => 'index', 'plugin' => null)) . '</li>';
				echo '<li>' . $this->Html->link('Manual', array('controller' => 'pages', 'action' => 'manual', 'plugin' => null)) . '</li>';
				echo '<li>' . $this->Html->link('Contact us', array('controller' => 'contacts', 'action' => 'send', 'plugin' => null)) . '</li>';
				
				echo $this->fetch('menu');
				?>
				</ul>
			</div>
			
			<div id="content">
				<?php
				echo $this->Session->flash();
				echo $this->fetch('content');
				?>
			</div>
		</div>
		
		<div id="footer">
			<?php echo $this->element('footer'); ?>
		</div>
	</div>
	
	
	
</body>
</html>
