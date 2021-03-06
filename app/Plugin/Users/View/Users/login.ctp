<div class="auth">
	<h2>Login</h2>
	
	<?php
	echo $this->Session->flash('auth');
	
	
	echo $this->Form->create($modelName);
	echo $this->Form->input(Configure::read('Users.loginName'), array('required' => false, 'autocomplete' => 'off'));
	echo $this->Form->input('password', array('required' => false, 'autocomplete' => 'off'));
	echo $this->Form->end('Log on');
	?>
	
	
	<ul>
		<li>
			<?php
			if($this->Session->check('Users.verification')) {
				echo $this->Html->link('Resend Email Verification', array(
					'action' => 'request_email_verification',
					'controller' => 'users'
				));
			}else{
				echo $this->Html->link('I forgot my password', array(
					'action' => 'request_new_password',
					'controller' => 'users'
				));
			}
			?>
		</li>
		<?php
		if(is_null(Configure::read('Users.allowRegistration')) OR Configure::read('Users.allowRegistration')) {
			?>
			<li>
				<?php
				$label = 'Register';
				if(Configure::read('Users.adminConfirmRegistration')) $label = 'Apply';
				echo $this->Html->link($label, array(
					'controller' => 'users',
					'action' => 'register'
				));
				?>
			</li>
			<?php
		}
		?>
	</ul>
</div>