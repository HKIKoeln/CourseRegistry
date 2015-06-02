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

<h3>New Account Requests</h3>
<?php
if(empty($unapproved)) {
	echo '<p>There are no new users to confirm.</p>';
}else{
	
	$fieldlist = array(
		'AppUser.name' => array('label' => 'Name'),
		'Institution.name' => array('label' => 'Institution'),
		'AppUser.institution' => array('label' => 'New Institution?'),
		'AppUser.authority' => array('label' => 'Proof'),
		'AppUser.email' => array('label' => 'Email'),
		'AppUser.telephone' => array('label' => 'Telephone')
	);

	
	echo '<div class="scroll_wrapper">';
		echo '<table>';
			echo '<tr>';
				
				echo '<th>Actions</th>';
				
				foreach($fieldlist as $key => $fieldDef) {
					if(!isset($fieldDef['label']) OR empty($fieldDef['label'])) {
						$fieldDef['label'] = Inflector::camelize($fieldname);
					}
					echo '<th>' . $fieldDef['label'] . '</th>';
				}
				
			echo '</tr>';
			
			foreach($unapproved as $record) {
				echo '<tr>';
					
					echo '<td class="actions">';
						echo $this->Html->link('edit', array(
							'controller' => 'users',
							'action' => 'profile',
							'plugin' => null,
							$record[$modelName]['id']
						));
						echo $this->Html->link('approve', array(
							'controller' => 'users',
							'action' => 'approve',
							'plugin' => null,
							$record[$modelName]['id']
						), array('confirm' => 'Please confirm to grant access to DH Course Registry for ' . $record[$modelName]['name'] . '.'));
						echo $this->Html->link('delete', array(
							'controller' => 'users',
							'action' => 'delete',
							'plugin' => null,
							$record[$modelName]['id']
						), array('confirm' => 'Please confirm to delete the account of ' . $record[$modelName]['name'] . '.'));
					echo '</td>';
					
					foreach($fieldlist as $key => $fieldDef) {
						$expl = explode('.', $key);
						$fieldModelName = $modelName;
						$fieldname = $expl[0];
						if(isset($expl[1])) {
							$fieldModelName = $expl[0];
							$fieldname = $expl[1];
						}
						
						$value = $record[$fieldModelName][$fieldname];
						$classname = '';
						if(empty($value)) $value = ' - ';
						elseif($key == 'AppUser.name') $classname = ' class="strong"';
						
						echo '<td' . $classname . '>' . $value . '</td>';
					}
				echo '</tr>';
			}
			?>
		</table>
	</div>
	<?php
}
?>