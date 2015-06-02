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

class CoursesController extends AppController {
	
	
	public $filter = array();
	
	
	
	public function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow(array('index', 'reset'));
		
		if($this->request->is('requested') AND $this->request->params['action'] == 'map') {
			$this->Auth->allow(array('map'));
		}
	}
	
	
	public function index() {
        $filter = $this->_getFilter();
		
		$this->Paginator->settings = $this->paginate;
		try{
			$courses = $this->Paginator->paginate('Course', $filter);
		}catch(NotFoundException $e) {
			$this->redirect(array(
				'controller' => 'courses',
				'action' => 'index'
			));
		}
		
		if($this->Auth->user('is_admin')) $this->set('edit', true);
		
		// set results to view
		$this->set(compact('courses'));
		// Only invoke the map logic, if a filter is set. Otherwise a cached view will be rendered.
		if(!empty($this->filter)) {
			$this->map();
		}
    }
	
	// set the unpaginated results to view for the map
	public function map() {
		$locations = $this->Course->find('all', array(
			'contain' => array('Institution.name'),
			'conditions' => $this->_getFilter(),
			'fields' => array('id','active','name','department','user_id','city_id','country_id','course_type_id','course_parent_type_id','institution_id','lon','lat'),
			'limit' => 1000
		));
		if($this->request->is('requested')) {
            // used by view-caching
			return $locations;
        }else{
            $this->set('locations', $locations);
        }
		$this->render('index');
	}
	
	
	public function reset($filter = null) {
		if(!empty($filter)) {
			// Only remove a single filter key. As the filter keys contain find-conditions in "."-notation, Session::delete() doesn't handle it correctly
			$store = $this->Session->read('filter');
			// special handling for geolocation, because it affects to keys
			if($filter == 'geolocation') {
				unset($store['Course.lon']);
				unset($store['Course.lat']);
			}else{
				unset($store[$filter]);
			}
			$this->Session->write('filter', $store);
		}else{
			// remove all filters
			$this->Session->delete('filter');
		}
		$this->redirect(array(
			'controller' => 'courses',
			'action' => 'index'
		));
	}
	
	
	public function edit($id = null) {
		if(empty($id)) $this->redirect(array(
			'controller' => 'users',
			'action' => 'dashboard'
		));
		
		$admin = false;
		$conditions = array('Course.id' => $id);
		if(!$this->Auth->user('is_admin')) $conditions['Course.user_id'] = $this->Auth->user('id');
		else $admin = true;
		
		// check autorisation beforehand
		$course = $this->Course->find('first', array('conditions' => $conditions));
		if(empty($course)) $this->redirect(array(
			'controller' => 'users',
			'action' => 'dashboard'
		));
		
		if(!empty($this->request->data['Course'])) {
			if(!$admin) {
				$this->request->data['Course']['user_id'] = $this->Auth->user('id');
				$this->request->data['Course']['id'] = $id;
				unset($this->request->data['Course']['created']);
				unset($this->request->data['Course']['updated']);
			}else{
				if(empty($this->request->data['Course']['update'])) {
					$this->request->data['Course']['updated'] = $course['Course']['updated'];
				}
			}
			if(!empty($this->request->data['Course']['skip_validation'])) {
				$this->Course->validator()->remove('url', 'status_ok');
				$this->Course->validator()->remove('guide_url', 'status_ok');
			}
			if($this->Course->validateAll($this->request->data)) {
				$this->request->data = $this->Course->data;		// callback beforeValidate manipulates data
				if($this->Course->saveAll($this->request->data, array('validate' => false))) {
					$this->redirect(array(
						'controller' => 'users',
						'action' => 'dashboard'
					));
				}
			}else{
				$this->set('errors', $this->Course->validationErrors);
			}
		}else{
			$this->request->data = $course;
		}
		
		$this->_setOptions($admin);
		$this->render('form');
	}
	
	
	public function add() {
		$admin = ($this->Auth->user('is_admin')) ? true : false;
		if(!empty($this->request->data['Course'])) {
			if(!$admin) {
				$this->request->data['Course']['user_id'] = $this->Auth->user('id');
				unset($this->request->data['Course']['created']);
				unset($this->request->data['Course']['updated']);
			}
			if(!empty($this->request->data['Course']['skip_validation'])) {
				$this->Course->validator()->remove('url', 'status_ok');
				$this->Course->validator()->remove('guide_url', 'status_ok');
			}
			if($this->Course->validateAll($this->request->data)) {
				$this->request->data = $this->Course->data;		// callback beforeValidate manipulates data
				if($this->Course->saveAll($this->request->data, array('validate' => false))) {
					$this->redirect(array(
						'controller' => 'users',
						'action' => 'dashboard'
					));
				}
			}else{
				$this->set('errors', $this->Course->validationErrors);
			}
		}
		
		$this->_setOptions($admin);
		$this->render('form');
	}
	
	
	public function delete($id = null) {
		if(empty($id)) $this->redirect(array(
			'controller' => 'users',
			'action' => 'dashboard'
		));
		
		$conditions = array('Course.id' => $id);
		if(!$this->Auth->user('is_admin')) $conditions['Course.user_id'] = $this->Auth->user('id');
		
		$this->Course->deleteAll($conditions, $cascade = true);
		
		$this->redirect(array(
			'controller' => 'users',
			'action' => 'dashboard'
		));
	}
	
	
	protected function _setOptions($admin = false) {
		$users = array();
		if($admin) $rawUsers = $this->Course->AppUser->find('all', array(
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
		$universities = $this->Course->Institution->find('list', array(
			'contain' => array('Country'),
			'fields' => array('Institution.id', 'Institution.name', 'Country.name')
		));
		ksort($universities);
		$languages = $this->Course->Language->find('list');
		$types = $this->Course->Type->find('list', array(
			'contain' => array('CourseParentType'),
			'fields' => array('CourseType.id','CourseType.name','CourseParentType.name')
		));
		
		$this->_setTaxonomy();
		
		$this->set(compact(
			'users',
			'universities',
			'languages',
			'types',
			'admin'
		));
	}
	
	
	protected function _setupFilter() {
		// check for previously set filters
		$this->filter = $this->Session->read('filter');
		// get/maintain filters
		$this->_postedFilters();
		$this->_getFilterOptions_validateFilters();
		
		$this->Session->write('filter', $this->filter);
		
		// don't store named and extended filters in the session, but set the named to the form!
		$this->_namedFilters();
		$this->_filterToForm();
		
		$this->_extendFilters();
		
		$this->_setJoins();
		
		return $this->filter;
	}
	
	
	protected function _getFilter() {
		$filter = $this->filter;
		// if the map is invoked by request action, then rebuild the filter
		if(empty($filter)) $filter = $this->_setupFilter();
		// set some filter properties that are NOT editable via the filter form - so $this->filter remains empty if no filter is set
		$filter['Course.active'] = 1;	// active will be used as an user-option to unpublish the record
		$filter['Course.updated >'] = date('Y-m-d H:i:s', time() - Configure::read('App.CourseExpirationPeriod'));
		return $filter;
	}
	
	
	protected function _namedFilters() {
		// get filters from named URL parameters
		if(!empty($this->request->named)) {
			foreach($this->request->named as $key => $value) {
				switch($key) {
					case 'sort':
					case 'direction':
						continue;
					case 'geolocation':
						$expl = explode(',', $value);
						if(!empty($expl[0]) AND !empty($expl[1])) {
							$this->filter['Course.lat'] = $expl[0];
							$this->filter['Course.lon'] = $expl[1];
						}
						break;
					case 'id':
						$this->filter['Course.id'] = $value;
						break;
					case 'country':
						if(!ctype_digit($value)) {
							$value = $this->Course->Country->field('id', array('Country.name' => $value));
						}
						$this->filter['Course.country_id'] = $value;
						break;
					default:
						continue;
				}
			}
		}
	}
	
	// from ['Model']['field'] notation to Model.field notation...
	protected function _postedFilters() {
		// get filters from form data - mention all possible fields explicitly to avoid any trickery
		if(!empty($this->request->data)) {
			if(!empty($this->request->data['Course'])) {
				$form = $this->request->data['Course'];
				
				if(empty($form['country_id'])) unset($this->filter['Course.country_id']);
				else $this->filter['Course.country_id'] = $form['country_id'];
				
				if(empty($form['city_id'])) unset($this->filter['Course.city_id']);
				else $this->filter['Course.city_id'] = $form['city_id'];
				
				if(empty($form['institution_id'])) unset($this->filter['Course.institution_id']);
				else $this->filter['Course.institution_id'] = $form['institution_id'];
				
				if(empty($form['course_parent_type_id'])) unset($this->filter['Course.course_parent_type_id']);
				else $this->filter['Course.course_parent_type_id'] = $form['course_parent_type_id'];
				
				if(empty($form['course_type_id'])) unset($this->filter['Course.course_type_id']);
				else $this->filter['Course.course_type_id'] = $form['course_type_id'];
			}
			// the HABTM filters
			if(!empty($this->request->data['TadirahObject'])) {
				if(!empty($this->request->data['TadirahObject']['TadirahObject']))
					$this->filter['CoursesTadirahObject.tadirah_object_id'] = $this->request->data['TadirahObject']['TadirahObject'];
				else unset($this->filter['CoursesTadirahObject.tadirah_object_id']);
			}
			if(!empty($this->request->data['TadirahTechnique'])) {
				if(!empty($this->request->data['TadirahTechnique']['TadirahTechnique']))
					$this->filter['CoursesTadirahTechnique.tadirah_technique_id'] = $this->request->data['TadirahTechnique']['TadirahTechnique'];
				else unset($this->filter['CoursesTadirahTechnique.tadirah_technique_id']);
			}
			if(!empty($this->request->data['TadirahActivity'])) {
				if(!empty($this->request->data['TadirahActivity']['TadirahActivity']))
					$this->filter['CoursesTadirahActivity.tadirah_activity_id'] = $this->request->data['TadirahActivity']['TadirahActivity'];
				else unset($this->filter['CoursesTadirahActivity.tadirah_activity_id']);
			}
		}
	}
	
	// ... and back from Model.field notation to ['Model']['field'] notation
	protected function _filterToForm() {
		// bring the mangled filter variables back into the filter-form
		if(!empty($this->filter)) {
			foreach($this->filter as $key => $value) {
				$expl = explode('.', $key);
				$model = 'Course';
				$field = $expl[0];
				if(!empty($expl[1])) {
					$model = $expl[0];
					$field = $expl[1];
				}
				switch($model) {
					case 'CoursesTadirahObject':
						$model = $field = 'TadirahObject';
						break;
					case 'CoursesTadirahActivity':
						$model = $field = 'TadirahActivity';
						break;
					case 'CoursesTadirahTechnique':
						$model = $field = 'TadirahTechnique';
						break;
				}
				$this->request->data[$model][$field] = $value;
			}
		}
	}
	
	protected function _setJoins() {
		// set joins for HABTM queries during pagination
		if(!empty($this->filter['CoursesTadirahObject.tadirah_object_id'])) {
			$subquery = $this->Course->find('all', array(
				'joins' => array(
					array(
						'alias' => 'CoursesTadirahObject',
						'table' => 'courses_tadirah_objects',
						'type' => 'INNER',
						'conditions' => 'CoursesTadirahObject.course_id = Course.id'
					)
				),
				'conditions' => array(
					'CoursesTadirahObject.tadirah_object_id' => $this->filter['CoursesTadirahObject.tadirah_object_id']
				),
				'fields' => array('DISTINCT (CoursesTadirahObject.course_id) AS ids_filtered'),
				'contain' => array('CoursesTadirahObject')
			));
			$this->filter['Course.id'] = Set::extract('/CoursesTadirahObject/ids_filtered', $subquery);
			unset($this->filter['CoursesTadirahObject.tadirah_object_id']);
		}
		if(!empty($this->filter['CoursesTadirahTechnique.tadirah_technique_id'])) {
			$subquery = $this->Course->find('all', array(
				'joins' => array(
					array(
						'alias' => 'CoursesTadirahTechnique',
						'table' => 'courses_tadirah_techniques',
						'type' => 'INNER',
						'conditions' => 'CoursesTadirahTechnique.course_id = Course.id'
					)
				),
				'conditions' => array(
					'CoursesTadirahTechnique.tadirah_technique_id' => $this->filter['CoursesTadirahTechnique.tadirah_technique_id']
				),
				'fields' => array('DISTINCT (CoursesTadirahTechnique.course_id) AS ids_filtered'),
				'contain' => array('CoursesTadirahTechnique')
			));
			$this->filter['Course.id'] = Set::extract('/CoursesTadirahTechnique/ids_filtered', $subquery);
			unset($this->filter['CoursesTadirahTechnique.tadirah_technique_id']);
		}
		if(!empty($this->filter['CoursesTadirahActivity.tadirah_activity_id'])) {
			$subquery = $this->Course->find('all', array(
				'joins' => array(
					array(
						'alias' => 'CoursesTadirahActivity',
						'table' => 'courses_tadirah_activities',
						'type' => 'INNER',
						'conditions' => 'CoursesTadirahActivity.course_id = Course.id'
					)
				),
				'conditions' => array(
					'CoursesTadirahActivity.tadirah_activity_id' => $this->filter['CoursesTadirahActivity.tadirah_activity_id']
				),
				'fields' => array('DISTINCT (CoursesTadirahActivity.course_id) AS ids_filtered'),
				'contain' => array('CoursesTadirahActivity')
			));
			$this->filter['Course.id'] = Set::extract('/CoursesTadirahActivity/ids_filtered', $subquery);
			unset($this->filter['CoursesTadirahActivity.tadirah_activity_id']);
		}
	}
	
	protected function _getFilterOptions_validateFilters() {
		// filter logic: if minor doesn't fit major, remove minor from filter
		// get option lists for the filter
		$parentTypes = $this->Course->CourseParentType->find('list');
		$conditions = (empty($this->filter['Course.course_parent_type_id'])) ? array() : array('CourseType.course_parent_type_id' => $this->filter['Course.course_parent_type_id']);
		$types = $this->Course->CourseType->find('list', array('conditions' => $conditions));
		if(!empty($this->filter['Course.course_type_id']) AND !isset($types[$this->filter['Course.course_type_id']])) unset($this->filter['Course.course_type_id']);
		$types = $this->Course->CourseType->find('list', array(
			'contain' => array('CourseParentType'),
			'fields' => array('CourseType.id', 'CourseType.name', 'CourseParentType.name'),
			'conditions' => $conditions
		));
		
		$countries = $this->Course->Country->find('list');
		$conditions = (empty($this->filter['Course.country_id'])) ? array() : array('City.country_id' => $this->filter['Course.country_id']);
		$cities = $this->Course->City->find('list', array('conditions' => $conditions));
		
		if(!empty($this->filter['Course.city_id']) AND !isset($cities[$this->filter['Course.city_id']])) unset($this->filter['Course.city_id']);
		// make a structured list
		$cities = $this->Course->City->find('list', array(
			'contain' => array('Country'),
			'fields' => array('City.id', 'City.name', 'Country.name'),
			'conditions' => $conditions
		));
		ksort($cities);
		
		// filter logic 2 - avoid redundant conditions
		if(!empty($this->filter['Course.city_id'])) unset($this->filter['Course.country_id']);
		if(!empty($this->filter['Course.course_type_id'])) unset($this->filter['Course.course_parent_type_id']);
		
		// child of country & city: university
		$conditions = array();
		if(!empty($this->filter['Course.country_id']))
			$conditions['Institution.country_id'] = $this->filter['Course.country_id'];
		if(!empty($this->filter['Course.city_id']))
			$conditions['Institution.city_id'] = $this->filter['Course.city_id'];
		$universities = $this->Course->Institution->find('list', array('conditions' => $conditions));
		// filter logic 1
		if(!empty($this->filter['Course.institution_id']) AND !isset($universities[$this->filter['Course.institution_id']])) unset($this->filter['Course.institution_id']);
		$universities = $this->Course->Institution->find('list', array(
			'contain' => array('Country'),
			'fields' => array('Institution.id', 'Institution.name', 'Country.name'),
			'conditions' => $conditions
		));
		ksort($universities);
		// filter logic 2
		if(!empty($this->filter['Course.institution_id'])) {
			unset($this->filter['Course.country_id']);
			unset($this->filter['Course.city_id']);
		}
		
		$this->_setTaxonomy();
		
		// set all option lists to view
		$this->set(compact(
			'countries',
			'cities',
			'parentTypes',
			'types',
			'universities'
		));
	}
	
	protected function _setTaxonomy() {
		$tadirahObjects = $this->Course->TadirahObject->find('all', array('contain' => array()));
		$tadirahObjectsList = Hash::combine($tadirahObjects, '{n}.TadirahObject.id', '{n}.TadirahObject.name');
		$tadirahTechniques = $this->Course->TadirahTechnique->find('all', array('contain' => array()));
		$tadirahTechniquesList = Hash::combine($tadirahTechniques, '{n}.TadirahTechnique.id', '{n}.TadirahTechnique.name');
		$tadirahActivities = $this->Course->TadirahActivity->find('threaded', array(
			'contain' => array(
				'ParentTadirahActivity',
				'TadirahTechnique'		// both needed for filter extension
			)
		));
		$tadirahActivitiesList = $this->Course->TadirahActivity->find('list');
		
		$this->set(compact(
			'tadirahObjects',
			'tadirahTechniques',
			'tadirahActivities',
			'tadirahActivitiesList',
			'tadirahObjectsList',
			'tadirahTechniquesList'
		));
	}
	
	//extend the filters according to the TaDiRAH taxonomy relations
	protected function _extendFilters() {
		$addParent = false;
		$addChildren = true;	// add children 3 levels deep (hardcoded... TadiRAH has no more levels.)
		$addTechniques = false;
		
		//hierarchical activities relations: additionally select direct child and parent category
		$activityFilter = false;
		if(!empty($this->filter['CoursesTadirahActivity.tadirah_activity_id']))
			$activityFilter = $this->filter['CoursesTadirahActivity.tadirah_activity_id'];
		
		$techniqueFilter = array();	
		if(!empty($this->filter['CoursesTadirahTechnique.tadirah_technique_id']))
			$techniqueFilter = $this->filter['CoursesTadirahTechnique.tadirah_technique_id'];
		
		if(!empty($this->viewVars['tadirahActivities'])) {
			$tadirahActivities = $this->viewVars['tadirahActivities'];
			$additionalActivities = array();
			
			foreach($tadirahActivities as $pk => $pv) {
				if($addChildren AND $addTechniques AND !empty($pv['TadirahTechnique'])) {
					foreach($pv['TadirahTechnique'] as $tqv) {
						if(!empty($techniqueFilter) AND !in_array($tqv['id'], $techniqueFilter))
							$techniqueFilter[] = $tqv['id'];
					}
				}
				if(!empty($pv['children'])) {
					foreach($pv['children'] as $sk => $sv) {
						if($activityFilter) {
							if(	$addChildren
							AND	in_array($pv['TadirahActivity']['id'], $activityFilter)
							AND	!in_array($sv['TadirahActivity']['id'], $activityFilter)
							) {
								$additionalActivities[] = $sv['TadirahActivity']['id'];
							}
							if(	$addParent
							AND	in_array($sv['TadirahActivity']['id'], $activityFilter)
							AND	!in_array($sv['ParentTadirahActivity']['id'], $activityFilter)
							) {
								$additionalActivities[] = $sv['ParentTadirahActivity']['id'];
							}
						}
						if($addChildren AND $addTechniques AND !empty($sv['TadirahTechnique'])) {
							foreach($sv['TadirahTechnique'] as $tqv) {
								if(!empty($techniqueFilter) AND !in_array($tqv['id'], $techniqueFilter))
									$techniqueFilter[] = $tqv['id'];
							}
						}
						if(!empty($sv['children'])) {
							foreach($sv['children'] as $tk => $tv) {
								if($activityFilter) {
									if(	$addChildren
									AND	in_array($sv['TadirahActivity']['id'], $activityFilter)
									AND	!in_array($tv['TadirahActivity']['id'], $activityFilter)
									) {
										$additionalActivities[] = $tv['TadirahActivity']['id'];
									}
									if(	$addParent
									AND	in_array($tv['TadirahActivity']['id'], $activityFilter)
									AND	!in_array($tv['ParentTadirahActivity']['id'], $activityFilter)
									) {
										$additionalActivities[] = $tv['ParentTadirahActivity']['id'];
									}
								}
								if($addChildren AND $addTechniques AND !empty($tv['TadirahTechnique'])) {
									foreach($tv['TadirahTechnique'] as $tqv) {
										if(!empty($techniqueFilter) AND !in_array($tqv['id'], $techniqueFilter))
											$techniqueFilter[] = $tqv['id'];
									}
								}
							}
						}
					}
				}
			}
			if(!empty($activityFilter)) $this->filter['CoursesTadirahActivity.tadirah_activity_id'] = array_merge($activityFilter, $additionalActivities);
			elseif(!empty($additionalActivities)) $this->filter['CoursesTadirahActivity.tadirah_activity_id'] = $additionalActivities;
			
			if(!empty($techniqueFilter)) $this->filter['CoursesTadirahTechnique.tadirah_technique_id'] = $techniqueFilter;
			
			if(!empty($this->filter['Course.lon']) OR !empty($this->filter['Course.lat'])) {
				unset($this->filter['Course.country_id']);
				unset($this->filter['Course.city_id']);
				unset($this->filter['Course.institution_id']);
			}
			if(!empty($this->filter['Course.id'])) {
				$this->filter = array('Course.id' => $this->filter['Course.id']);
			}
		}
	}
	
}
?>