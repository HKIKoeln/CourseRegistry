<?php

App::uses('Component', 'Controller');

class DefaultAuthComponent extends Component {
	
	
	public $settings = array();
	
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);

		$this->settings = Hash::merge($this->_defaults(), $settings);
	}
	
	protected function _defaults() {
		return array(
			'Auth' => array(
				'priority' => 2,
				'loginAction' => array(
					'controller' => 'users',
					'action' => 'login',
					'plugin' => 'users',
					'admin' => false
				),
				'authError' => 'Please log in to access this location.',
				'authenticate' => array(
					'Form' => array(
						'fields' => array(
							'username' => Configure::read('Users.loginName'),
							'password' => 'password'
						),
						'userModel' => Configure::read('Users.userModel'),
						'scope' => array(
							Configure::read('Users.userModel') . '.active' => 1,
							Configure::read('Users.userModel') . '.email_verified' => 1
						)
					)
				),
				'loginRedirect' => array('action' => 'dashboard','controller' => 'users','plugin' => 'users'),
				'logoutRedirect' => '/'
			)
		);
	}
	
	
	public function initialize(Controller $controller) {
		foreach($this->settings as $component => $settings) {
			$controller->components[$component] = $settings;
			$controller->{$component} = $controller->Components->load($component, $settings);
			$controller->{$component}->initialize($controller);
		}
		$controller->set('auth_user', $controller->Auth->user());
		if (Configure::read('Users.disableDefaultAuth') === true) {
			$controller->Auth->allow('*');
		}
	}
	
}
?>