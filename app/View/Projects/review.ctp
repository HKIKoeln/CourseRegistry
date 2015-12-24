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

echo $this->Form->create('Project', array(
	'novalidate' => 'novalidate',
	'class' => 'review'
));

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
		echo $this->Form->input('id', array(
			'disabled' => true,
			'type' => 'text',
			'label' => 'Project ID',
			'datapath' => 'Project.id'
		));
	}
	
	// show this field on the right pane if we're acting as an admin...
	$options = array('type' => 'hidden');
	
	$options = array(
		'type' => 'textarea',
		'label' => 'Changeset',
		'div' => array('class' => 'rightpane')
	);
	
	echo $this->Form->input('ProjectReview.changeset_json', $options);
	
	if(!empty($admin)) {
		echo $this->Form->input('Project.user_id', array(
			'label' => 'Owner',
			'empty' => ' -- nobody -- ',
			'datapath' => 'Project.user_id'
		));
		?>
		<p>Hide this Project or not:</p>
		<?php echo $this->Form->input('Project.active', array(
			'label' => 'Publish',
			'datapath' => 'Project.active')); ?>
		<p>Mark this Project for further reviews:</p>
		<?php echo $this->Form->input('Project.review', array(
			'label' => 'Review Neccessary',
			'datapath' => 'Project.review')); ?>
		
		
		<?php echo $this->Form->input('ProjectReview.id', array('type' => 'hidden', 'datapath' => 'ProjectReviev.id')); ?>
		<p>Check if this Review-Dataset has been inserted into it's associated Project:</p>
		<?php echo $this->Form->input('ProjectReview.done', array('label' => 'Update Completed'));
	}
	
	echo $this->Form->input('ProjectReview.email', array('label' => 'Reviewer Email'));
	echo $this->Form->input('ProjectReview.comment', array(
		'type' => 'textarea',
		'placeholder' => 'Additional comments, hints etc. this form doesn\'t cover.'
	));
	?>
</fieldset>
<fieldset>
	<h3>Project</h3>
	<?php
	echo $this->Form->input('Project.name', array('type' => 'textarea',
			'datapath' => 'Project.name'));
	echo $this->Form->input('Project.description', array('datapath' => 'Project.description'));
	echo $this->Form->input('Project.start_date', array('datapath' => 'Project.start_date'));
	echo $this->Form->input('Project.end_date', array('datapath' => 'Project.end_date'));
	echo $this->Form->input('Project.is_phd', array('label' => 'Is PhD Project',
			'datapath' => 'Project.is_phd'));
	?>
</fieldset>
<fieldset>
	<h3>Hierarchy</h3>
	<?php echo $this->Form->input('Project.parent_id', array('empty' => ' - ',
			'datapath' => 'Project.parent_id')); ?>
	<p>
		If the parent project is not listed in our DH-Project Registry, 
		please provide at least a link or any hint, as we might want to add it!
	</p>
	<?php echo $this->Form->input('Project.parent_not_listed', array('datapath' => 'Project.parent_not_listed')); ?>
	<p>
		If this project has subprojects, please check if these projects occur 
		in the DH-Project Registry and add this projects\' ID to those.<br>
		Please also provide the subproject IDs or any other hint (eg. link) in the field below:
	</p>
	<?php echo $this->Form->input('Project.subproject_ids', array('type' => 'text',
			'datapath' => 'Project.subproject_ids')); ?>
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
<fieldset id="ProjectLink">
	<h3>Hyperlinks</h3>
</fieldset>
<fieldset>
	<?php echo $this->Form->end('submit'); ?>
</fieldset>


<script>
window.jQuery || document.write('<script src="\/\/code.jquery.com\/jquery-1.11.3.min.js"><\/script>')
</script>
<?php echo $this->Html->script('HasManyForm.js'); ?>

<script>

var record = <?php echo $_serialize['project']; ?>;

var projectLinks = record.ProjectLink;
var projectLinkTypes = <?php echo $_serialize['projectLinkTypes']; ?>;
var projectLinkFieldlist = <?php echo $_serialize['projectLinkFieldlist']; ?>;

jQuery(document).ready(function() {
	var parentForm = new HasManyForm(
		'#ProjectReviewForm',
		'#ProjectReviewChangesetJson',
		'#ProjectReviewChangesetJson, #ProjectReviewEmail, #ProjectReviewComment, #ProjectReviewDone',
		record
		// no schema, nothing to build or populate here
	);
	
	var hyperlinks = $('#ProjectLink');
	var hyperlinks = new HasManyForm(
		null,null,null,
		projectLinks,
		projectLinkFieldlist
	);
	hyperlinks.populateForm($('#ProjectLink'), projectLinkFieldlist, projectLinks);
	
	parent.watchForm();
});
</script>





