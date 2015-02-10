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
$toggle = ($showDetails) ? ''
	: 'onclick="toggleRow(event, \'course-details-' . $k . '\');" onmouseover="siblingHover(this, \'next\');" onmouseout="siblingHover(this, \'next\')"';

$outdated = false;
if(	$record['Course']['updated'] < date('Y-m-d H:i:s', time() - Configure::read('App.CoursePublicWarnPeriod'))
OR	(!empty($edit) AND $record['Course']['updated'] < date('Y-m-d H:i:s', time() - Configure::read('App.CourseWarnPeriod')))
) $outdated = true;

$title = '';
if($outdated) {
	if(empty($classname)) $classname = 'outdated';
	else $classname .= ' outdated';
	$title = 'This record has not been revised  for a year or longer.';
	if(!empty($edit)) $title = ' title="Please update this record to avoid it being dropped from the Course Registry."';
}
?>
<tr <?php echo $toggle; echo $title; ?>
	class="<?php echo $classname; ?>"
	>
	<?php
	if(!empty($edit)) {
		echo '<td class="actions">';
			echo $this->Html->link('review', array(
				'controller' => 'courses',
				'action' => 'edit',
				$record['Course']['id']
			));
		echo '</td>';
	}
	
	$modelName = 'Course';
	foreach($fieldlist as $key => $fieldDef) {
		$expl = explode('.', $key);
		$fieldModelName = $modelName;
		$fieldname = $expl[0];
		if(isset($expl[1])) {
			$fieldModelName = $expl[0];
			$fieldname = $expl[1];
		}
		
		$value = (!empty($record[$fieldModelName][$fieldname])) ? $record[$fieldModelName][$fieldname] : ' - ';
		$classname = '';
		switch($key) {
			case 'Course.name':
				$classname = ' class="strong"';
				break;
			case 'Type.name':
				$value = $record['ParentType']['name'] . ': ' . $value;
				break;
			case 'Course.url':
				if($value != ' - ' AND !empty($value)) {
					$value = $this->Html->link('Link >>', $record[$fieldModelName][$fieldname], array(
						'target' => '_blank',
						'title' => 'external information link (new tab)'
					));
				}
				break;
			case 'Course.guide_url':
				if($value != ' - ' AND !empty($value)) {
					$value = $this->Html->link('Guide >>', $record[$fieldModelName][$fieldname], array(
						'target' => '_blank',
						'title' => 'external information link (new tab)'
					));
				}
				break;
			case 'Course.location':
				$value = $this->Html->image('/img/markerRedSmall.png', array(
					'url' => array(
						'controller' => 'courses',
						'action' => 'index',
						'id' => $record[$fieldModelName]['id']
					),
					'alt' => 'show on map',
					'title' => 'show on map',
					'width' => 16,
					'height' => 28,
					'style' => 'vertical-align: middle;'
				));
		}
		echo '<td' . $classname . '>' . $value . '</td>';
	}
	?>
</tr>