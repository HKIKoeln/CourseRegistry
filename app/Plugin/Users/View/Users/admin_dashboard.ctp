<h2>Admin Dashboard</h2>

<?php
if(Configure::read('Users.adminConfirmRegistration')) {
	echo $this->element('Users.dashboard/admin_account_requests');
}
?>