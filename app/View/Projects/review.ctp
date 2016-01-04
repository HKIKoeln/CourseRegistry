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
<h2>Review Project Information</h2>
<p class="strong">Submitting the form saves your input, you may continue editing .</p>

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
		echo $this->Html->link('View this record\'s summary', array(
			'action' => 'view',
			$this->request->data['Project']['id']
		));
		?>
		</p>
		<p class="actions">
		<?php
		echo $this->Html->link('Reset the form (discard all user input!)', array(
			'action' => 'review',
			$this->request->data['Project']['id']
		));
		?>
	</div>
	<?php
}

echo $this->Form->create('Project', array(
	'novalidate' => 'novalidate',
	'class' => 'review',
	'id' => 'ProjectReviewForm'
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
		echo $this->Form->input('Project.id', array(
			'disabled' => true,
			'type' => 'text',
			'label' => 'Project ID',
			'datapath' => 'Project.id'
		));
	}
	
	if($this->action == 'review')
		echo $this->Form->input('ProjectReview.id', array('type' => 'hidden'));
	if($this->action == 'edit')
		echo $this->Form->input('ProjectReview.id', array(
			'disabled' => true,
			'type' => 'text',
			'label' => 'Review ID',
			'datapath' => 'ProjectReview.id'
		));
	
	// show this field on the right pane if we're acting as an admin...
	if($admin) {
		$options = array(
			'type' => 'textarea',
			'label' => 'Changeset (readonly)',
			'readonly' => true,
			'div' => array('class' => 'rightpane')
		);
	}else{
		$options = array('type' => 'hidden');
	}
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
		'placeholder' => 'Additional comments, hints etc. this form doesn\'t cover.                           
Eg. consider people involved or institutions and their respective roles and identifiers (see the summary for this).'
	));
	?>
</fieldset>

