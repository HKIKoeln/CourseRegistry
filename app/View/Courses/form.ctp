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
<h2><?php echo ucfirst($this->action); ?> Course</h2>

<?php
if($this->action == 'edit') {
	?>
	<p class="actions">
	<?php
	echo $this->Html->link('Delete this Course', '/courses/delete/' . $this->request->data['Course']['id'], array(
		'confirm' => "Are you sure? /n
			Courses not updated for " . Configure::read('App.CourseArchivalPeriod') / (60*60*24*365) . " years will be 
			archived for later research and disappear from your dashboard automatically. \n
			Please only remove this entry, if it never will be or was a real course. \n\n
			You can uncheck the 'publish' option if you do not want this entry to display in the registry any longer."
	));
	?>
	</p>
	
	<p>Courses are not displayed any more, if the "last-update" field's date is too old. </p>
	<p>To mark this record as up-to-date, you have to submit this form, even if the information did not change.</p>
	<?php
}

echo $this->Form->create('Course', array('novalidate' => 'novalidate'));

if(!empty($errors)) {
	?>
	<div class="validation-errors">
		<h3>Validation Errors</h3>
		<dl>
			<?php
			foreach($errors as $field => $error) {
				?>
				<dt><?php echo Inflector::humanize($field); ?></dt>
				<dd><?php echo implode('<br />', $error); ?></dd>
				<?php
			}
			?>
		</dl>
	</div>
	<?php
}

if($this->action == 'edit') {
	echo $this->Form->input('id');
	if(!empty($admin)) {
		echo '<p>Admin: leave this box unchecked to *NOT* update the "last-update" field when saving your revisions.</p>';
		echo '<p>Owners of course records are emailed based on the date in the field "last-update" to keep their entries alive.</p>';
		echo $this->Form->input('update', array(
			'type' => 'checkbox',
			'label' => 'Update Timestamp',
			'checked' => false,
			'value' => 1
		));	// do or not update the timestamp 
	}
}
?>

<p>
	Validation has been set up to assist you entering valid content. <br />
	However, sometimes technique plays tricks on us (especially with the URL fields). 
</p>
<p>Please only skip validation if you know why!</p>

<?php
echo $this->Form->input('skip_validation', array(
	'label' => 'Skip URL Validation',
	'type' => 'checkbox',
	'checked' => false,
	'value' => 1
));
?>

<br />

<?php
if(!empty($admin)) {
	echo $this->Form->input('user_id', array(
		'label' => 'Owner',
		'empty' => ' -- nobody -- '
	));
}

echo $this->Form->input('active', array('label' => 'publish'));
echo $this->Form->input('name');
echo $this->Form->input('type_id', array('empty' => ' -- none -- '));
echo $this->Form->input('language_id', array('empty' => ' -- none -- '));
echo $this->Form->input('access_requirements');
echo $this->Form->input('start_date', array('title' => 'One or many course start dates, format YYYY-MM-DD, separated by ";".'));
echo $this->Form->input('recurring', array(
	'title' => 'Check box if the course begins every year at the same date. Uncheck if the course takes place only once.',
	'required' => false
));
echo $this->Form->input('url', array(
	'label' => 'Information URL',
	'title' => 'Course information URL.'
));
echo $this->Form->input('guide_url', array(
	'label' => 'Curriculum URL',
	'title' => 'URL of a course guide (eg a .pdf), that describes the course modules and structure.'
));
echo $this->Form->input('ects', array('title' => 'Decimal numbers only. Optionally use the decimal point.'));
echo $this->Form->input('contact_name');
echo $this->Form->input('contact_mail');
echo $this->Form->input('lat', array('label' => 'Latitude', 'title' => 'Lookup coordinates of the university or department in Google maps.'));
echo $this->Form->input('lon', array('label' => 'Longitude', 'title' => 'Lookup coordinates of the university or department in Google maps.'));
$opts = array('empty' => ' -- none -- ');
if($this->action === 'add' AND !empty($auth_user) AND !empty($auth_user['university_id']))
	$opts = array('selected' => $auth_user['university_id']);
echo $this->Form->input('university_id', $opts);
echo $this->Form->input('department');

echo $this->element('courses/taxonomy/selector', array('habtmModel' => 'TadirahActivity'));

echo $this->element('courses/taxonomy/selector', array('habtmModel' => 'TadirahTechnique'));

echo $this->element('courses/taxonomy/selector', array('habtmModel' => 'TadirahObject'));

echo $this->Form->end('submit');
?>






