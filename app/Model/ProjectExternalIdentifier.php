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

class ProjectExternalIdentifier extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'identifier';
	
	public $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id'
		),
		'ExternalIdentifierType' => array(
			'className' => 'ExternalIdentifierType',
			'foreignKey' => 'external_identifier_type_id'
		)
	);
	
	
	public function getFieldlist() {
		return array(
			'ProjectExternalIdentifier.id' => array(
				'attributes' => array(
					'type' => 'hidden',
					'datarelation' => 'Project.hasmany.ProjectExternalIdentifier'
				)
			),
			'ProjectExternalIdentifier.project_id' => array(
				'attributes' => array(
					'type' => 'hidden',
					'datarelation' => 'Project.hasmany.ProjectExternalIdentifier'
				)
			),
			'ProjectExternalIdentifier.external_identifier_type_id' => array(
				'attributes' => array(
					'type' => 'select',
					'required' => true,
					'datarelation' => 'Project.hasmany.ProjectExternalIdentifier'
				),
				'options' => 'projectExternalIdentifierTypes',
				'label' => 'Identifier Type',
				'empty' => true
			),
			'ProjectExternalIdentifier.identifier' => array(
				'attributes' => array(
					'type' => 'text',
					'required' => true,
					'datarelation' => 'Project.hasmany.ProjectExternalIdentifier'
				)
			)
		);
	}
	
	
	
}
?>