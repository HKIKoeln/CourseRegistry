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
 
include_once(APPLIBS.'project_display_functions.php');
 
$detailsFieldlist = array(
	'top' => array(
		'Project.name' => array('class' => 'strong'),
		'Project.description' => array('label' => 'Description')
	),
	'left' => array(
		'Project.parent_project' => array(
			'label' => 'ParentProject',
			'display' => 'dh_get_parent'
		),
		'Project.child_projects' => array(
			'label' => 'Sub-Projects',
			'display' => 'dh_get_children'
		),
		'Project.links' => array('display' => 'dh_project_links'),
		'Project.identifiers' => array(
			'display' => 'dh_identifiers',
			'modelName' => 'ProjectExternalIdentifier'
		),
		'Project.institutions' => array('display' => 'dh_project_institutions'),
		'Project.persons' => array('display' => 'dh_project_people'),
	),
	'right' => array(
		'Project.start_date' => array('label' => 'Start Date'),
		'Project.end_date' => array('label' => 'End Date'),
		'Project.is_phd' => array(
			'label' => 'PhD Project',
			'display' => 'bool'
		),
		'Project.disciplines' => array(
			'display' => 'dh_tags',
			'modelName' => 'NwoDiscipline'
		),
		'Project.activities' => array(
			'display' => 'dh_tags',
			'modelName' => 'TadirahActivity'
		),
		'Project.techniques' => array(
			'display' => 'dh_tags',
			'modelName' => 'TadirahTechnique'
		),
		'Project.objects' => array(
			'display' => 'dh_tags',
			'modelName' => 'TadirahObject'
		)
	)
);
$this->set(compact('fieldlist', 'detailsFieldlist'));
?>
<div class="view record_details">
<?php echo $this->element('projects/view'); ?>
</div>


