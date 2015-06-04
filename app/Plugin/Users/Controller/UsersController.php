<?php

App::uses('UsersAppController', 'Users.Controller');

class UsersController extends UsersAppController {


	public $name = 'Users';
	
	public $components = array(
		'Security' => array('priority' => 1)
	);
	
	
	
	public function beforeFilter() {
		$this->modelClass = Configure::read('Users.userModel');
		$this->uses = array(Configure::read('Users.userModel'));
		$this->set('modelName', $this->modelClass);
		
		if(isset($this->Auth)) {
			$this->Auth->allow(array(
				'request_new_password',
				'reset_password',
				'register',
				'logout',
				'login',
				'verify_email',
				'request_email_verification',
				'approve'
			));
			if(!is_null(Configure::read('Users.allowRegistration')) && !Configure::read('Users.allowRegistration')) {
				$this->Auth->deny('register');
			}
		}
		
		if(isset($this->Security)) {
			$this->Security->requireSecure(array(
				'login',
				'dashboard',
				'profile',
				'request_new_password',
				'reset_password',
				'register',
				'request_email_verification'
			));
			if($settings = Configure::read('Users.securitySettings') AND is_array($settings)) {
				foreach($settings as $key => $value) {
					$this->Security->{$key} = $value;
				}
			}
		}
		
		if (!Configure::read('App.defaultEmail')) {
			$host = (env('HTTP_HOST') != 'localhost') ? env('HTTP_HOST') : 'example.com';
			Configure::write('App.defaultEmail', 'noreply@' . $host);
		}
		
		parent::beforeFilter();
		$this->set('title_for_layout', 'User Management');
	}
	
	
	public function login() {
		$Event = new CakeEvent(
			'Users.Controller.Users.beforeLogin',
			$this,
			array(
				'data' => $this->request->data,
			)
		);
		$this->getEventManager()->dispatch($Event);
		if($Event->isStopped()) {
			return;
		}
		if($this->request->is('post')) {
			if($this->Auth->login()) {
				$Event = new CakeEvent(
					'Users.Controller.Users.afterLogin',
					$this,
					array(
						'data' => $this->request->data,
						'isFirstLogin' => !$this->Auth->user('last_login')
					)
				);
				$this->getEventManager()->dispatch($Event);
				$this->{$this->modelClass}->id = $this->Auth->user('id');
				$this->{$this->modelClass}->saveField('last_login', date('Y-m-d H:i:s'));
				if($this->here == $this->Auth->loginRedirect) {
					$this->Auth->loginRedirect = '/';
				}
				$returnTo = null;
				if($this->Session->check('Auth.redirect')) {
					$returnTo = $this->Session->read('Auth.redirect');
				}
				// Checking for 2.3 but keeping a fallback for older versions
				if(method_exists($this->Auth, 'redirectUrl')) {
					$this->redirect($this->Auth->redirectUrl($returnTo));
				}else{
					$this->redirect($this->Auth->redirect($returnTo));	// fallback
				}
			}else{
				$this->Auth->flash('Invalid email-password combination. Please try again.');
			}
		}
	}
	
	
	public function logout() {
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}
	
	
	public function dashboard() {
		if($this->_isAdmin()) {
			// admin dashboard
			$inactive = $this->AppUser->find('all', array(
				'conditions' => array($this->modelClass . '.active' => 0)
			));
			$this->set('inactive', $inactive);
			$this->render('admin_dashboard');
		}else{
			$this->render('user_dashboard');
		}
	}
	
	
	public function profile($id = null) {
		$user = array();
		$auth_user = $this->Auth->user();
		$admin = false;
		if(!empty($auth_user)) {
			$user[$this->modelClass] = $auth_user;
		}
		if(!empty($id) AND $this->_isAdmin()) {
			$user = $this->{$this->modelClass}->find('first', array(
				'contain' => array(),
				'conditions' => array($this->modelClass . '.id' => $id)
			));
			$admin = true;
		}
		if(empty($user)) $this->redirect(array(
			'plugin' => null,
			'controller' => 'users',
			'action' => 'dashboard'
		));
		
		if(!empty($this->request->data[$this->modelClass])) {
			$this->request->data[$this->modelClass]['id'] = $user[$this->modelClass]['id'];
			$result = $this->{$this->modelClass}->saveProfile($this->request->data, $admin);
			if($result) $this->redirect(array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'dashboard'
			));
		}
		$this->request->data = $user;
	}
	
	
	public function request_new_password($email = null) {
		$user = $this->Auth->user();
		if(!empty($user)) $email = $user['email'];
		if(!empty($this->request->data[$this->modelClass]['email'])) {
			$email = $this->request->data[$this->modelClass]['email'];
		}
		if(!empty($email)) {
			$user = $this->{$this->modelClass}->requestNewPassword($email);
			if($user AND !empty($user[$this->modelClass]['password_token'])) {
				$result = $this->_sendUserManagementMail(array(
					'template' => 'Users.password_reset',
					'subject' => 'Password Reset',
					'email' => $email,
					'data' => $user
				));
				if($result) $this->Auth->flash('You should receive an email with further instructions shortly.');
				if($this->Auth->loggedIn()) $this->redirect(array(
					'plugin' => null,
					'controller' => 'users',
					'action' => 'dashboard'
				));
				$this->redirect(array(
					'plugin' => null,
					'controller' => 'users',
					'action' => 'login'
				));
			}
		}
	}
	
	
	protected function _sendUserManagementMail($options = array()) {
		$subject_prefix = (Configure::read('App.EmailSubjectPrefix'))
			? trim(Configure::read('App.EmailSubjectPrefix')) . ' '
			: '';
		$defaults = array(
			'subject_prefix' => $subject_prefix,
			'subject' => 'Password Reset',
			'emailFormat' => 'text',
			'template' => 'default',
			'layout' => 'default',
			'transport' => 'Mail',
			'content' => ''
		);
		$options = array_merge($defaults, $options);
		$result = false;
		if(empty($options['data']) AND !empty($this->{$this->modelClass}->data))
			$options['data'] = $this->{$this->modelClass}->data;
		if(!empty($options['email'])) {
			App::uses('CakeEmail', 'Network/Email');
			$Email = $this->_getMailInstance();
			$Email->to($options['email']);
			if(!empty($options['from'])) $Email->from($options['from']);	// set in email config on app level
			$Email->emailFormat($options['emailFormat']);
			$Email->subject($options['subject_prefix'] . $options['subject']);
			$Email->template($options['template'], $options['layout']);
			$Email->viewVars(array(
				'model' => $this->modelClass,
				'user' => $options['data'],
				'content' => $options['content']
			));
			if(!empty($options['message'])) $Email->message($options['message']);
			$Email->transport($options['transport']);
			$result = $Email->send();
		}
		return $result;
	}
	
	
	protected function _newUserAdminNotification($user = array()) {
		if(empty($user)) return false;
		$result = true;
		$mailOpts = array(
			'template' => 'Users.admin_new_user',
			'subject' => 'New Account Request',
			'data' => $user
		);
		
		if(Configure::read('Users.adminConfirmRegistration') AND Configure::read('Users.newUserAdminNotification')) {
			if(!Configure::read('Users.adminEmailAddress') AND $this->{$this->modelClass}->hasField('user_admin')) {
				$admins = $this->{$this->modelClass}->find('all', array(
					'contain' => array(),
					'conditions' => array(
						$this->modelClass . '.user_admin' => 1,
						$this->modelClass . '.active' => 1)));
				if($admins) {
					foreach($admins as $admin) {
						$mailOpts['email'] = $admin[$this->modelClass]['email']; 
						if(!$this->_sendUserManagementMail($mailOpts)) {
							$result = false;
						}
					}
				}
			}elseif($mailOpts['email'] = Configure::read('Users.adminEmailAddress')) {
				return $this->_sendUserManagementMail($mailOpts);
			}
		}
		return $result;
	}
	
	
	public function reset_password($token = null) {
		if(!empty($token)) {
			$user = $this->{$this->modelClass}->checkPasswordToken($token);
			if(empty($user)) {
				$this->Auth->flash('Invalid password reset token, try again.');
				$this->redirect(array(
					'plugin' => null,
					'controller' => 'users',
					'action' => 'request_new_password'
				));
			}
			elseif($user[$this->modelClass]['active'] == 0) {
				$msg = 'Your account has been locked, you cannot reset your password.';
				if(Configure::read('Users.adminConfirmRegistration')) {
					$msg = 'Your account has not been activated by an administrator, yet.';
				}
				$this->Auth->flash($msg);
				$this->redirect('/');
			}
			$id = (!empty($user[$this->modelClass][$this->{$this->modelClass}->primaryKey]))
				? $user[$this->modelClass][$this->{$this->modelClass}->primaryKey]
				: null;
			if(!empty($this->request->data[$this->modelClass]) AND !empty($id)) {
				$data = array();
				$data[$this->modelClass][$this->{$this->modelClass}->primaryKey] = $id;
				$data[$this->modelClass]['new_password'] = $this->request->data[$this->modelClass]['new_password'];
				if($this->{$this->modelClass}->resetPassword($data)) {
					$this->Auth->flash('Password changed, please login with your new password.');
					$this->Auth->logout();
					$this->redirect($this->Auth->loginAction);
				}
			}

			$this->set('token', $token);
		}
	}
	
	
	public function register() {
		if(!empty($this->request->data[$this->modelClass])) {
			
			$user = $this->{$this->modelClass}->register($this->request->data);
			if($user) {
				$this->_newUserAdminNotification($user);
				$result = $this->_sendUserManagementMail(array(
					'template' => 'Users.email_verification',
					'subject' => 'Email Verification',
					'email' => $user[$this->modelClass]['email'],
					'data' => $user
				));
				$this->Session->write('Users.verification', $user[$this->modelClass]['email_token']);
				if($result) {
					$this->Auth->flash('Before logging in, check your inbox for an email with instructions to veryfy your email address.');
					$this->redirect(array(
						'plugin' => null,
						'controller' => 'users',
						'action' => 'login'
					));
				}else{
					$this->Auth->flash('Something went wrong. Try resending the veryfication mail.');
					$this->redirect(array(
						'plugin' => null,
						'controller' => 'users',
						'action' => 'request_email_verification'
					));
				}
			}
		}
	}
	
	
	public function request_email_verification() {
		$new_email = false;
		$user = array();
		$token = null;
		$user_id = $this->Auth->user('id');
		
		// this section handles email verification / resending it after registration
		if($this->Session->check('Users.verification')) {
			$token = $this->Session->read('Users.verification');
			$user = $this->{$this->modelClass}->checkEmailToken($token);
			// we're in fact evaluating the old 'email', not 'new_email' (even though the User::register method sets both to the same value)
			$new_email = $user[$this->modelClass]['email'];
			
			$user = $this->_send_verification_mail($new_email, $user, $redirect = false);
			$this->Session->write('Users.verification', $user[$this->modelClass]['email_token']);
			$this->redirect(array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'login'
			));
		}
		
		if(!empty($user_id) AND !empty($this->request->data[$this->modelClass]['new_email'])) {
			// require the user to enter her password to change the account's email address
			$user = $this->{$this->modelClass}->find('first', array(
				'contain' => array(),
				'conditions' => array($this->modelClass . '.id' => $user_id)
			));
			if(!empty($user)) {
				$hash = $pwd = false;
				if(!empty($this->request->data[$this->modelClass]['password'])) {
					$hash = $this->{$this->modelClass}->hash($this->request->data[$this->modelClass]['password']);
				}
				if($hash AND $user[$this->modelClass]['password'] == $hash) {
					$pwd = true;
				}else{
					$this->Auth->flash('Please enter your password to reset your account\'s email address.');
					$this->{$this->modelClass}->invalidate('password', 'The password did not match.');
				}
				if($this->{$this->modelClass}->validates('new_email') AND $pwd) {
					$new_email = $this->request->data[$this->modelClass]['new_email'];
				}
			}
		}
		
		$this->_send_verification_mail($new_email, $user);
	}
	
	
	protected function _send_verification_mail($new_email = false, $user = array(), $redirect = true) {
		if($new_email) {
			if(empty($user)) $user[$this->modelClass] = $this->Auth->user();
			if(empty($user[$this->modelClass])) $user = array();
			if($user) {
				$user = $this->{$this->modelClass}->requestEmailVerification($new_email, $user);
				$result = $this->_sendUserManagementMail(array(
					'template' => 'Users.email_verification',
					'subject' => 'Email Verification',
					'email' => $new_email,
					'data' => $user
				));
				if($result) {
					$msg = 'Confirmation mail was sent. Check your inbox for an email with instructions to verify your email address.';
					if($this->Auth->loggedIn()) $this->Session->setFlash($msg);
					$this->Auth->flash($msg);
				}else{
					$this->Auth->flash('Something went wrong. Try resending the verification mail.');
				}
				if($redirect) {
					if($this->Auth->loggedIn()) {
						if($result) {
							$this->redirect(array(
								'plugin' => null,
								'controller' => 'users',
								'action' => 'dashboard'
							));
						}
						$this->redirect(array(
							'plugin' => null,
							'controller' => 'users',
							'action' => 'request_email_verification'
						));
					}
					$this->redirect(array(
						'plugin' => null,
						'controller' => 'users',
						'action' => 'login'
					));
				}
			}
		}
		return $user;
	}
	
	
	public function verify_email($token = null) {
		if(!empty($token)) {
			$user = $this->{$this->modelClass}->checkEmailToken($token);
			if(empty($user)) {
				$this->Session->setFlash('Invalid email verification token, try again.', 'default', array(), 'auth');
				$this->redirect(array(
					'plugin' => null,
					'controller' => 'users',
					'action' => 'request_email_verification'
				));
			}
			$id = (!empty($user[$this->modelClass][$this->{$this->modelClass}->primaryKey]))
				? $user[$this->modelClass][$this->{$this->modelClass}->primaryKey]
				: null;
			if(!empty($id)) {
				if($result = $this->{$this->modelClass}->verifyEmail($user)) {
					$this->Session->setFlash('Your email address has been verified successful.', 'default', array(), 'auth');
					$this->Session->delete('Users.verification');
					$this->Auth->logout();
				}else{
					$this->Session->setFlash('An error occurred, please try again.', 'default', array(), 'auth');
					$this->redirect(array(
						'plugin' => null,
						'controller' => 'users',
						'action' => 'request_email_verification'
					));
				}
			}
		}
		$this->redirect($this->Auth->loginAction);
	}
	
	
	public function approve($id = null) {
		$proceed = false;
		if($this->_isAdmin() AND !empty($id) AND ctype_digit($id)) {
			$proceed = true;
		}else{
			$user = $this->{$this->modelClass}->find('first', array(
				'contain' => array(),
				'conditions' => array(
					$this->modelClass . '.approval_token' => $id,
					$this->modelClass . '.approved' => 0
				)
			));
			if($user) {
				$id = $user[$this->modelClass]['id'];
				$proceed = true;
			}
		}
		
		if($proceed) {
			$user = $this->{$this->modelClass}->approve($id);
			$this->_sendUserManagementMail(array(
				'template' => 'Users.account_approved',
				'subject' => 'Account approved',
				'email' => $user[$this->modelClass]['email'],
				'data' => $user
			));
			$this->Session->setFlash('The account has been approved successfully.');
		}
		
		if($this->_isAdmin()) $this->redirect(array(
			'plugin' => null,
			'controller' => 'users',
			'action' => 'dashboard'
		));
		$this->redirect('/');
	}
	
	
	public function delete($id = null) {
		if($this->_isAdmin() AND !empty($id)) {
			$this->AppUser->delete($id, $cascade = false);
		}
		$this->redirect(array(
			'plugin' => null,
			'controller' => 'users',
			'action' => 'dashboard'
		));
	}
	
	
	
	
	
	
	
	
}
?>