<?php
echo $this->element('pager');
$fieldlist = array(
	'Project.name' => array(
		'label' => 'Project Name',
		'class' => 'strong'
	),
);
$detailsFieldlist = array(
	'left' => array(
		
	),
	'right' => array(
		
	)
);
$this->set(compact('fieldlist', 'detailsFieldlist'));
echo $this->element('index');
echo $this->element('pager');
?>