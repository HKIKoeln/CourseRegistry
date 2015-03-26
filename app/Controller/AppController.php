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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array(
		'Users.DefaultAuth',
		'DebugKit.Toolbar',
		'Paginator',
		'Session',
		'RequestHandler'
	);
	
	// paging defaults
	public $paginate = array(
		'limit' => 10,
		'maxLimit' => 200
	);
	
	
	public function beforeFilter() {
		// maintain pagination settings
		if($paginate = $this->Session->read('Paginate')) $this->paginate = $paginate;
		if(!empty($this->request->data['Pager'])) {
			$form = $this->request->data['Pager'];
			if(!empty($form['limit']) AND ctype_digit($form['limit'])) {
				$this->paginate['limit'] = $form['limit'];
				$this->Session->write('Paginate.limit', $form['limit']);
			}
		}
		
		if(	!empty($this->request->params['layout'])
		AND	$this->request->params['layout'] == 'iframe'
		) {
			$this->layout = 'iframe';
		}
		
		// disable SSL on the dariah.uni-koeln.de server (bad configuration...)
			$this->Security->requireSecure = array();
		
	}
	
	
	public function beforeRedirect($url, $status = null, $exit = true) {
		if(	!empty($this->request->params['layout'])
		AND	$this->request->params['layout'] == 'iframe'
		AND	strpos($url, 'iframe') === false
		) {
			$url = (strpos($url, '/') === 0) ? '/iframe' . $url : '/iframe/' . $url;
			return array(
				'url' => $url,
				'status' => $status,
				'exit' => $exit
			);
		}
	}
	
	
	
	
	
	
	
	
}





