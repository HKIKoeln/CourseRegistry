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
$filter = $this->Session->read('filter'); ?>
	<li class="notification">
		Courses not in sync with the Course-Registry (state ~2014).<br>
		Visit the <?php echo $this->Html->link('DARIAH Course-Registry.', 'https://dh-registry.de.dariah.eu/', array('target' => 'blank')); ?>
	</li>
	<li class="filter">Filters:</li>
	<?php
	$location_fixed = false;
	if(!empty($filter['Course.lon']) OR !empty($filter['Course.lat'])) $location_fixed = 'geolocation';
	if(!empty($filter['Course.id']) AND !is_array($filter['Course.id'])) $location_fixed = 'Course.id';
	?>
	
	<li class="filter">
		<?php
		echo $this->Form->create('Course', array('url' => array(
			'controller' => 'courses',
			'action' => 'index'
		)));
		
		$this->Form->inputDefaults(array(
			'empty' => ' - all - ',
			'required' => false,		// as the validation scheme in the model has this field mandatory, the formHelper sets this attribute to true, thus triggering HTML 5 browser-validation!
			'onchange' => 'this.form.submit()'
		));
		if(!$location_fixed) {
			echo $this->Form->input('country_id');
			echo $this->Form->input('city_id');
			echo $this->Form->input('institution_id');
		}
		echo $this->Form->input('course_parent_type_id', array(
			'label' => '1. Coursetype'
		));
		echo $this->Form->input('course_type_id', array(
			'label' => '2. Coursetype'
		));
		?>
		<p>TaDiRAH keywords</p>
		<noscript>
			<p class="note">Enable Javascript to make use of the taxonomy filter. </p>
		</noscript>
		<?php
		$this->Form->inputDefaults(array(
			'empty' => false,
			'required' => false,
			'onchange' => false
		));
		echo $this->element('taxonomy/taxonomy_filter', array('dropdownChecklist' => false));
		
		echo $this->Form->end(array(
			'label' => 'Show Results',
			'div' => array('id' => 'submit_filter')
		));
		?>
	</li>
	
	<?php
	if($location_fixed) 
		echo '<li class="filter">' . $this->Html->link('Fixed Location (remove)', array(
			'controller' => 'courses',
			'action' => 'reset',
			$location_fixed
		), array('title' => 'remove this filter')) . '</li>';
	
	if(!empty($filter))
		echo '<li class="filter">' . $this->Html->link('>> Reset all Filters', array(
			'controller' => 'courses',
			'action' => 'reset'
		)) . '</li>';
	?>

<?php $this->append('onload', 'document.getElementById("submit_filter").style.display = "none";'); ?>






