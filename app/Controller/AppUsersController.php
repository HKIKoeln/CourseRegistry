<?php
/**
 * Copyright 2014 Hendrik Schmeer on behalf of DARIAH-EU, VCC2 and DARIAH-DE,
 * Credits to Erasmus University Rotterdam, University of Cologne, PIREH / University Paris 1
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

App::uses('UsersController', 'Users.Controller');

/**	Extending the plugin's UsersController.
*	This is not neccessary to override plugin views on app-level, but if you want to extend the plugin views. 
*	
*	The Users plugin is a reinforced/refactored version of the CakeDC Users plugin.
*	documentation: https://github.com/CakeDC/users/blob/master/Docs/Documentation/Extending-the-Plugin.md
*/

class AppUsersController extends UsersController {
    
	public $name = 'AppUsers';
	
	// if using the plugin's model:
	//public $modelClass = 'Users.User';
	//public $uses = array('Users.User');
	
	public $modelClass = 'AppUser';
	
	public $uses = array('AppUser');
	
	
	
	
	
	
	
	
	// render the plugin views by default, if no app-view exists
	public function render($view = null, $layout = null) {
		if(is_null($view)) {
			$view = $this->action;
		}
		$viewPath = substr(get_class($this), 0, strlen(get_class($this)) - 10);
		clearstatcache();
		if(!file_exists(APP . 'View' . DS . $viewPath . DS . $view . '.ctp')) {
			$this->viewPath = $this->plugin = 'Users';
		}else{
			$this->viewPath = $viewPath;
		}
		return parent::render($view, $layout);
	}
	
	
	protected function _setUniversities() {
		$institutions = $this->AppUser->Institution->find('list', array(
			'contain' => array('Country'),
			'fields' => array('Institution.id', 'Institution.name', 'Country.name'),
			'conditions' => array('Institution.is_university' => 1)
		));
		ksort($institutions);
		$this->set('institutions', $institutions);
	}
	
	
	public function register() {
		$this->_setUniversities();
		parent::register();
	}
	
	
	public function profile($id = null) {
		$this->_setUniversities();
		parent::profile($id);
	}
	
	
	public function dashboard() {
		$courses = $this->AppUser->Course->find('all', array(
			'conditions' => array(
				'Course.user_id' => $this->Auth->user('id'),
				'Course.updated >' => date('Y-m-d H:i:s', time() - Configure::read('App.CourseArchivalPeriod'))
			)
		));
		$this->set(compact('courses'));
		
		if($this->_isAdmin()) {
			// admin dashboard
			$unapproved = $this->AppUser->find('all', array(
				'contain' => array('University'),
				'conditions' => array(
					$this->modelClass . '.active' => 0,
					$this->modelClass . '.approved' => 0
				)
			));
			
			$invited = $this->AppUser->find('all', array(
				'contain' => array('University'),
				'conditions' => array(
					'OR' => array(
						$this->modelClass . '.password IS NULL',
						$this->modelClass . '.password' => ''
					),
					$this->modelClass . '.active' => 1
				)
			));
			
			$this->set(compact('unapproved', 'invited'));
			
			$this->render('admin_dashboard');
			
		}else{
			// user dashboard
			$this->render('user_dashboard');
		}
	}
	
	
	// technically, this is a admin-triggered password reset - thus the email template reads somewhat different
	public function invite($param = null) {
		if(!$this->_isAdmin()) $this->redirect('/users/dashboard');
		
		$mailOpts = array(
			'template' => 'invite_user',
			'subject' => 'Join the Digital Humanities Course Registry'
		);
		if(Configure::read('debug') > 0) $mailOpts['transport'] = 'Debug';
		
		if(!empty($param)) {
			if(ctype_digit($param)) {
				// invite individual user - $param == $id
				$user = $this->{$this->modelClass}->find('first', array(
					'contain' => array(),
					'conditions' => array(
						$this->modelClass . '.id' => $param,
						$this->modelClass . '.active' => 1
					)
				));
				if($user AND !empty($user[$this->modelClass]['email'])) {
					$mailOpts['email'] = $user[$this->modelClass]['email'];
					$mailOpts['data'] = $user;
					$this->_sendUserManagementMail($mailOpts);
					$this->Session->setFlash('User will receive an email shortly.');
				}
				
			}elseif($param === 'all') {
				$users = $this->{$this->modelClass}->find('all', array(
					'contain' => array(),
					'conditions' => array(
						$this->modelClass . '.active' => 1,
						$this->modelClass . '.password' => array(null, '')
					)
				));
				if(!empty($users)) {
					foreach($users as $user) {
						if($user AND !empty($user[$this->modelClass]['email'])) {
							$mailOpts['email'] = $user[$this->modelClass]['email'];
							$mailOpts['data'] = $user;
							$this->_sendUserManagementMail($mailOpts);
						}
					}
					$this->Session->setFlash('Users will receive an email shortly.');
				}
			}
			$this->redirect('/users/dashboard');
			
		}else{
			// add a new user
			if(!empty($this->request->data[$this->modelClass])) {
				if($user = $this->{$this->modelClass}->inviteRegister($this->request->data)) {
					if(!empty($user[$this->modelClass]['email'])) {
						$mailOpts['email'] = $user[$this->modelClass]['email'];
						$mailOpts['data'] = $user;
						$this->_sendUserManagementMail($mailOpts);
						$this->Session->setFlash('User successfully invited and emailed.');
					}
					$this->redirect('/users/dashboard');
				}
			}
			$this->_setUniversities();
		}
	}
	
	
	
	
	
	
}
?>