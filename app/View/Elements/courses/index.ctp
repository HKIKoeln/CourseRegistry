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
$fieldlist = array(
	'Course.name' => array('label' => 'Course Name'),
	'CourseType.name' => array('label' => 'Coursetype'),
	'Institution.name' => array('label' => 'Institution'),
	'Course.department' => array('label' => 'Department'),
	'Course.url' => array('label' => 'Information'),
	'Course.guide_url' => array('label' => 'Curriculum'),
	'Course.location' => array('label' => 'Location')
);
$colspan = count($fieldlist);
if(!empty($edit)) $colspan++;
?>
	
<div class="scroll_wrapper">
	<table>
		<?php
		echo $this->element('courses/table/header', array('fieldlist' => $fieldlist));
		
		if(!empty($courses)) {
			$showDetails = (count($courses) === 1) ? true : false;
			foreach($courses as $k => $record) {
				$classname = ($k % 2 == 0) ? 'even' : 'odd';
				echo $this->element('courses/table/row', array(
					'k' => $k,
					'record' => $record,
					'fieldlist' => $fieldlist,
					'colspan' => $colspan,
					'classname' => $classname,
					'showDetails' => $showDetails
				));
				echo $this->element('courses/table/row_details', array(
					'k' => $k,
					'record' => $record,
					'colspan' => $colspan,
					'classname' => $classname,
					'showDetails' => $showDetails
				));
			}
		}
		?>
	</table>
	
	<?php
	if(empty($courses)) {
		echo '<br />';
		echo '<p>Sorry, no results for your query.</p>';
	}
	?>
</div>



<?php $this->Html->scriptStart(array('inline' => false)); ?>
function toggleRow(event, id) {
	if(event.target.tagName.toLowerCase() === 'a') return;
	var element = document.getElementById(id);
	if(element.style.display == 'table-row') element.style.display = 'none';
	else element.style.display = 'table-row';
}
function siblingHover(target, orientation) {
	var element = target.nextSibling;
	if(orientation == 'prev') element = target.previousElementSibling;
	if(orientation == 'next') element = target.nextElementSibling;
	if(hasClass(element, 'mouseover')) {
		removeClass(element, 'mouseover');
	}else{
		element.className += ' mouseover';
	}
	if(hasClass(target, 'mouseover')) {
		removeClass(target, 'mouseover');
	}else{
		target.className += ' mouseover';
	}
}
function hasClass(element, name) {
	return new RegExp('(\\\s|^)' + name + '(\\\s|$)').test(element.className);
}

function removeClass(element, name) {
	var reg = new RegExp('(\\\s|^)' + name + '(\\\s|$)');
	element.className = element.className.replace(reg, ' ');
}
<?php $this->Html->scriptEnd(); ?>