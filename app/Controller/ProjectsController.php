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
		
		
		if(Configure::read('debug') > 0) {
			// whyever - allow('*') does not work
			$this->Auth->allow(array('index', 'view', 'review', 'reset', 'schema', 'review_invitation', 'institutions'));
		}else{
			$this->Auth->allow(array('index', 'view', 'review', 'reset', 'schema'));
		}
	}
	
	
	
	public function view($id = null) {
		if(empty($id) OR $id == 0) $this->redirect('index');
		$record = $this->Project->getProject($id);
		if(empty($record)) $this->redirect('index');
		$this->set('record', $record);
		$this->set('_serialize', array('record'));
	}
	
	
	private function _getAdress($key, $idOnly = false) {
		$data = explode(' ', trim($this->request->data[$key]));
		$count = count($data);
		$mailId = str_replace(array('>','<'), '', array_pop($data));
		if($idOnly) {
			return $mailId;
		}
		if(!empty($data)) {
			$name = implode(' ', $data);
			return array($mailId => $name);
		}
		return array($mailId);
	}
	
	
	public function review_invitation($project_id = null) {
		if(empty($project_id)) $this->redirect('index');
		$project = $this->Project->find('first', array(
			'conditions' => array('Project.id' => $project_id),
			'contain' => array('Person' => array('ProjectsPerson' => array('PersonProjectRole')))
		));
		if(!empty($this->request->data)) {
			if(empty($this->request->data['name'])) {
				$to = $this->_getAdress('email');
			}else{
				$to = array($this->_getAdress('email', $idOnly = true) => $this->request->data['name']);
			}
			// email logic
			App::uses('CakeEmail', 'Network/Email');
			// format defaults to text
			// email input: String with email, Array with email as key, name as value or email as value (without name)
			$Email = new CakeEmail();
			try{
				$Email->from('noreply@dh-projectregistry.org')
				->replyTo($this->_getAdress('from_email'))
				->to($to)
				->bcc($this->_getAdress('from_email'))
				->subject($this->request->data['subject'])
				->send($this->request->data['body']);
				
				// executed on success:
				$data = array(
					'id' => $project_id,
					'last_invitation_date' => date('Y-m-d H:i:s'),
					'last_invitation_address' => $this->_getAdress('email', $idOnly = true)
				);
				$this->Project->save($data, $validate = false);
				
				if(!empty($this->request->data['save_mail_as'])) {
					$keys = array();
					foreach($this->request->data['save_mail_as'] as $value) {
						$split = explode('.', $value);
						if(!empty($split[1])) {
							$data = array(
								'id' => $split[1],
								'email' => $this->_getAdress('email', $idOnly = true)
							);
							switch($split[0]) {
							case 'person_id':
								$this->Project->Person->save($data, false);
								break;
							case 'projects_person_id':
								$this->Project->ProjectsPerson->save($data, false);
								break;
							}
						}
						$keys[$split[0]] = $split[1];
					}
				}
				
				$this->Session->setFlash('Invitation has been sent to ' . $this->_getAdress('email', $idOnly = true));
				$this->redirect('index');
			}
			catch(Exception $e) {
				$this->Session->setFlash('Something went wrong: ' . $e->getMessage());
			}
		}
		$persons = $databaseContacts = array();
		if(!empty($project)) {
			if(!empty($project['Person'])) {
				foreach($project['Person'] as $person) {
					$email = $role = null;
					if(!empty($person['email'])) $email = $person['email'];
					if(	empty($email)
					AND	!empty($person['ProjectsPerson']['email']))
						$email = $person['ProjectsPerson']['email'];
					$keys = array('title','name');
					$name = array('title' => 'Mr/Mrs');
					foreach($keys as $key) {
						if(!empty($person[$key])) $name[$key] = $person[$key];
					}
					$namestring = implode(' ', $name);
					if(!empty($person['ProjectsPerson'][0]['PersonProjectRole']['name'])) {
						$role = $person['ProjectsPerson'][0]['PersonProjectRole']['name'];
					}
					$persons[] = array(
						'name' => $namestring,
						'email' => $email,
						'role' => $namestring . ' (' . $role . ')',
						'person_id' => $person['id'],
						'projects_person_id' => $person['ProjectsPerson']['id']
					);
				}
				foreach($persons as $key => $set) {
					$databaseContacts[$key] = $set['role'];
				}
			}
		}
		$this->set(compact('databaseContacts', 'project'));
		$this->viewVars['_serialize']['persons'] = json_encode($persons);
		$this->request->data['id'] = $project_id;
		if(!empty($persons)) $this->request->data['name'] = $persons[0]['name'];
		if(!empty($persons)) $this->request->data['email'] = $persons[0]['email'];
	}
	
	
	public function review($id = null) {
		if(empty($id)) $this->redirect('index');
		$admin = false;
		$project = $this->Project->getProject($id);
		
		if(empty($project)) $this->redirect('index');
		
		if(!empty($this->request->data['ProjectReview'])) {
			$this->loadModel('ProjectReview');
			// check the ID has been autorized correctly
			$sid = $this->Session->read('review.Project.id');
			if(empty($sid) OR $id != $sid) $this->redirect('index');
			$this->request->data['ProjectReview']['project_id'] = $id;
			
			$this->ProjectReview->set($this->request->data['ProjectReview']);
			if($this->ProjectReview->validates()) {
				if($this->ProjectReview->save($this->request->data, array('validate' => false))) {
					// don't redirect, don't destroy the session - let people save their form
					$this->Session->write('review.Project.id', $id);
					$this->request->data['Project']['id'] = $id;
					$this->request->data['ProjectReview']['id'] = $this->ProjectReview->id;
					$this->Session->setFlash('Your input has been saved. You may continue editing, until your session expires.');
				}
			}else{
				$this->set('errors', $this->ProjectReview->validationErrors);
				$this->request->data['Project']['id'] = $id;
			}
		}else{
			$this->request->data = $project;
			$this->Session->write('review.Project.id', $id);
		}
		$this->_setEditOptions($admin, $id);
		$this->_setSchemas();
		
		$this->viewVars['_serialize']['project'] = json_encode($project);
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
		
		$this->_setEditOptions($admin);
		$this->render('form');
	}
	
	
	protected function _setEditOptions($admin = false, $project_id = null) {
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
		$projectExternalIdentifierTypes = $this->Project->ProjectExternalIdentifier->ExternalIdentifierType->find('list', array(
			'conditions' => array('ExternalIdentifierType.project' => true)
		));
		
		$currencies = $this->Project->Currency->find('list');
		
		$options = array(
			'fields' => array('Project.id', 'Project.name'),
			'order' => array('Project.name' => 'ASC'),
			//'conditions' => array('Project.has_subprojects' => true)
		);
		if(!empty($project_id)) $options['conditions']['Project.id !='] = $project_id;
		$parents = $this->Project->find('list', $options);
		//foreach($parents as $id => $name) {
		//	$parents[$id] = $id.' - '.$name;
		//}
		
		$this->_setTaxonomies();
		
		$this->set(compact(
			'users',
			'institutions',
			'admin',
			'parents',
			'currencies'
		));
		$this->viewVars['_serialize']['projectLinkTypes'] = json_encode($projectLinkTypes);
		$this->viewVars['_serialize']['projectExternalIdentifierTypes'] = json_encode($projectExternalIdentifierTypes);
	}
	
	
	protected function _setSchemas() {
		$projectLinkFieldlist = $this->Project->ProjectLink->getFieldlist();
		$projectExternalIdentifierFieldlist = $this->Project->ProjectExternalIdentifier->getFieldlist();
		
		$this->viewVars['_serialize']['projectLinkFieldlist'] = json_encode($projectLinkFieldlist);
		$this->viewVars['_serialize']['projectExternalIdentifierFieldlist'] = json_encode($projectExternalIdentifierFieldlist);
	}
	
	
	public function institutions() {
		$institutions = $this->Project->Institution->find('list', array(
			'order' => 'Institution.name ASC',
			'conditions' => array(
				'OR' => array(
					'AND' => array(
						'Institution.country_id' => 1,	// the Netherlands
						'Institution.parent_id' => null
					),
					/*
					'AND' => array(
						'Institution.parent_id' => null,
						'Institution.id >=' => 1000
					)
					*/
				)
			)
		));
		
		$result = $this->_getInstitutionChildren($institutions);
		
		debug($result);
		exit;
	}
	
	protected function _getInstitutionChildren($institutions) {
		$result = array();
		foreach($institutions as $id => $name) {
			$children = $this->Project->Institution->find('list', array(
				'conditions' => array(
					'Institution.parent_id' => $id
				),
				'order' => 'Institution.name ASC'
			));
			$result[$id] = array('name' => $name);
			if(!empty($children)) {
				$children = $this->_getInstitutionChildren($children);
				$result[$id]['children'] = $children;
			}
		}
		return $result;
	}
	
	protected function _setFilterOptions() {
		$nwoDisciplines = $this->Project->NwoDiscipline->find('all', array(
			'contain' => array(),
			'order' => 'NwoDiscipline.name ASC'
		));
		
		$institutions = $this->Project->Institution->find('list', array(
			//'contain' => array('Country'),
			//'fields' => array('Institution.id', 'Institution.name', 'Country.name'),
			'order' => 'Institution.name ASC',
			'conditions' => array(
				'OR' => array(
					'Institution.id >=' => 1000,
					'Institution.country_id' => 1	// the Netherlands
				)
			)
		));
		
		$projectTypes = $this->Project->ProjectType->find('list', array('order' => 'ProjectType.name ASC'));
		
		$this->set(compact(
			'nwoDisciplines',
			'institutions',
			'projectTypes'
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
		if(empty($this->request->params['ext'])) {
			$filter = $this->_getFilter();
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
						'ProjectType',
						'ProjectLink' => array('ProjectLinkType'),
						'ProjectExternalIdentifier' => array('ExternalIdentifierType'),
						'NwoDiscipline',
						'TadirahTechnique',
						'TadirahActivity',
						'TadirahObject'
					),
					'order' => array('Project.name' => 'ASC')
				)
			);
			$this->paginate = array_merge($this->paginate, $paginate);
			$this->paginate['Project']['limit'] = $this->paginate['limit'];
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
			$this->_setFilterOptions();
			$this->set(compact('records', 'chartData'));
		}else{
			// JSON or XML export
			$records = $this->Project->find('all', $paginate['Project']);
			$this->set(compact('records'));
			$this->set('_serialize', array('records'));
		}
	}
	
	
	protected function _getFilter($filter = null) {
		$filter = $this->filter;
		if(empty($filter)) $filter = $this->_setupFilter();
		// set some filter properties that are NOT editable via the filter form - so $this->filter remains empty if no filter is set
		$filter['Project.active'] = 1;	// active will be used as an user-option to unpublish the record
		return $filter;
	}
	
	
	protected function _setupFilter() {
		parent::_setupFilter();
// don't store named and extended filters in the session, but set the named to the form!
		//$this->_namedFilters();
		$this->_filterToForm();
		
		$this->_setJoins();
		
		return $this->filter;
	}
	
	
	// from ['Model']['field'] notation to Model.field notation...
	protected function _postedFilters() {
		// get filters from form data - mention all possible fields explicitly to avoid any trickery
		if(!empty($this->request->data)) {
			if(!empty($this->request->data['Project'])) {
				$form = $this->request->data['Project'];
				
				if(empty($form['institution_id'])) unset($this->filter['ProjectsInstitution.institution_id']);
				else $this->filter['ProjectsInstitution.institution_id'] = $form['institution_id'];
				
				if(empty($form['project_type_id'])) unset($this->filter['Project.project_type_id']);
				else $this->filter['Project.project_type_id'] = $form['project_type_id'];
			}
			// the HABTM filters
			if(!empty($this->request->data['NwoDiscipline'])) {
				if(!empty($this->request->data['NwoDiscipline']['NwoDiscipline']))
					$this->filter['ProjectsNwoDiscipline.nwo_discipline_id'] = $this->request->data['NwoDiscipline']['NwoDiscipline'];
				else unset($this->filter['ProjectsNwoDiscipline.nwo_discipline_id']);
			}
		}
	}
	
	// ... and back from Model.field notation to ['Model']['field'] notation
	protected function _filterToForm() {
		// bring the mangled filter variables back into the filter-form
		if(!empty($this->filter)) {
			foreach($this->filter as $key => $value) {
				$expl = explode('.', $key);
				$model = 'Project';
				$field = $expl[0];
				if(!empty($expl[1])) {
					$model = $expl[0];
					$field = $expl[1];
				}
				switch($model) {
					case 'ProjectsInstitution':
						$model = 'Project';
						$field = 'institution_id';
						break;
					case 'ProjectsNwoDiscipline':
						$model = $field = 'NwoDiscipline';
						break;
				}
				$this->request->data[$model][$field] = $value;
			}
		}
	}
	
	protected function _setJoins() {
		// set joins for HABTM queries during pagination
		if(!empty($this->filter['ProjectsInstitution.institution_id'])) {
			$subquery = $this->Project->find('all', array(
				'joins' => array(
					array(
						'alias' => 'ProjectsInstitution',
						'table' => 'projects_institutions',
						'type' => 'INNER',
						'conditions' => 'ProjectsInstitution.project_id = Project.id'
					)
				),
				'conditions' => array(
					'ProjectsInstitution.institution_id' => $this->filter['ProjectsInstitution.institution_id']
				),
				'fields' => array('DISTINCT (ProjectsInstitution.project_id) AS ids_filtered'),
				'contain' => array('ProjectsInstitution')
			));
			$this->filter['Project.id'] = Set::classicExtract($subquery, '{n}.ProjectsInstitution.ids_filtered');
			unset($this->filter['ProjectsInstitution.institution_id']);
		}
		if(!empty($this->filter['ProjectsNwoDiscipline.nwo_discipline_id'])) {
			$subquery = $this->Project->find('all', array(
				'joins' => array(
					array(
						'alias' => 'ProjectsNwoDiscipline',
						'table' => 'projects_nwo_disciplines',
						'type' => 'INNER',
						'conditions' => 'ProjectsNwoDiscipline.project_id = Project.id'
					)
				),
				'conditions' => array(
					'ProjectsNwoDiscipline.nwo_discipline_id' => $this->filter['ProjectsNwoDiscipline.nwo_discipline_id']
				),
				'fields' => array('DISTINCT (ProjectsNwoDiscipline.project_id) AS ids_filtered'),
				'contain' => array('ProjectsNwoDiscipline')
			));
			$this->filter['Project.id'] = Set::extract('/ProjectsNwoDiscipline/ids_filtered', $subquery);
			unset($this->filter['ProjectsNwoDiscipline.nwo_discipline_id']);
		}
	}
	
}
?>