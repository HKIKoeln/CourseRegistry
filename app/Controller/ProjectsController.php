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

class ProjectsController extends AppController {

	
	public function beforeFilter() {
		parent::beforeFilter();
		$paginate = array(
			'Project' => array(
				'contain' => array(
					'ProjectsInstitution' => array(
						'Institution',
						'InstitutionRole'
					),
					'ProjectsPerson' => array(
						'Person',
						'PersonProjectRole',
						'PersonInstitutionRole',
						'Institution'
					),
					'ProjectLink' => array('ProjectLinkType'),
					'ProjectExternalIdentifier' => array('ExternalIdentifierType'),
					'NwoDiscipline',
					'TadirahTechnique',
					'TadirahActivity',
					'TadirahObject'
				)
			)
		);
		$this->paginate = array_merge($this->paginate, $paginate);
		
		$this->Auth->allow(array('index', 'view', 'review', 'reset', 'schema'));
	}
	
	
	
	public function view($id = null) {
		if(empty($id) OR $id == 0) $this->redirect('index');
		$record = $this->Project->find('first', array(
			'conditions' => array('Project.id' => $id),
			'contain' => array(
				'ParentProject',
				'ChildProject',
				'ProjectExternalIdentifier' => array('ExternalIdentifierType'),
				'ProjectLink' => array('ProjectLinkType'),
				'ProjectsPerson' => array(
					'PersonProjectRole', 'Person' => array(
						'PersonExternalIdentifier' => array('ExternalIdentifierType')
					)
				),
				'ProjectsInstitution' => array(
					'InstitutionRole', 'Institution' => array(
						'InstitutionExternalIdentifier' => array('ExternalIdentifierType'),
						'City', 'Country'
					)
				),
				'TadirahActivity',
				'TadirahTechnique',
				'TadirahObject',
				'NwoDiscipline'
			)
		));
		if(empty($record)) $this->redirect('index');
		$this->set('record', $record);
		$this->set('_serialize', array('record'));
	}
	
	
	public function review($id = null) {
		if(empty($id)) $this->redirect('/');
		$admin = false;
		$project = $this->Project->find('first', array('conditions' => array('Project.id' => $id)));
		if(empty($id)) $this->redirect('/');
		
		if(!empty($this->request->data['Project'])) {
			// check the ID has been autorized correctly
			$sid = $this->Session->read('review.Project.id');
			if(empty($sid) OR $id != $sid) $this->redirect('/');
			
			if($this->Project->validateAll($this->request->data)) {
				$this->request->data = $this->Project->data;		// callback beforeValidate manipulates data
				// serialize
				
				if($this->Project->ProjectReview->saveAll($this->request->data, array('validate' => false))) {
					$this->Session->delete('review.Project.id');
					$this->redirect(array('/'));
				}
			}else{
				$this->set('errors', $this->Project->validationErrors);
			}
		}else{
			$this->request->data = $project;
			$this->Session->write('review.Project.id', $id);
		}
		$this->_setOptions($admin);
		$this->render('form');
	}
	
	
	public function schema($mode = null) {
		$schema = $this->Project->getSchema($mode);
		$this->set('DHOECT', $schema);
		$this->set('_serialize', array('DHOECT'));
	}
	
	
	public function edit($id = null) {
		if(empty($id)) $this->redirect(array(
			'controller' => 'users',
			'action' => 'dashboard'
		));
		
		$admin = false;
		$conditions = array('Project.id' => $id);
		if(!$this->Auth->user('is_admin')) $conditions['Project.user_id'] = $this->Auth->user('id');
		else $admin = true;
		
		// check autorisation beforehand
		$project = $this->Project->find('first', array('conditions' => $conditions));
		if(empty($project)) $this->redirect(array(
			'controller' => 'users',
			'action' => 'dashboard'
		));
		
		if(!empty($this->request->data['Project'])) {
			// check the ID has been autorized correctly
			$sid = $this->Session->read('edit.Project.id');
			if(empty($sid) OR $id != $sid) $this->redirect(array(
				'controller' => 'users',
				'action' => 'dashboard'
			));
			
			if(!$admin) {
				$this->request->data['Project']['user_id'] = $this->Auth->user('id');
				$this->request->data['Project']['id'] = $id;
				unset($this->request->data['Project']['created']);
				unset($this->request->data['Project']['updated']);
			}else{
				if(empty($this->request->data['Project']['update'])) {
					$this->request->data['Project']['updated'] = $project['Project']['updated'];
				}
			}
			if($this->Project->validateAll($this->request->data)) {
				$this->request->data = $this->Project->data;		// callback beforeValidate manipulates data
				if($this->Project->saveAll($this->request->data, array('validate' => false))) {
					$this->Session->delete('edit.Project.id');
					$this->redirect(array(
						'controller' => 'users',
						'action' => 'dashboard'
					));
				}
			}else{
				$this->set('errors', $this->Project->validationErrors);
			}
		}else{
			$this->request->data = $project;
			$this->Session->write('edit.Project.id', $id);
		}
		
		$this->_setOptions($admin);
		$this->render('form');
	}
	
	
	protected function _setOptions($admin = false, $project_id = null) {
		$users = array();
		if($admin) $rawUsers = $this->Project->AppUser->find('all', array(
			'contain' => array('Institution' => array('Country')),
			'conditions' => array(
				'AppUser.active' => 1
			),
			'order' => 'AppUser.last_name ASC'
		));
		if(!empty($rawUsers)) {
			$countries = array();
			foreach($rawUsers as $user) {
				$entry = array($user['AppUser']['id'] => $user['AppUser']['name']);
				if(empty($user['AppUser']['institution_id']) OR empty($user['Institution']['Country'])) {
					$users = $users + $entry;
				}else{
					$country = $user['Institution']['Country']['name'];
					if(isset($countries[$country])) $countries[$country] = $countries[$country] + $entry;
					else $countries[$country] = $entry;
				}
			}
			ksort($countries);
			$users = $users + $countries;
		}
		$institutions = $this->Project->Institution->find('list', array(
			'contain' => array('Country'),
			'fields' => array('Institution.id', 'Institution.name', 'Country.name')
		));
		ksort($institutions);
		
		$projectLinkTypes = $this->Project->ProjectLink->ProjectLinkType->find('list');
		
		$options = array(
			'fields' => array('Project.id', 'Project.name'),
			'conditions' => array('Project.has_subprojects' => true)
		);
		if(!empty($project_id)) $options['conditions']['Project.id !='] = $project_id;
		$parents = $this->Project->find('list', $options);
		foreach($parents as $id => $name) {
			$parents[$id] = $id.' - '.$name;
		}
		
		$this->_setTaxonomies();
		
		$this->set(compact(
			'users',
			'institutions',
			'admin',
			'projectLinkTypes',
			'parents'
		));
		$this->set('_serialize', array(
			'institutions',
			'projectLinkTypes'
		));
	}
	
	
	protected function _setTaxonomies() {
		$tadirahObjects = $this->Project->TadirahObject->find('all', array('contain' => array()));
		$tadirahObjectsList = Hash::combine($tadirahObjects, '{n}.TadirahObject.id', '{n}.TadirahObject.name');
		$tadirahTechniques = $this->Project->TadirahTechnique->find('all', array('contain' => array()));
		$tadirahTechniquesList = Hash::combine($tadirahTechniques, '{n}.TadirahTechnique.id', '{n}.TadirahTechnique.name');
		$tadirahActivities = $this->Project->TadirahActivity->find('threaded', array(
			'contain' => array(
				'ParentTadirahActivity',
				'TadirahTechnique'		// both needed for filter extension
			)
		));
		$tadirahActivitiesList = $this->Project->TadirahActivity->find('list');
		
		$nwoDisciplines = $this->Project->NwoDiscipline->find('all', array('contain' => array()));
		$nwoDisciplinesList = Hash::combine($nwoDisciplines, '{n}.NwoDiscipline.id', '{n}.NwoDiscipline.name');
		
		$this->set(compact(
			'tadirahObjects',
			'tadirahTechniques',
			'tadirahActivities',
			'tadirahActivitiesList',
			'tadirahObjectsList',
			'tadirahTechniquesList',
			'nwoDisciplinesList',
			'nwoDisciplines'
		));
	}
	
	
	public function index() {
		$filter = $this->_getFilter();
		
		$this->Paginator->settings = $this->paginate;
		try{
			$records = $this->Paginator->paginate('Project', $filter);
		}catch(NotFoundException $e) {
			$this->redirect(array(
				'controller' => 'projects',
				'action' => 'index'
			));
		}
		
		$projectYears = $this->Project->find('all', array(
			'conditions' => array('Project.active' => 1),
			'order' => array('Project.start_date ASC'),
			'fields' => array('Project.start_date'),
			'contain' => array()
		));
		$years = array(0 => 0);
		if(!empty($projectYears)) {
			foreach($projectYears as $year) {
				$year = $year['Project']['start_date'];
				if(!empty($year)) {
					$year = (int) substr($year, 0, 4);
					if(empty($years[$year])) {
						$years[$year] = 1;
					}else{
						$years[$year] = $years[$year] + 1;
					}
				}else{
					$years[0] = $years[0] + 1;
				}
			}
		}
		$maxCount = max($years);
		$chartData = array(
			'years' => $years,
			'unitY' => floor(338 / $maxCount)
		);
		
		if($this->Auth->user('is_admin')) $this->set('edit', true);
		
		$this->set(compact('records', 'chartData'));
		$this->set('_serialize', array('records'));
	}
	
	
	protected function _getFilter($filter = null) {
		$filter = $this->filter;
		if(empty($filter)) $filter = $this->_setupFilter();
		// set some filter properties that are NOT editable via the filter form - so $this->filter remains empty if no filter is set
		$filter['Project.active'] = 1;	// active will be used as an user-option to unpublish the record
		return $filter;
	}
	
	
	protected function _setupFilter() {
		// check for previously set filters
		
		// !!! SEPARATE courses & projects filters !!!
		//$this->filter = $this->Session->read('projects.filter');
		
		// get/maintain filters
		//$this->_postedFilters();
		//$this->_getFilterOptions_validateFilters();
		
		//$this->Session->write('filter', $this->filter);
		
		// don't store named and extended filters in the session, but set the named to the form!
		//$this->_namedFilters();
		//$this->_filterToForm();
		
		//$this->_extendFilters();
		
		//$this->_setJoins();
		
		return $this->filter;
	}
	
}
?>