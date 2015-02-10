<h3>New Account Requests</h3>

<?php
if(empty($inactive)) {
	echo '<p>There are no new users to confirm.</p>';
	
}else{
	
	$fieldlist['email'] = array('label' => 'Email');
	if(Configure::read('Users.loginName') != 'email') {
		$fieldlist[Configure::read('Users.loginName')] = array('label' => Inflector::humanize(Configure::read('Users.loginName')));
	}
	
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
			
			foreach($inactive as $record) {
				echo '<tr>';
					
					echo '<td class="actions">';
						echo $this->Html->link('edit', '/users/profile/' . $record[$modelName]['id']);
						echo $this->Html->link('approve', '/users/approve/' . $record[$modelName]['id'], 
							array('confirm' => 'Please confirm to grant access to DH Course Registry for ' . $record[$modelName]['name'] . '.'));
						echo $this->Html->link('delete', '/users/delete/' . $record[$modelName]['id'], 
							array('confirm' => 'Please confirm to delete the account of ' . $record[$modelName]['name'] . '.'));
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