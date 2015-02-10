<?php
/**
* set some defaults
*/
Configure::write('Users.allowRegistration', true);
Configure::write('Users.disableDefaultAuth', false);
Configure::write('Users.emailConfig', 'default');
Configure::write('Users.securitySettings', array(
	'blackHoleCallback' => 'blackHole',
	'csrfCheck' => true
));
Configure::write('Users.userModel', 'Users.User');



/**
* not fully tested
*/
Configure::write('Users.loginName', 'email');	// alternatively: 'username'


/**
* not yet fully implemented:
*/
//Configure::write('Users.showLogin', true);
//Configure::write('Users.rememberMe', false);


/**
* Let Admins approve newly registrated users or not. 
* Set optionally in applicational configuration: the address of the person that has to confirm new registrated accounts. 
* Alternatively, use the database-field 'user_admin' to declare many responsible persons. 
*/
Configure::write('Users.adminConfirmRegistration', false);
Configure::write('Users.newUserAdminNotification', false);
//Configure::write('Users.adminEmailAddress', 'user.admin@example.com');


/**
* This Plugin also uses the following configuration constants:
*/
//Configure::write('App.EmailSubjectPrefix', '[Your Site]');


$filename = APP . 'Config' . DS . 'Users' . DS . 'bootstrap.php';
if(file_exists($filename)) {
    include($filename);
}