<div id="replicate">
	<fieldset>
		<h3>Project</h3>
		<?php
		echo $this->Form->input('Project.name', array('type' => 'textarea',
				'datapath' => 'Project.name'));
		echo $this->Form->input('Project.description', array('datapath' => 'Project.description'));
		echo $this->Form->input('Project.start_date', array('datapath' => 'Project.start_date'));
		echo $this->Form->input('Project.end_date', array('datapath' => 'Project.end_date'));
		echo $this->Form->input('Project.funding_body', array(
			'label' => 'Funding Body(ies)',
			'datapath' => 'Project.funding_body'));
		echo $this->Form->input('Project.funding_size', array(
			'label' => 'Size of Grant',
			'datapath' => 'Project.funding_size'));
		echo $this->Form->input('Project.currency_id', array(
			'label' => 'Grant Currency',
			'datapath' => 'Project.currency_id',
			'empty' => true));
		echo $this->Form->input('Project.is_phd', array(
				'label' => 'Is PhD Project',
				'datapath' => 'Project.is_phd'));
		echo $this->Form->input('Project.phd_students', array(
			'label' => 'PhD Students involved',
			'datapath' => 'Project.phd_students'));
		?>
	</fieldset>
	<fieldset>
		<h3>Hierarchy</h3>
		<?php echo $this->Form->input('Project.parent_id', array('empty' => ' - ',
				'datapath' => 'Project.parent_id',
				'label' => 'Parent Project')); ?>
		<p>
			If the parent project is not listed in our DH-Project Registry, 
			please provide at least a link or any hint, as we might want to add it!
		</p>
		<?php echo $this->Form->input('Project.parent_not_listed', array(
			'datapath' => 'Project.parent_not_listed',
			'label' => 'Parent Project not listed')); ?>
		<p>
			If this project has subprojects, please check if these projects occur 
			in the DH-Projectregistry and add this projects' ID to those.<br>
			Please also provide the subproject IDs or any other hint (eg. link) in the field below:
		</p>
		<?php echo $this->Form->input('Project.subproject_ids', array('type' => 'text',
				'datapath' => 'Project.subproject_ids',
				'label' => 'Subprojects')); ?>
	</fieldset>
	<fieldset>
		<h3>Tagging</h3>
		<?php
		echo $this->element('taxonomy/selector', array('habtmModel' => 'NwoDiscipline', 'dropdown' => true,
			'label' => 'NWO Disciplines'));
		?>
		<h4>TaDiRAH</h4>
		<p>
			Taxonomy of Digital Research Activities in the Humanities. 
			<?php echo $this->Html->link('Link', 'http://tadirah.dariah.eu/vocab/sobre.php', array('style' => 'display: inline')); ?>
		</p>
		<?php
		echo $this->element('taxonomy/selector', array('habtmModel' => 'TadirahActivity', 'dropdown' => true,
			'label' => 'Research Activities'));
		echo $this->element('taxonomy/selector', array('habtmModel' => 'TadirahTechnique', 'dropdown' => true,
			'label' => 'Research Techniques'));
		echo $this->element('taxonomy/selector', array('habtmModel' => 'TadirahObject', 'dropdown' => true,
			'label' => 'Research Objects'));
		?>
	</fieldset>
	<fieldset id="ProjectLink">
		<h3>Hyperlinks to relevant web-based resources</h3>
	</fieldset>
	<fieldset id="ProjectExternalIdentifier">
		<h3>External Identifiers</h3>
		<p>
			Currently the database only recognizes so-called<br>
			NARCIS project identifiers (OND[number]).<br>
			For information on NARCIS, see <?php echo $this->Html->link('www.narcis.nl','http://www.narcis.nl/', 
			array('style' => 'display: inline')); ?><br>
			Please leave a note in the comment field above, if your project has an identifier from another registry.
		</p>
	</fieldset>
	
	<?php
	$this->set('record',$this->request->data);
	include_once(APPLIBS.'project_display_functions.php');
	?>
	<fieldset>
		<h3>Institutes involved</h3>
		<p>
			A review form for the Institution-Registry is not yet available (deeply nested data).<br>
			Please review the given information though, and provide any additional 
			information in the freetext field beyond.<br>
			Consider also additional identifiers, eg. from VIAF, ISNI.
		</p>
		<?php
		echo $this->element('definitionlist', array('fieldlist' => array(
			'Project.institutions' => array(
				'display' => 'dh_project_institutions',
				'label' => 'Institutes involved'))));
		echo $this->Form->input('ProjectReview.institutions_comment', array(
			'type' => 'textarea',
			'label' => 'Institution Comment'
		));
		?>
	</fieldset>
	<fieldset>
		<h3>Persons involved</h3>
		<p>
			A review form for the Person-Registry is not yet available (deeply nested data).<br>
			Please review the given information though, and provide any additional 
			information in the freetext field beyond.<br>
			Consider also additional identifiers, eg. from ORCID, VIAF, ISNI.
		</p>
		<?php
		echo $this->element('definitionlist', array('fieldlist' => array(
			'Project.persons' => array(
				'display' => 'dh_project_people',
				'label' => 'Persons involved'))));
		echo $this->Form->input('ProjectReview.people_comment', array(
			'type' => 'textarea',
			'label' => 'Persons Comment'
		));
		?>
	</fieldset>
</div>

<fieldset>
	<?php echo $this->Form->end('save'); ?>
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

var projectIdentifiers = record.ProjectExternalIdentifier;
var projectExternalIdentifierTypes = <?php echo $_serialize['projectExternalIdentifierTypes']; ?>;
var projectExternalIdentifierFieldlist = <?php echo $_serialize['projectExternalIdentifierFieldlist']; ?>;

jQuery(document).ready(function() {
	var reviewForm = new HasManyForm(
		'#ProjectReviewForm',
		'#ProjectReviewChangesetJson',
		'#ProjectReviewChangesetJson, #ProjectReviewEmail, #ProjectReviewComment, #ProjectReviewDone',
		record
		// no schema, nothing to build or populate here
	);
	
	reviewForm.populateForm($('#ProjectLink'), projectLinkFieldlist, projectLinks);
	reviewForm.populateForm($('#ProjectExternalIdentifier'), projectExternalIdentifierFieldlist, projectIdentifiers);
	
	reviewForm.watchForm('#replicate', '#ProjectReviewFormJson');
	
});
</script>





