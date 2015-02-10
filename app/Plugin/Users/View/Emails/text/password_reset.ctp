<?php
echo "To change your password, click the link below within 24 hours.\n";
echo "If you did not request a password reset, you may ignore this email.\n";
echo "\n";
echo Router::url(array(
	'admin' => false,
	'plugin' => 'users',
	'controller' => 'users',
	'action' => 'reset_password',
	$user[$model]['password_token']
), $full = true);
echo "\n";
?>