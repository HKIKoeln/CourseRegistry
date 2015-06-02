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
App::uses('User', 'Users.Model');

class AppUser extends User {
    
	public $name = 'AppUser';
	
    public $useTable = 'users';
	
	// a set of validation rules, extending or overriding the given rules from the plugin
	public $validationRules = array(
		'institution_id' => array(
			'special' => array(
				'rule' => 'checkUniversity',
				'message' => 'Please either choose your university from this list or enter the name in the next field if it\'s not available.'
			)
		),
		'last_name' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter your last name.'
			)
		),
		'first_name' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter your first name.'
			)
		),
		'telephone' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'For verification of your authority, please enter your telephone number.'
			)
		),
		'authority' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'For verification of your authority, please provide any further information.'
			)
		)
	);
	
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->validate = array_merge($this->validate, $this->validationRules);
	}
	
	
	public $virtualFields = array(
		'name' => 'CONCAT(AppUser.first_name, " ", AppUser.last_name)'
	);
	
	
	
	public $belongsTo = array(
		'Institution' => array(
			'className' => 'Institution',
			'foreignKey' => 'institution_id'
		)
	);
	
	public $hasMany = array(
		'Course' => array(
			'className' => 'Course',
			'foreignKey' => 'user_id',
			'dependent' => false
		)
	);
	
	
	// custom validation
	public function checkUniversity($check) {
		$result = false;
		$universities = $this->Institution->find('list');
		
		if(	!empty($this->data[$this->alias]['institution_id'])
		AND	isset($universities[$this->data[$this->alias]['institution_id']])
		) {
			$result = true;
		}
		elseif(!empty($this->data[$this->alias]['university'])) {
			foreach($universities as $k => &$value) {
				$value = strtolower($value);
			}
			$pos = array_search(strtolower($this->data[$this->alias]['university']), $universities);
			if($pos !== false) {
				$this->data[$this->alias]['institution_id'] = $pos;
			}
			$result = true;
		}
		
		return $result;
	}
	
	
	public function inviteRegister($data = array()) {
		$result = false;
		$this->set($data);
		if($this->validates(array('fieldList' => array('email', 'institution_id', 'first_name', 'last_name')))) {
			$token = $this->generateToken('password_token');
			$expiry = date('Y-m-d H:i:s', time() + $this->tokenExpirationTime);
			$this->data[$this->alias]['email_verified'] = 1;
			$this->data[$this->alias]['active'] = 1;
			$this->data[$this->alias]['approved'] = 1;
			$this->data[$this->alias]['password_token'] = $token;
			$this->data[$this->alias]['password_token_expires'] = $expiry;
			
			$result = $this->save($this->data, array('validate' => false));
			$result[$this->alias]['name'] = $result[$this->alias]['first_name'] . ' ' . $result[$this->alias]['last_name'];
		}
		return $result;
	}
	
	
	
	
}
?>