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
		
		$this->Auth->allow(array('index', 'reset'));
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
		
		$this->set(compact('records'));
	}
	
}
?>