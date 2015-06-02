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
	<h2>Edit Profile</h2>
	<?php
	echo $this->Form->create($modelName);
	
	echo $this->Form->input('id', array('disabled' => true, 'type' => 'text'));
	
	echo $this->Form->input('last_login', array('disabled' => true, 'type' => 'text'));
	
	echo $this->Form->input('email', array('disabled' => true, 'required' => false));
	
	echo $this->Html->link('Change E-mail', array(
		'controller' => 'users',
		'action' => 'request_email_verification'
	));
	
	echo $this->Html->link('Change Password', array(
		'controller' => 'users',
		'action' => 'request_new_password'
	));
	
	if(!empty($auth_user['is_admin'])) {
		echo $this->Form->input('is_admin');
		echo $this->Form->input('user_admin');
		echo $this->Form->input('active');
	}
	
	if(Configure::read('Users.loginName') == 'username') {
		echo $this->Form->input('username');
	}
	
	echo $this->Form->input('institution_id', array(
		'label' => 'Institution',
		'empty' => '-- choose institution --'
	));
	
	echo $this->Form->input('academic_title');
	
	echo $this->Form->input('first_name');
	
	echo $this->Form->input('last_name');
	
	echo $this->Form->input('telephone', array(
		'type' => 'text'
	));
	
	echo $this->Form->input('authority', array(
		'type' => 'textarea',
		'label' => 'Remarks'
	));
	
	echo $this->Form->end('Submit');
	?>
	
</div>