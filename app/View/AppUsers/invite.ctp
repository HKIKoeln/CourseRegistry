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
<h2>Invite New Course Maintainer</h2>
<?php
echo $this->Form->create($modelName);

if(Configure::read('Users.username')) {
	echo $this->Form->input('username', array(
		'label' => 'Username'
	));
}
echo $this->Form->input('email', array(
	'autocomplete' => 'off'
));

// no password is required, the invited users are emailed to set it themselves

echo $this->Form->input('institution_id', array(
	'label' => 'Institution',
	'empty' => '-- choose institution --'
));

echo $this->Form->input('academic_title');

echo $this->Form->input('first_name');

echo $this->Form->input('last_name');

echo $this->Form->input('telephone', array('type' => 'text', 'required' => false));

echo $this->Form->input('authority', array(
	'label' => 'Remarks',
	'required' => false,
	'type' => 'textarea',
	'placeholder' => 'Please provide the name of the department or any other contact details that are suitable to proof the users authority to add entries to the Digital Humanities Course Registry.',
));

echo $this->Form->end('Invite');
?>