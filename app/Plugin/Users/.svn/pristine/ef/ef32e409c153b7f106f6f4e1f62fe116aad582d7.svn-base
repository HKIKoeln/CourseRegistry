<div class="auth">
<h2>Reset your password - Step 2/2</h2>
<?php
	echo $this->Form->create($modelName, array(
		'url' => array(
			'plugin' => null,
			'controller' => 'users',
			'action' => 'reset_password',
			$token
		)
	));
	echo $this->Form->input('new_password', array(
		'label' => 'New Password',
		'type' => 'password',
		'required' => false,
		'autocomplete' => 'off'
	));
	echo $this->Form->end('Submit');
?>
</div>