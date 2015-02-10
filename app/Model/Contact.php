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

App::uses('AppModel', 'Model');
/**
 * Country Model
 *
 * @property City $City
 * @property Course $Course
 */
class Contact extends AppModel {

/**
 * 
 *
 * @var string
 */
	public $useTable = false;
	
	
/**
 * Validation rules
 *
 * @var array
 */
	public $validationRules = array(
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
				'message' => 'Please enter your telephone number so we can call you in case we have further questions.'
			)
		),
		'message' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'Please type in a message.'
			)
		)
	);


}
