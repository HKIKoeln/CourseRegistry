<?php

include_once(APPLIBS.'project_display_functions.php');


$fieldlist = array(
	'Project.name' => array(
		'label' => 'Project Name',
		'class' => 'strong'
	),
	'Project.start_date' => array('label' => 'Start Date'),
	'Project.end_date' => array('label' => 'End Date'),
	'Project.is_phd' => array(
		'label' => 'PhD Project',
		'display' => 'bool'
	)
);
$detailsFieldlist = array(
	'left' => array(
		'Project.links' => array('display' => 'dh_project_links'),
		'Project.institutions' => array('display' => 'dh_project_institutions'),
		'Project.persons' => array('display' => 'dh_project_people'),
		'Project.identifiers' => array(
			'display' => 'dh_identifiers',
			'modelName' => 'ProjectExternalIdentifier'
		)
	),
	'right' => array(
		'Project.description' => array('label' => 'Description'),
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

echo $this->element('projects/barchart');
echo $this->element('pager');
echo $this->element('index');
echo $this->element('pager');



?>