<div class="auth">
	<h2>User Registration</h2>
	
	<?php
	echo $this->Form->create($modelName);
	
	if(Configure::read('Users.username')) {
		echo $this->Form->input('username', array(
			'label' => 'Username',
			'autocomplete' => 'off'
		));
	}
	echo $this->Form->input('email', array(
		'label' => 'E-mail',
		'autocomplete' => 'off'
	));
	
	echo $this->Form->input('password', array(
		'label' => 'Password',
		'type' => 'password',
		'autocomplete' => 'off'
	));
	
	echo $this->Form->end('Submit');
	?>
	
</div>