<div class="auth">
	<h2>Change account e-mail address</h2>
	<?php
	echo $this->Session->flash('auth');
	
	if(Configure::read('Users.loginName') == 'email') {
		echo '<p>Keep in mind, that your new email address will be the username you will log in with in future.</p>';
	}
	echo $this->Form->create($modelName);
	echo $this->Form->input('new_email', array('required' => false, 'autocomplete' => 'off'));
	echo $this->Form->input('password', array('required' => false, 'autocomplete' => 'off'));
	echo $this->Form->end('Submit');
	?>
</div>