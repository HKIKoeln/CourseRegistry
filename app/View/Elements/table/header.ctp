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


echo '<tr>';
	
	if(!empty($edit)) echo '<th>Actions</th>';
	
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
		echo '<th>';
			if(!empty($this->request->params['paging'][$modelName])) {
				$named = array();
				if(!empty($this->request->params['named'])) $named = $this->request->params['named'];
				if(	!empty($named['sort']) AND $named['sort'] == $fieldModelName . '.' . $fieldname
				AND	!empty($named['direction']) AND strtolower($named['direction']) == 'desc'
				) {
					// build a link to reset sorting
					unset($named['sort']);
					unset($named['direction']);
					$url = array(
						'action' => $this->request->params['action'],
						'controller' => $this->request->params['controller']
					);
					$url = array_merge($url, $this->request->params['pass'], $named);
					echo $this->Html->link($fieldDef['label'], $url, array('class' => 'desc'));
					
				}else{
					echo $this->Paginator->sort($fieldModelName . '.' . $fieldname, $fieldDef['label']);
				}
			}else{
				echo $fieldDef['label'];
			}
		echo '</th>';
	}
echo '</tr>';
?>