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
 
if(!empty($fieldlist)) {
	echo '<dl>';
	foreach($fieldlist as $key => $fieldDef) {
		$expl = explode('.', $key);
		$fieldModelName = $modelName;
		$fieldname = $expl[0];
		if(isset($expl[1])) {
			$fieldModelName = $expl[0];
			$fieldname = $expl[1];
		}
		if(!isset($fieldDef['label']) OR empty($fieldDef['label'])) {
			$fieldDef['label'] = Inflector::camelize($fieldname);
		}
		echo '<dt>' . $fieldDef['label'] . '</dt>';
		$value = (!empty($record[$fieldModelName][$fieldname])) ? record[$fieldModelName][$fieldname] : ' - ';
		if(!empty($fieldDef['class'])) {
			if(is_array($fieldDef['class'])) {
				$fieldclass = ' class="' . implode(' ', $fieldDef['class']) . '"';
			}else{
				$fieldclass = ' class="' . $fieldDef['class'] . '"';
			}
		}
		if(!empty($fieldDef['display'])) {
			switch($fieldDef['display']) {
			case 'link':
				if($value != ' - ' AND !empty($value)) {
					$value = $this->Html->link('Link >>', $record[$fieldModelName][$fieldname], array(
						'target' => '_blank',
						'title' => 'external link (new tab)'
					));
				}
				break;
			}
		}
		echo '<dd' . $fieldclass . '>' . $value . '</dd>';
	}
	echo '</dl>';
}
?>