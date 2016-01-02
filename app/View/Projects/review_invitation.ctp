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

<?php
echo $this->Form->create(false, array('class' => 'full','novalidate' => 'novalidate'));
echo $this->Form->input('id', array('type' => 'text', 'label' => 'Project ID'));
echo $this->Form->input('database_contacts', array('type' => 'select'));
echo $this->Form->input('name');
echo $this->Form->input('email');
echo $this->Form->input('from_email', array('value' => 'b.safradin@gmail.com'));
echo $this->Form->input('subject', array('value' => '[DH Project-Registry] Review your project data'));
echo $this->Form->input('body', array('type' => 'textarea'));
echo $this->Form->end('Go!');
?>

<script>
window.jQuery || document.write('<script src="\/\/code.jquery.com\/jquery-1.11.3.min.js"><\/script>')
</script>
<script>
var persons = <?php echo $_serialize['persons']; ?>;
jQuery(document).ready(function() {
	createMessage($('#name').val(), <?php echo $this->request->data['id']; ?>);
	$('#database_contacts').change(function() {
		var option = $('#database_contacts').val();
		var options = window['persons'];
		$('#email').val(persons[option].email);
		$('#name').val(persons[option].name);
		createMessage(persons[option].name, <?php echo $this->request->data['id']; ?>);
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
that have been initiated in the Netherlands, or of international ones in\n\
which a Dutch party was involved. After the successful completion of a\n\
European Digital Humanities Course Registry \n\
http://www.clariah.nl/en/dodh/course-registry,\n\
the Erasmus Studio for e-research has taken up the challenge of creating\n\
a similar structure for Digital Humanities projects.\n\
As a student assistent of the Erasmus Studio it is my task to collect\n\
and structure the data.\n\
\n\
Up to now we have succeeded in identifying 255 digital humanities projects.\n\
However, not all projects provide the required information on their\n\
websites so the database needs to be complemented.\n\
\n\
On the website I have found your name and e-mail as the contact person for\n\
the project. I would like to kindly ask for your feedback on whether I have\n\
represented your project in the appropriate way. Please take a look at the\n\
overview on our website, and indicate whether there are features that are\n\
missing or fields that should be edited.\n\
\n\
This is the link to the summary of your project on our website:\n\
http://dh-projectregistry.org/projects/view/##id##\n\
\n\
This is the link to the review form where you can edit:\n\
http://dh-projectregistry.org/projects/review/##id##\n\
\n\
After the information on the DH projects is complete, the data will be\n\
turned into a visualized searchable database with the support of the KNAW\n\
e-humanities group. The result will be presented on a webpage of the\n\
CLARIAH website.\n\
\n\
It would be great if we could get your support!\n\
\n\
Best wishes,\n\
Barbara Safradin\n\
Student-Assistant Erasmus Studio for E-research, Erasmus University\n\
Rotterdam';
	
	body = replaceAll(body, '##id##', id);
	$('#body').val(replaceAll(body, '##name##', name));
}

</script>