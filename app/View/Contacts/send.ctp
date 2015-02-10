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
?>
<div class="users_form">
	<h2>Contact</h2>
	<p>Contact us if you have questions or problems. Please check the manual and FAQ first.</p>
	<?php
	echo $this->Form->create('Contact', array('novalidate' => true));
	
	echo $this->Form->input('email', array(
		'label' => 'E-mail',
		'autocomplete' => 'off'
	));
		
	echo $this->Form->input('first_name');
	
	echo $this->Form->input('last_name');
	
	echo $this->Form->input('telephone', array(
		'type' => 'text'
	));
	
	echo $this->Form->input('message', array(
		'type' => 'textarea',
	));
	
	echo $this->Form->end('Submit');
	?>
	
</div>