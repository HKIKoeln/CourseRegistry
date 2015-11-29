<?php



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
		'Project.identifiers' => array('display' => 'dh_project_identifiers')
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





// custom output functions, called from the generic definitionlist template!

function dh_project_links($obj = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData('ProjectLink', $record);
	$content = null;
	if(!empty($modelData)) {
		foreach($modelData as $k => $row) {
			$title = (!empty($row['title'])) ? $row['title'] : null;
			$descr = (!empty($row['description'])) ? $row['description'] : null;
			if(!empty($row['url'])) {
				if($title == $record['Project']['name'] OR empty($title))
					$title = ucwords($row['ProjectLinkType']['name']);
				$content .= $obj->Html->link($title, $row['url'], array(
					'target' => 'blank',
					'title' => $descr
				));
				$content .= '<br>';
			}else{
				if(empty($descr)) $descr = $title;
				$title = ucwords($row['ProjectLinkType']['name']) . ': <br>' . $descr;
				$content .= $title . '<br>';
			}
		}
	}
	return $content;
}


function dh_project_institutions($obj = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData('ProjectsInstitution', $record);
	$content = null;
	if(!empty($modelData)) {
		foreach($modelData as $k => $row) {
			$content .= $row['Institution']['name'] . ' (' . $row['InstitutionRole']['name'] . '), <br>';
		}
	}
	return $content;
}


function dh_project_people($obj = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData('ProjectsPerson', $record);
	$content = null;
	if(!empty($modelData)) {
		foreach($modelData as $k => $row) {
			$fname = (!empty($row['Person']['first_name'])) ? $row['Person']['first_name'] : $row['Person']['initials'];
			$name = (!empty($row['Person']['academic_grade'])) ? $row['Person']['academic_grade'] . ' ' : '';
			$name .= (!empty($fname)) ? $fname . ' ' : '';
			$name .= $row['Person']['name'];
			$content .= $name . ' (' . $row['PersonProjectRole']['name'] . '), <br>';
		}
	}
	return $content;
}


function dh_project_identifiers($obj = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData('ProjectExternalIdentifier', $record);
	$content = null;
	if(!empty($modelData)) {
		foreach($modelData as $k => $row) {
			$identifier = (!$row['ExternalIdentifierType']['schema_remove_prefix'])
				? $row['identifier']
				: str_replace($row['ExternalIdentifierType']['prefix'], '', $row['identifier']);
			$href = $row['ExternalIdentifierType']['schema'] . $identifier;
			$content .= $row['ExternalIdentifierType']['name'] . ': ';
			$content .= $obj->Html->link($row['identifier'], $href, array('target' => 'blank')) . '<br>';
		}
	}
	return $content;
}


function dh_tags($obj = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData($fieldDef['modelName'], $record);
	$content = null;
	if(!empty($modelData) and is_array($modelData)) {
		foreach($modelData as $k => $row) {
			if(!empty($row['name'])) $content .= $row['name'] . '<br>';
		}
	}
	return $content;
}


function getModelData($modelName = null, $data = array()) {
	$modelData = $data;
	if(isset($data[$modelName])) $modelData = $data[$modelName];
	return $modelData;
}
?>