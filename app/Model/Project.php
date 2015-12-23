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
	
	
	public $belongsTo = array(
		'AppUser' => array(
			'className' => 'AppUser',
			'foreignKey' => 'user_id'
		),
		'ParentProject' => array(
			'className' => 'Project',
			'foreignKey' => 'parent_id'
		)
	);
	
	// bugfix workaround - Containable was returning a complete Project array of null values!
	public function afterFind($results, $primary = false) {
		if(	$primary === true
		AND isset($results[0]['ParentProject'])
		AND	empty($results[0]['ParentProject']['id'])) {
			unset($results[0]['ParentProject']);
		}
		if(	$primary === true
		AND isset($results[0]['AppUser'])
		AND	empty($results[0]['AppUser']['id'])) {
			unset($results[0]['AppUser']);
		}
		return $results;
	}
	
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
		),
		'ChildProject' => array(
			'className' => 'Project',
			'foreignKey' => 'parent_id'
		),
		'ProjectReview' => array(
			'className' => 'ProjectReview',
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
	
	
	public function getSchema($mode = null) {
		$metadataSkipList = array('id','created','updated','schema','active','review');
		$schema = array();
		$collection['Project'] = $this->schema();
		$collection['ProjectLink'] = $this->ProjectLink->schema();
		$collection['ProjectLinkType'] = $this->ProjectLink->ProjectLinkType->schema();
		$collection['ProjectExternalIdentifier'] = $this->ProjectExternalIdentifier->schema();
		$collection['ProjectsPerson'] = $this->ProjectsPerson->schema();
		$collection['Person'] = $this->ProjectsPerson->Person->schema();
		$collection['PersonProjectRole'] = $this->ProjectsPerson->PersonProjectRole->schema();
		$collection['PersonExternalIdentifier'] = $this->ProjectsPerson->Person->PersonExternalIdentifier->schema();
		$collection['ProjectsInstitution'] = $this->ProjectsInstitution->schema();
		$collection['Institution'] = $this->ProjectsInstitution->Institution->schema();
		$collection['InstitutionRole'] = $this->ProjectsInstitution->InstitutionRole->schema();
		$collection['InstitutionExternalIdentifier'] = $this->ProjectsInstitution->Institution->InstitutionExternalIdentifier->schema();
		$collection['NwoDiscipline'] = $this->NwoDiscipline->schema();
		$collection['TadirahActivity'] = $this->TadirahActivity->schema();
		$collection['TadirahTechnique'] = $this->TadirahTechnique->schema();
		$collection['TadirahObject'] = $this->TadirahObject->schema();
		$collection['ExternalIdentifierType'] = $this->ProjectsPerson->Person->PersonExternalIdentifier->ExternalIdentifierType->schema();
		
		switch($mode) {
		case 'fielddefinition': break;
		case 'fieldlist':
			foreach($collection as $model => &$modelschema) {
				foreach($modelschema as $field => $def) $modelschema[$field] = '';
			}
			break;
		case 'metadata':
		default:
			foreach($collection as $model => &$modelschema) {
				foreach($modelschema as $field => $def) {
					$modelschema[$field] = '';
					foreach($metadataSkipList as $skip)
						if(	strpos($field, $skip) !== false
						AND	$field != 'identifier'
						AND $field != 'parent_id'
						) unset($modelschema[$field]);
				}
			}
			if(isset($collection['ExternalIdentifierType'])) {
				unset($collection['ExternalIdentifierType']['project']);
				unset($collection['ExternalIdentifierType']['person']);
				unset($collection['ExternalIdentifierType']['institution']);
				unset($collection['ExternalIdentifierType']['deprecated']);
			}
		}
		
		$schema['Project'] = $collection['Project'];
		$schema['Project']['ProjectLink'] = $collection['ProjectLink'];
		$schema['Project']['ProjectLink']['ProjectLinkType'] = $collection['ProjectLinkType'];
		$schema['Project']['ProjectExternalIdentifier'] = $collection['ProjectExternalIdentifier'];
		$schema['Project']['ProjectExternalIdentifier']['ExternalIdentifierType'] = $collection['ExternalIdentifierType'];
		$schema['Project']['ProjectsPerson'] = $collection['ProjectsPerson'];
		$schema['Project']['ProjectsPerson']['Person'] = $collection['Person'];
		$schema['Project']['ProjectsPerson']['PersonProjectRole'] = $collection['PersonProjectRole'];
		$schema['Project']['ProjectsPerson']['Person']['PersonExternalIdentifier'] = $collection['PersonExternalIdentifier'];
		$schema['Project']['ProjectsPerson']['Person']['PersonExternalIdentifier']['ExternalIdentifierType'] = $collection['ExternalIdentifierType'];
		$schema['Project']['ProjectsInstitution'] = $collection['ProjectsInstitution'];
		$schema['Project']['ProjectsInstitution']['Institution'] = $collection['Institution'];
		$schema['Project']['ProjectsInstitution']['InstitutionRole'] = $collection['InstitutionRole'];
		$schema['Project']['ProjectsInstitution']['Institution']['InstitutionExternalIdentifier'] = $collection['InstitutionExternalIdentifier'];
		$schema['Project']['ProjectsInstitution']['Institution']['InstitutionExternalIdentifier']['ExternalIdentifierType'] = $collection['ExternalIdentifierType'];
		$schema['Project']['NwoDiscipline'] = $collection['NwoDiscipline'];
		$schema['Project']['TadirahActivity'] = $collection['TadirahActivity'];
		$schema['Project']['TadirahTechnique'] = $collection['TadirahTechnique'];
		$schema['Project']['TadirahObject'] = $collection['TadirahObject'];
		
		return $schema;
	}
	
	
	
}
?>