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
?>

<fieldset>
	<h3>Administration Metadata</h3>
	<?php
	if($this->action == 'edit') {
		echo $this->Form->input('id', array(
			'disabled' => true,
			'type' => 'text',
			'label' => 'Project ID'
		));
	}
	
	if(!empty($admin)) {
		echo '<p>Admin: leave this box unchecked to *NOT* update the "last-update" field when saving your revisions.</p>';
		echo $this->Form->input('Project.update', array(
			'type' => 'checkbox',
			'label' => 'Update Timestamp',
			'checked' => false,
			'value' => 1
		));	// do or not update the timestamp 
		echo $this->Form->input('Project.user_id', array(
			'label' => 'Owner',
			'empty' => ' -- nobody -- '
		));
		?>
		
		<p>Mark this Project for further reviews:</p>
		<?php echo $this->Form->input('Project.review', array('label' => 'Review Neccessary')); ?>
		<p>Check if this Review-Dataset has been inserted into it's associated Project:</p>
		<?php echo $this->Form->input('ProjectReview.done', array('label' => 'Update Completed'));
	}
	?>
	<p>Hide this Project or not:</p>
	<?php echo $this->Form->input('Project.active', array('label' => 'Publish')); ?>
</fieldset>
<fieldset>
	<h3>Project</h3>
	<?php
	echo $this->Form->input('Project.name', array('type' => 'textarea'));
	echo $this->Form->input('Project.description');
	echo $this->Form->input('Project.start_date');
	echo $this->Form->input('Project.end_date');
	echo $this->Form->input('Project.is_phd', array('label' => 'Is PhD Project'));
	?>
</fieldset>
<fieldset>
	<h3>Tagging</h3>
	<?php
	echo $this->element('taxonomy/selector', array('habtmModel' => 'NwoDiscipline', 'dropdown' => true));
	echo $this->element('taxonomy/selector', array('habtmModel' => 'TadirahActivity', 'dropdown' => true));
	echo $this->element('taxonomy/selector', array('habtmModel' => 'TadirahTechnique', 'dropdown' => true));
	echo $this->element('taxonomy/selector', array('habtmModel' => 'TadirahObject', 'dropdown' => true));
	echo $this->Form->end('submit');
	?>






