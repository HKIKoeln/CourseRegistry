<?php

include_once(APPLIBS.'project_display_functions.php');



$fieldlist = array(
	'Project.name' => array(
		'label' => 'Project Name',
		'class' => 'strong'
	),
	'Project.start_date' => array('label' => 'Start Date'),
	'Project.end_date' => array('label' => 'End Date')
);
$detailsFieldlist = array(
	'top' => array(
		'Project.description' => array(
			'label' => 'Description',
			'class' => 'pre-wrap')
	),
	'left' => array(
		'ProjectType.name' => array(
			'label' => 'Resource Type'
		),
		'Project.links' => array('display' => 'dh_project_links'),
		'Project.identifiers' => array(
			'display' => 'dh_identifiers',
			'modelName' => 'ProjectExternalIdentifier'
		),
		'Project.id' => array(
			'display' => 'dh_permalink',
			'label' => 'Permalink'
		)
	),
	'right' => array(
		'Project.disciplines' => array(
			'display' => 'dh_tags',
			'modelName' => 'NwoDiscipline'
		),
		'Project.institutions' => array('display' => 'dh_project_institutions'),
		'Project.persons' => array('display' => 'dh_project_people')
	)
);
$this->set(compact('fieldlist', 'detailsFieldlist'));

echo $this->element('slider');
echo $this->element('pager');
echo $this->element('projects/index');
echo $this->element('pager');



?>