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

class ContactsController extends AppController {
	
	
	public $filter = array();
	
	
	
	public function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow(array('send'));
		$this->set('title_for_layout', 'Contact');
	}
	
	public function send() {
			Configure::write('debug', 0);
			$recipient = 'Dh-registry@uni-koeln.de'; //Contact Address
			if(Configure::read('debug' > 0)) $recipient = 'Dh-registry@uni-koeln.de';
			debug($this->request->data);
			if(!empty($this->request->data['Contact'])) {
			// email logic
			App::uses('CakeEmail', 'Network/Email');
			$Email = new CakeEmail();
			$Email->from($this->request->data['Contact']['email'])
   				  ->to($recipient)
   				  
 			      ->subject('New Question')
  				  ->send($this->request->data['Contact']['message']);

				$this->Session->setFlash('Your message has been sent!');
				$this->redirect('/');
			}
			else{

			}
	}
}
?>