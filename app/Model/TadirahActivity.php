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
 * TadirahActivity Model
 *
 * @property TadirahActivity $ParentTadirahActivity
 * @property TadirahActivity $ChildTadirahActivity
 * @property Course $Course
 * @property TadirahTechnique $TadirahTechnique
 */
class TadirahActivity extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	
	
	var $validate = array(
		'TadirahActivity' => array(
			'rule' => 'checkTags',
			'message' => 'Please provide at least one keyword of Tadirah Activity.',
			'required' => true
		)
	);
	
	function checkTags() {
		if(!empty($this->data['TadirahActivity']['TadirahActivity'])) return true;
		return false;
	}
	
	

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ParentTadirahActivity' => array(
			'className' => 'TadirahActivity',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ChildTadirahActivity' => array(
			'className' => 'TadirahActivity',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Course' => array(
			'className' => 'Course',
			'joinTable' => 'courses_tadirah_activities',
			'foreignKey' => 'tadirah_activity_id',
			'associationForeignKey' => 'course_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'TadirahTechnique' => array(
			'className' => 'TadirahTechnique',
			'joinTable' => 'tadirah_activities_tadirah_techniques',
			'foreignKey' => 'tadirah_activity_id',
			'associationForeignKey' => 'tadirah_technique_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

}
