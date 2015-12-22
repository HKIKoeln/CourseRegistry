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
	if(empty($modelData)) return null;
	foreach($modelData as $k => $row) {
		if(!empty($row['url'])) {
			$pre = $desc = null;
			$text = ($row['title'] != $record['Project']['name']) ? $row['title'] : null;
			if(empty($text)) {
				$text = ucwords($row['ProjectLinkType']['name']);
			}else{
				$pre = ucwords($row['ProjectLinkType']['name']) . ': ';
			}
			$desc = (!empty($row['description'])) ? $row['description'] : null;
			
			$content .= $pre;
			$content .= $view->Html->link($text, $row['url'], array(
				'target' => 'blank',
				'title' => $desc
			));
			$content .= '<br>';
		}else{
			$content .= ucwords($row['ProjectLinkType']['name']) . ': <br>';
			if(!empty($row['title'])) $content .= $row['title'] . '<br>';
			if(!empty($row['description'])) $content .= $row['description'] . '<br>';
		}
	}
	return $content;
}


function dh_project_institutions($view = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData('ProjectsInstitution', $record);
	$content = $output = null;
	if(empty($modelData)) return null;
	// get ready to pull the identifiers:
	$fieldDef['modelName'] = 'InstitutionExternalIdentifier';
	foreach($modelData as $k => $row) {
		$content = $row['Institution']['name'];
		$location = dh_location($view, $row['Institution']);
		if(!empty($location))
			$content .= ',<br>' . $location;
		if(!empty($row['InstitutionRole']['name']))
			$content .= ' (' . $row['InstitutionRole']['name'] . ')';
		$identifiers = dh_identifiers($view, $row['Institution'], $fieldDef);
		if(!empty($identifiers))
			$content .= '<br><span style="padding-left: 10px; display: block;">' . $identifiers . '</span>';
		if(!empty($content)) $output .= '<p>' . $content . '</p>';
	}
	return $output;
}


function dh_location($view = null, $record = array(), $fieldDef = array()) {
	$output = array();
	if(empty($record)) return null;
	if(!empty($record['Country']['name']))
		$output[] = $record['Country']['name'];
	if(!empty($record['City']['name']))
		$output[] = $record['City']['name'];
	
	return (empty($output)) ? null : implode(', ', $output);
}


function dh_project_people($view = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData('ProjectsPerson', $record);
	$content = $output = null;
	if(empty($modelData)) return null;
	// get ready to pull the identifiers:
	$fieldDef['modelName'] = 'PersonExternalIdentifier';
	foreach($modelData as $k => $row) {
		$content = $firstname = $name = null;
		$firstname = (!empty($row['Person']['first_name'])) ? $row['Person']['first_name'] : $row['Person']['initials'];
		$name = (!empty($row['Person']['academic_grade'])) ? $row['Person']['academic_grade'] . ' ' : '';
		$name .= (!empty($firstname)) ? $firstname . ' ' : '';
		if(!empty($row['Person']['name'])) $name .= $row['Person']['name'];
		$content = $name;
		if(!empty($row['PersonProjectRole']['name']))
			$content .= ' (' . $row['PersonProjectRole']['name'] . ')';
		$identifiers = dh_identifiers($view, $row['Person'], $fieldDef);
		if(!empty($identifiers))
			$content .= '<br><span style="padding-left: 10px; display: block;">' . $identifiers . '</span>';
		if(!empty($content)) $output .= '<p>' . $content . '</p>';
	}
	return $output;
}


function dh_identifiers($view = null, $record = array(), $fieldDef = array()) {
	// fieldDef must declare the modelName to make this work
	$modelName = $fieldDef['modelName'];
	$modelData = getModelData($modelName, $record);
	$content = null;
	$output = array();
	if(!empty($modelData)) {
		foreach($modelData as $k => $row) {
			$identifier = (!$row['ExternalIdentifierType']['schema_remove_prefix'])
				? $row['identifier']
				: str_replace($row['ExternalIdentifierType']['prefix'], '', $row['identifier']);
			if(strpos($row['ExternalIdentifierType']['schema'], '##ID##') === false) {
				$href = $row['ExternalIdentifierType']['schema'] . $identifier;
			}else{
				$href = str_replace('##ID##', $identifier, $row['ExternalIdentifierType']['schema']);
			}
			$content = $row['ExternalIdentifierType']['name'] . ': ';
			$content .= $view->Html->link($row['identifier'], $href, array('target' => 'blank'));
			$output[] = $content;
		}
	}
	if($modelName == 'ProjectExternalIdentifier') {
		$href = '/projects/view/' . $record['Project']['id'];
		$content = 'DARIAH-PROJECT_ID: ';
		$content .= $view->Html->link($record['Project']['id'], $href);
		$output[] = $content;
	}
	return (empty($output)) ? null : implode('<br>', $output);
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


function dh_get_parent($view = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData('ParentProject', $record);
	if(empty($modelData)) return null;
	$href = '/projects/view/' . $modelData['id'];
	return $view->Html->link($modelData['name'], $href);
}


function dh_get_children($view = null, $record = array(), $fieldDef = array()) {
	$modelData = getModelData('ChildProject', $record);
	if(empty($modelData)) return null;
	$output = array();
	foreach($modelData as $key => $row) {
		$href = '/projects/view/' . $row['id'];
		$output[] = $view->Html->link($row['name'], $href);
	}
	return (empty($output)) ? null : implode('<br>', $output);
}


function getModelData($modelName = null, $data = array()) {
	$modelData = array();
	if(isset($data[$modelName])) $modelData = $data[$modelName];
	return $modelData;
}
?>