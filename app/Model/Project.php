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
 * Project Model
 *
 * 
 */
class Project extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
	
	//public $order = 'Project.updated DESC';
	
	public $hasMany = array(
		'ProjectExternalIdentifier' => array(
			'className' => 'ProjectExternalIdentifier',
			'foreignKey' => 'project_id'
		),
		'ProjectLink' => array(
			'className' => 'ProjectLink',
			'foreignKey' => 'project_id'
		),
		'ProjectsInstitution' => array(
			'className' => 'ProjectsInstitution',
			'foreignKey' => 'project_id'
		),
		'ProjectsPerson' => array(
			'className' => 'ProjectsPerson',
			'foreignKey' => 'project_id'
		)
	);
	
	public $hasAndBelongsToMany = array(
		'Person' => array(
			'className' => 'Person',
			'joinTable' => 'projects_people',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'person_id',
			'unique' => 'keepExisting'
		),
		'Institution' => array(
			'className' => 'Institution',
			'joinTable' => 'projects_institutions',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'institution_id',
			'unique' => 'keepExisting'
		),
		'TadirahActivity' => array(
			'className' => 'TadirahActivity',
			'joinTable' => 'projects_tadirah_activities',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'tadirah_activity_id',
			'unique' => 'keepExisting'
		),
		'TadirahTechnique' => array(
			'className' => 'TadirahTechnique',
			'joinTable' => 'projects_tadirah_techniques',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'tadirah_technique_id',
			'unique' => 'keepExisting'
		),
		'TadirahObject' => array(
			'className' => 'TadirahObject',
			'joinTable' => 'projects_tadirah_objects',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'tadirah_object_id',
			'unique' => 'keepExisting'
		),
		'NwoDiscipline' => array(
			'className' => 'NwoDiscipline',
			'joinTable' => 'projects_nwo_disciplines',
			'foreignKey' => 'project_id',
			'associationForeignKey' => 'nwo_discipline_id',
			'unique' => 'keepExisting'
		)
	);
	
	
	
	

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Provide a meaningful name for your course.',
				'allowEmpty' => false,
				'required' => true
			)
		)
	);
	
	
	
}
?>