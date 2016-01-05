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
<h2>Review Invitation</h2>

<p>
	Last Invitation: 
	<?php
	if(!empty($project))
		echo $project['Project']['last_invitation_date'] . ' - ' . $project['Project']['last_invitation_address'];
	else echo ' - ';
	?>
</p>
	
<?php
echo $this->Form->create(false, array('class' => 'full','novalidate' => 'novalidate'));
?>
<fieldset>
	<?php
	echo $this->Form->input('id', array('type' => 'text', 'label' => 'Project ID'));
	echo $this->Form->input('database_contacts', array('type' => 'select'));
	echo $this->Form->input('name');
	echo $this->Form->input('email');
	echo $this->Form->input('from_email', array('value' => 'b.safradin@gmail.com'));
	echo $this->Form->input('subject', array('value' => '[DH Project-Registry] Review your project data'));
	echo $this->Form->input('body', array('type' => 'textarea'));
	?>
</fieldset>
<fieldset>
	<?php
	echo $this->Form->input('save_emailaddress_for', array(
		'type' => 'select',
		'options' => $databaseContacts
	));
	echo $this->Form->input('save_mail_as', array(
		'type' => 'select',
		'multiple' => 'checkbox',
		'options' => array(0 => 'personal', 1 => 'projectspecific'),
		'label' => 'Save Emailaddress As'));
	?>
</fieldset>
<fieldset>
<?php
echo $this->Form->end('Go!');
?>
</fieldset>

<script>
window.jQuery || document.write('<script src="\/\/code.jquery.com\/jquery-1.11.3.min.js"><\/script>')
</script>
<script>
var persons = <?php echo $_serialize['persons']; ?>;
jQuery(document).ready(function() {
	
	createMessage($('#name').val(), <?php echo $this->request->data['id']; ?>);
	
	$('#SaveMailAs0').val('person_id.' + persons[$('#database_contacts').val()].person_id);
	$('#SaveMailAs1').val('projects_person_id.' + persons[$('#database_contacts').val()].projects_person_id);
	
	$('#database_contacts').change(function() {
		var option = $('#database_contacts').val();
		$('#email').val(persons[option].email);
		$('#name').val(persons[option].name);
		createMessage(persons[option].name, <?php echo $this->request->data['id']; ?>);
		$('#save_emailaddress_for').val(option);
		$('#SaveMailAs0').val('person_id.' + persons[option].person_id);
		$('#SaveMailAs1').val('projects_person_id.' + persons[option].projects_person_id);
	});
	
	$('#name').change(function() {
		createMessage($(this).val(), <?php echo $this->request->data['id']; ?>);
	});
});

function replaceAll(str, find, replace) {
  return str.replace(new RegExp(find, 'g'), replace);
}

function createMessage(name, id) {
	var body = 'Dear ##name##,\n\
\n\
I am writing to you to ask for your support for the creation of an\n\
exhaustive overview and search environment of Digital Humanities projects\n\
that have been initiated in the Netherlands, or of international DH projects\n\
in which one ore more Dutch parties were involved.\n\
This is a follow-up initiative modeled after the\n\
European Digital Humanities Course Registry:\n\
http://www.clariah.nl/en/dodh/course-registry.\n\
As a student assistent of the Erasmus Studio (Erasmus University Rotterdam)\n\
it is my task to collect and structure the data.\n\
\n\
Up to now we have succeeded in identifying 211 relevant projects.\n\
However, not all project websites provide the information that our database\n\
requires in order to be compatible with the open data model(s) used by similar\n\
digital resources in the Netherlands.\n\
As I have found your name and e-mail as the contact person for one or more\n\
DH projects of your institute I would like to kindly ask your feedback on\n\
whether I have represented your project(s) in the appropriate way.\n\
\n\
This link shows the summary of your project on our website:\n\
http://dh-projectregistry.org/projects/view/##id##\n\
\n\
We would like you to indicate suggestions for change and\n\
additional information in this page:\n\
http://dh-projectregistry.org/projects/review/##id##\n\
\n\
Once all the information on the identified DH projects is complete,\n\
the data will be turned into a visualized searchable database.\n\
This functionality will be supplied by the KNAW e-humanities group.\n\
The result will be presented on a webpage of the CLARIAH website,\n\
the programme that is supporting the initiative.\n\
\n\
It would be great if we could get your support!\n\
\n\
Best wishes,\n\
Barbara Safradin\n\
Student-Assistant Erasmus Studio for E-research, Erasmus University Rotterdam';
	
	body = replaceAll(body, '##id##', id);
	$('#body').val(replaceAll(body, '##name##', name));
}

</script>