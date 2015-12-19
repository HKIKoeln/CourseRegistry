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
if(!function_exists('getOpts')) {
	function getOpts($habtmModel, $record, $request, $level = null) {
		if(!empty($level)) $level = ' ' . $level;
		$opts = array(
			'type' => 'checkbox',
			'value' => $record[$habtmModel]['id'],
			'label' => array('text' => $record[$habtmModel]['name'], 'title' => $record[$habtmModel]['description']),
			'name' => "data[$habtmModel][$habtmModel][]",
			'div' => array('class' => "checkbox$level"),
			'hiddenField' => false
		);
		if(!empty($request[$habtmModel][$habtmModel])) {
			if(in_array($record[$habtmModel]['id'], $request[$habtmModel][$habtmModel])) {
				$opts['checked'] = true;
			}
		}elseif(!empty($request[$habtmModel])) {
			foreach($request[$habtmModel] as $k => $activity) {
				if(!empty($activity['id']) AND $record[$habtmModel]['id'] == $activity['id']) {
					$opts['checked'] = true;
					break;
				}
			}
		}
		return $opts;
	}
}
?>

<?php $error = (!empty($errors) AND !empty($errors[$habtmModel])) ? ' error' : ''; ?>
<div class="input taxonomy select required<?php echo $error; ?>" >
	<label for="<?php echo $habtmModel . $habtmModel; ?>">
		<?php echo Inflector::humanize(Inflector::underscore(Inflector::pluralize($habtmModel))); ?>
	</label>
	<div><span id="<?php echo $habtmModel . '_toggle'; ?>" title="click to expand" class="checklist_toggle">Click to expand</span></div>
	<div id="<?php echo $habtmModel . '_checklist'; ?>" style="display:none;">
		<input id="<?php echo $habtmModel . $habtmModel; ?>"
			type="hidden" value=""
			name="data[<?php echo $habtmModel . '][' . $habtmModel; ?>]">
		
		<?php
		$varname = Inflector::variable(Inflector::pluralize($habtmModel));
		foreach($$varname as $pk => $pv) {
			$level = null;
			if(!empty($pv['children'])) $level = 'primary';
			$opts = getOpts($habtmModel, $pv, $this->request->data, $level);
			echo $this->Form->input($habtmModel . '.' . $habtmModel . $pv[$habtmModel]['id'], $opts);
			
			if(!empty($pv['children'])) {
				foreach($pv['children'] as $sk => $sv) {
					$opts = getOpts($habtmModel, $sv, $this->request->data, 'secondary');
					echo $this->Form->input($habtmModel . '.' . $habtmModel . $sv[$habtmModel]['id'], $opts);
					
					if(!empty($sv['children'])) {
						foreach($sv['children'] as $tk => $tv) {
							$opts = getOpts($habtmModel, $tv, $this->request->data, 'tertiary');
							echo $this->Form->input($habtmModel . '.' . $habtmModel . $tv[$habtmModel]['id'], $opts);
						}
					}
				}
			}
		}
		?>
	</div>
</div>


<?php $this->start('onload'); ?>
var <?php echo $habtmModel . '_checklist'; ?> = document.getElementById('<?php echo $habtmModel . '_checklist'; ?>');
var <?php echo $habtmModel . '_toggle'; ?> = document.getElementById('<?php echo $habtmModel . '_toggle'; ?>');
<?php echo $habtmModel . '_toggle'; ?>.onclick = function() {
	if(<?php echo $habtmModel . '_checklist'; ?>.style.display == 'block')
		<?php echo $habtmModel . '_checklist'; ?>.style.display = 'none';
	else
		<?php echo $habtmModel . '_checklist'; ?>.style.display = 'block';
}
<?php $this->end(); ?>