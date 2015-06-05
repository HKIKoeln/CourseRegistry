<?php
echo $this->element('pager');
$fieldlist = array(
	'Project.name' => array(
		'label' => 'Project Name',
		'class' => 'strong'
	),
	'Project.start_date' => array('label' => 'Start Date'),
	'Project.end_date' => array('label' => 'End Date'),
	'Project.is_phd' => array('
		label' => 'PhD Project',
		'display' => 'bool'
	)
);
$detailsFieldlist = array(
	'left' => array(
		
	),
	'right' => array(
		'Project.description' => array('label' => 'Description')
	)
);
$this->set(compact('fieldlist', 'detailsFieldlist'));
echo $this->element('index');
echo $this->element('pager');
?>