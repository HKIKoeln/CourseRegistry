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
 


/** include like this:
* (Projects/index.ctp; Projects/view.ctp)
include_once(APPLIBS.'project_display_functions.php');
*/
// custom output functions, called from the generic definitionlist template!

function dh_project_links($view = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData('ProjectLink', $record);
	$content = null;
	if(!empty($modelData)) {
		foreach($modelData as $k => $row) {
			$title = (!empty($row['title'])) ? $row['title'] : null;
			$descr = (!empty($row['description'])) ? $row['description'] : null;
			if(!empty($row['url'])) {
				if($title == $record['Project']['name'] OR empty($title))
					$title = ucwords($row['ProjectLinkType']['name']);
				$content .= $view->Html->link($title, $row['url'], array(
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


function dh_project_institutions($view = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData('ProjectsInstitution', $record);
	$content = null;
	// get ready to pull the identifiers:
	$fieldDef['modelName'] = 'ProjectExternalIdentifier';
	if(!empty($modelData)) {
		foreach($modelData as $k => $row) {
			$content .= $row['Institution']['name'] . ' (' . $row['InstitutionRole']['name'] . '), <br>';
			//$content .= '<span style="padding-left: 10px;">' . dh_identifiers($view, $record, $fieldDef) . '</span>';
		}
	}
	return $content;
}


function dh_project_people($view = null, $record = array(), $fieldDef = array()) {
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


function dh_identifiers($view = null, $record = array(), $fieldDef = array()) {
	// fieldDef must declare the modelName to make this work
	$modelName = $fieldDef['modelName'];
	$modelData = getModelData($modelName, $record);
	$content = null;
	if(!empty($modelData)) {
		foreach($modelData as $k => $row) {
			$identifier = (!$row['ExternalIdentifierType']['schema_remove_prefix'])
				? $row['identifier']
				: str_replace($row['ExternalIdentifierType']['prefix'], '', $row['identifier']);
			$href = $row['ExternalIdentifierType']['schema'] . $identifier;
			$content .= $row['ExternalIdentifierType']['name'] . ': ';
			$content .= $view->Html->link($row['identifier'], $href, array('target' => 'blank')) . '<br>';
		}
	}
	if($modelName == 'ProjectExternalIdentifier') {
		$href = '/projects/view/' . $record['Project']['id'];
		$content .= 'DARIAH-PROJECT_ID: ';
		$content .= $view->Html->link($record['Project']['id'], $href);
	}
	return $content;
}


function dh_tags($view = null, $record = array(), $fieldDef = array()) {
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