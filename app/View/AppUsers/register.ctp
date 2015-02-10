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

//$this->extend(APP . 'Plugin/Users/View/Users/register.ctp');
//$this->start('Users.details');

// view extension doesn't work well with Cake's form validation.
?>
<div class="users_form">
	<h2>User Registration</h2>
	<p>Registration for the universities' maintainers of course information.</p>
	<?php
	echo $this->Form->create($modelName, array('novalidate' => true));
	
	if(Configure::read('Users.username')) {
		echo $this->Form->input('username', array(
			'label' => 'Username'
		));
	}
	echo $this->Form->input('email', array(
		'label' => 'E-mail',
		'autocomplete' => 'off'
	));
	
	echo $this->Form->input('password', array(
		'label' => 'Password',
		'type' => 'password',
		'autocomplete' => 'off'
	));
	
	echo $this->Form->input('university_id', array(
		'label' => 'University',
		'empty' => '-- choose university --'
	));
	
	echo $this->Form->input('university', array(
		'label' => 'Other University',
		'type' => 'text',
		'title' => 'Only fill in if you cannot find your university in the dropdown list above.'
	));
	
	echo $this->Form->input('academic_title');
	
	echo $this->Form->input('first_name');
	
	echo $this->Form->input('last_name');
	
	echo $this->Form->input('telephone', array(
		'type' => 'text'
	));
	
	echo $this->Form->input('authority', array(
		'type' => 'textarea',
		'placeholder' => 'Please provide the name of the department or any other contact details that are suitable to prove your authority to add entries to the Digital Humanities Course Registry.',
	));
	
	echo $this->Form->end('Submit');
	?>
	
</div>