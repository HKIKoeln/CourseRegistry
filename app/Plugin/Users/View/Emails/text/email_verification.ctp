<?php
echo "To verify your email address, click the link below within 24 hours.\n";
echo "\n";
echo Router::url(array(
	'admin' => false,
	'plugin' => 'users',
	'controller' => 'users',
	'action' => 'verify_email',
	$user[$model]['email_token']
), $full = true);
echo "\n";
echo "\n";
if(Configure::read('Users.adminConfirmRegistration') AND !$user[$model]['active']) {
	echo "WAIT A MINUTE:\n";
	echo "Login is only enabled once our administrators approved your account.\n";
	echo "You will receive another email to notify you about that.\n";
}
?>