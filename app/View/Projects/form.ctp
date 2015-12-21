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
if($this->action == 'review') {
	?>
	<div class="actions">
		<?php
		echo $this->Html->link('View this record', array(
			'action' => 'view',
			$this->request->data[$modelName]['id']
		));
		?>
	</div>
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
?>
<fieldset>
	<h3>Administration Metadata</h3>
	<?php
	if($this->action == 'edit' OR $this->action == 'review') {
		echo $this->Form->input('id', array('disabled' => true, 'type' => 'text'));
	}
	if($this->action == 'edit' AND !empty($admin)) {
		echo '<p>Admin: leave this box unchecked to *NOT* update the "last-update" field when saving your revisions.</p>';
		echo $this->Form->input('update', array(
			'type' => 'checkbox',
			'label' => 'Update Timestamp',
			'checked' => false,
			'value' => 1
		));	// do or not update the timestamp 
	}
	
	if(!empty($admin)) {
		echo $this->Form->input('user_id', array(
			'label' => 'Owner',
			'empty' => ' -- nobody -- '
		));
		echo $this->Form->input('active', array('label' => 'Publish'));
		echo $this->Form->input('review', array('label' => 'Rewiew neccessary'));
	}
	?>
</fieldset>
<fieldset>
	<h3>Project</h3>
	<?php
	echo $this->Form->input('name', array('type' => 'textarea'));
	echo $this->Form->input('description');
	echo $this->Form->input('start_date');
	echo $this->Form->input('end_date');
	echo $this->Form->input('is_phd', array('label' => 'Is PhD Project'));
	?>
</fieldset>
<fieldset>
	<h3>Hierarchy</h3>
	<?php
	echo $this->Form->input('parent_id', array('empty' => ' - '));
	echo '<p>If you cannot find the parent project in the list above, please provide the parents\' ID, or at least any other hint in the field below:</p>';
	echo $this->Form->input('parent_not_in_list', array('title' => 'Provide an ID, but any hint is appreciated :)'));
	echo '<p>If this project has subprojects, please also have a look if these projects occur in this registry and add this projects\' ID to those.<br>Please also provide the subproject IDs in the field below:</p>';
	echo $this->Form->input('subproject_ids', array('type' => 'text'));
	?>
</fieldset>
<fieldset>
	<h3>Tagging</h3>
	<?php
	echo $this->element('taxonomy/selector', array('habtmModel' => 'NwoDiscipline', 'dropdown' => true));
	echo $this->element('taxonomy/selector', array('habtmModel' => 'TadirahActivity', 'dropdown' => true));
	echo $this->element('taxonomy/selector', array('habtmModel' => 'TadirahTechnique', 'dropdown' => true));
	echo $this->element('taxonomy/selector', array('habtmModel' => 'TadirahObject', 'dropdown' => true));
	?>
</fieldset>
<fieldset>
	<h3>Hyperlinks</h3>
	<?php
	echo $this->Form->input('ProjectLink.projectpresentation');
	echo $this->Form->input('ProjectLink.data');
	echo $this->Form->input('ProjectLink.software');
	echo $this->Form->input('ProjectLink.publication');
	echo $this->Form->input('ProjectLink.additional_links', array('type' => 'textarea'));
	?>
</fieldset>
<fieldset>
	<?php echo $this->Form->end('submit'); ?>
</fieldset>





