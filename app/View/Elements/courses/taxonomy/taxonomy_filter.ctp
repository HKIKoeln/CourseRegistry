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
?>
<p class="taxonomy selection" id="activities_selection" onclick="toggleActivities();">
	<?php
	$selected = array('- all -');
	if(!empty($this->request->data['TadirahActivity']['TadirahActivity'])) {
		$selected = array();
		foreach($this->request->data['TadirahActivity']['TadirahActivity'] as $id) {
			$selected[] = $tadirahActivitiesList[$id];
		}
	}
	?>
	Activities: <span><?php echo implode(', ', $selected); ?></span>
</p>
<div class="taxonomy keywords" id="activities_keywords" style="display:none;">
	<?php
	echo $this->element('courses/ie_apologies');
	
	echo $this->element('courses/taxonomy/selector', array('habtmModel' => 'TadirahActivity'));
	
	echo $this->Form->button('Ok', array(
		'type' => 'submit'
	));
	echo $this->Form->button('Cancel', array(
		'onclick' => "toggleActivities();",
		'type' => 'button'
	));
	?>
</div>



<p class="taxonomy selection" id="techniques_selection" onclick="toggleTechniques();">
	<?php
	$selected = array('- all -');
	if(!empty($this->request->data['TadirahTechnique']['TadirahTechnique'])) {
		$selected = array();
		foreach($this->request->data['TadirahTechnique']['TadirahTechnique'] as $id) {
			$selected[] = $tadirahTechniquesList[$id];
		}
	}
	?>
	Techniques: <span><?php echo implode(', ', $selected); ?></span>
</p>
<div class="taxonomy keywords" id="techniques_keywords" style="display:none;">
	<?php
	echo $this->element('courses/ie_apologies');
	
	echo $this->element('courses/taxonomy/selector', array('habtmModel' => 'TadirahTechnique'));
	
	echo $this->Form->button('Ok', array(
		'onclick' => 'this.form.submit();'
	));
	echo $this->Form->button('Cancel', array(
		'onclick' => "toggleTechniques();",
		'type' => 'button'
	));
	?>
</div>



<p class="taxonomy selection" id="objects_selection" onclick="toggleObjects();">
	<?php
	$selected = array('- all -');
	if(!empty($this->request->data['TadirahObject']['TadirahObject'])) {
		$selected = array();
		foreach($this->request->data['TadirahObject']['TadirahObject'] as $id) {
			$selected[] = $tadirahObjectsList[$id];
		}
	}
	?>
	Objects: <span><?php echo implode(', ', $selected); ?></span>
</p>
<div class="taxonomy keywords" id="objects_keywords" style="display:none;">
	<?php
	echo $this->element('courses/ie_apologies');
	
	echo $this->element('courses/taxonomy/selector', array('habtmModel' => 'TadirahObject'));
	
	echo $this->Form->button('Ok', array(
		'onclick' => 'this.form.submit();'
	));
	echo $this->Form->button('Cancel', array(
		'onclick' => "toggleObjects();",
		'type' => 'button'
	));
	?>
</div>


<?php $this->Html->scriptStart(array('inline' => false)); ?>
function toggle(id) {
	var element = document.getElementById(id);
	if(element.style.display == 'block') element.style.display = 'none';
	else element.style.display = 'block';
}
function hide(id1, id2) {
	document.getElementById(id1).style.display = 'none';
	document.getElementById(id2).style.display = 'none';
}
function toggleObjects() {
	hide('activities_keywords', 'techniques_keywords');
	toggle('objects_keywords');
}
function toggleTechniques() {
	hide('activities_keywords', 'objects_keywords');
	toggle('techniques_keywords');
}
function toggleActivities() {
	hide('techniques_keywords', 'objects_keywords');
	toggle('activities_keywords');
}
<?php $this->Html->scriptEnd(); ?>