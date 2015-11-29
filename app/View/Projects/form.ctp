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
<h2><?php echo ucfirst($this->action); ?> Project</h2>

<?php
if($this->action == 'edit') {
	?>
	<p class="actions">
	<?php
	echo $this->Html->link('Delete this Project', '/projects/delete/' . $this->request->data['Project']['id'], array(
		'confirm' => "Are you sure? /n
			Please only remove this entry, if it is a duplicate or a mistake. \n\n
			You can uncheck the 'publish' option if you do not want this entry to display in the registry any longer, 
			but keep it in the archive."
	));
	?>
	</p>
	
	<?php
}

echo $this->Form->create('Project', array('novalidate' => 'novalidate'));

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
		echo $this->Form->input('update', array(
			'type' => 'checkbox',
			'label' => 'Update Timestamp',
			'checked' => false,
			'value' => 1
		));	// do or not update the timestamp 
	}
}
?>

<br />

<?php
if(!empty($admin)) {
	echo $this->Form->input('user_id', array(
		'label' => 'Owner',
		'empty' => ' -- nobody -- '
	));
}

echo $this->Form->input('active', array('label' => 'Publish'));
echo $this->Form->input('review', array('label' => 'Rewiew neccessary'));
echo $this->Form->input('name', array('type' => 'textarea'));
echo $this->Form->input('description');
echo $this->Form->input('start_date');
echo $this->Form->input('end_date');
echo $this->Form->input('is_phd');
echo $this->Form->input('has_subprojects');


echo $this->element('courses/taxonomy/selector', array('habtmModel' => 'NwoDiscipline'));

echo $this->element('courses/taxonomy/selector', array('habtmModel' => 'TadirahActivity'));

echo $this->element('courses/taxonomy/selector', array('habtmModel' => 'TadirahTechnique'));

echo $this->element('courses/taxonomy/selector', array('habtmModel' => 'TadirahObject'));

echo $this->Form->end('submit');
?>






