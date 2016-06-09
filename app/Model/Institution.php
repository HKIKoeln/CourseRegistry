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
 * Institution Model
 *
 * @property City $City
 * @property Course $Course
 */
class Institution extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
	
	public $order = 'Institution.name ASC';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'city_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'abbreviation' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'City' => array(
			'className' => 'City',
			'foreignKey' => 'city_id'
		),
		'Country' => array(
			'className' => 'Country',
			'foreignKey' => 'country_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Course' => array(
			'className' => 'Course',
			'foreignKey' => 'institution_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'User' => array(			// this is only an administrative thing
			'className' => 'User',
			'foreignKey' => 'institution_id',
			'dependent' => false
		),
		'ProjectsInstitution' => array(
			'className' => 'ProjectsInstitution',
			'foreignKey' => 'institution_id'
		),
		'ProjectsPerson' => array(
			'className' => 'ProjectsPerson',
			'foreignKey' => 'institution_id'
		),
		'InstitutionExternalIdentifier' => array(
			'className' => 'InstitutionExternalIdentifier',
			'foreignKey' => 'institution_id'
		)
	);
	
	public $hasAndBelongsToMany = array(
		'Project' => array(
			'className' => 'Project',
			'joinTable' => 'projects_institutions',
			'foreignKey' => 'institution_id',
			'associationForeignKey' => 'project_id',
			'unique' => 'keepExisting'
		)
	);
	
	
	
	public function getHierarchicOptions() {
		return $tree = $this->getHierarchicInstitutions();
	}
	
	
	public function getHierarchicInstitutions() {
		// get only institutions that are linked to projects
		$set = $this->ProjectsInstitution->find('all', array(
			'contain' => array('Institution'),
			'order' => array(
				'Institution.parent_id' => 'ASC',
				'Institution.name' => 'ASC'
			)
		));
		$result = array();
		$ids = array();
		foreach($set as $k => $item) {
			$inst = $item['Institution'];
			if(empty($inst['parent_id'])) {
				$result[$inst['id']]['name'] = $inst['name'];
				$plain[$inst['id']] = $inst['name'];
				unset($set[$k]);
				$children = $this->getInstitutionChildren($inst['id'], $set, $plain, 1);
				if(!empty($children)) {
					$result[$inst['id']]['children'] = $children;
				}
			}
		}
		foreach($set as $k => $item) {
			$inst = $item['Institution'];
			//$children = $this->getInstitutionChildren($inst['parent_id'], $set, $plain, $level = 7);
			//if(!empty($children)) { we don't need to check this, as the current is a child itself
			// get the parent's name & ancestors - parent is yet not present in array
			$level = 0;
			$path = array();
			$result = $result + $this->getInstitutionAnchestors($inst['parent_id'], $path, $level);
			// get the children from the set - including the current record
			$children = $this->getInstitutionChildren($inst['parent_id'], $set, $plain, $level);
			if(empty($children)) continue;
			$temp = &$result;
			if(!empty($path)) foreach($path as $key) {
				$temp = &$temp[$key];
			}
			$temp = $children;
			unset($temp);
		}
		return $result;
	}
	
	
	public function getInstitutionAnchestors($id, &$path, &$level) {
		$inst = $this->find('first', array(
			'conditions' => array('Institution.id' => $id)
		));
		$result = array();
		if($inst) {
			$level++;
			array_unshift($path, $inst['Institution']['id'], 'children');
			
			$pre[$inst['Institution']['id']] = array(
				'name' => $inst['Institution']['name'],
				'children' => array()
			);
			//$this->_getIndentation($level - 1) . 
			
			if(!empty($inst['Institution']['parent_id'])) {
				$result = $this->getInstitutionAnchestors($inst['Institution']['parent_id'], $path, $level);
				$temp = &$result;
				if(!empty($path)) foreach($path as $key) {
					$temp = &$temp[$key];
				}
				$temp = $pre;
				unset($temp);
			}else{
				$result = $pre;
			}
			
		}
		return $result;
	}
	
	public function getInstitutionChildren($id, &$set, &$plain, $level) {
		$result = array();
		foreach($set as $k => $item) {
			$inst = $item['Institution'];
			if($inst['parent_id'] === $id) {
				$plain[$inst['id']] = $this->_getIndentation($level) . $inst['name'];
				$result[$inst['id']]['name'] = $inst['name'];
				unset($set[$k]);
				$children = $this->getInstitutionChildren($inst['id'], $set, $plain, $level + 1);
				if(!empty($children)) {
					$result[$inst['id']]['children'] = $children;
				}
			}
		}
		return $result;
	}
	
	
	protected function _getIndentation($level = null) {
		$out = null;
		while($level > 0) {
			$out .= '&nbsp;';
			$level--;
		}
		return $out;
	}

}
