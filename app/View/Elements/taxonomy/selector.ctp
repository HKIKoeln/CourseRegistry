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
	function getOpts($modelName, $habtmModel, $record, $request, $level = null) {
		if(!empty($level)) $level = ' ' . $level;
		$crossModel = Inflector::pluralize($modelName).$habtmModel;
		$opts = array(
			'type' => 'checkbox',
			'value' => $record[$habtmModel]['id'],
			'label' => array('text' => $record[$habtmModel]['name'], 'title' => $record[$habtmModel]['description']),
			'name' => "data[$habtmModel][$habtmModel][]",
			'div' => array('class' => "checkbox$level"),
			'hiddenField' => false,
			//'datapath' => "$HabtmModel.$CrossTableModel.$habtm_model_id",
			// the js code is aware of the crossTable by the datarelation and starts iteration on the existing records
			'datapath' => $habtmModel.'.'.$crossModel.'.'.Inflector::underscore($habtmModel).'_id',
			'datarelation' => $modelName.'.habtm.'.$habtmModel
		);
		if(!empty($request[$habtmModel][$habtmModel])) {
			if(in_array($record[$habtmModel]['id'], $request[$habtmModel][$habtmModel])) {
				$opts['checked'] = true;
				//$opts['data-cross-id'] = $crossModel . $record[$habtmModel][$crossModel]['id'];
			}
		}elseif(!empty($request[$habtmModel])) {
			foreach($request[$habtmModel] as $k => $entry) {
				if(!empty($entry['id']) AND $record[$habtmModel]['id'] == $entry['id']) {
					$opts['checked'] = true;
					$opts['data-cross-id'] = $crossModel . $entry[$crossModel]['id'];
					break;
				}
			}
		}
		return $opts;
	}
}

$classes = (!empty($errors) AND !empty($errors[$habtmModel])) ? ' error' : '';
$classes .= (!empty($dropdown)) ? ' dropdown_checklist' : '';
?>

<div class="input taxonomy select required<?php echo $classes; ?>">
	<label for="<?php echo $habtmModel . $habtmModel; ?>">
		<?php echo Inflector::humanize(Inflector::underscore(Inflector::pluralize($habtmModel))); ?>
	</label>
	<div class="wrapper">
		<?php
		if(!empty($dropdown)) {
			?>
			<div id="<?php echo $habtmModel . '_toggle'; ?>" class="checklist_toggle">
				<span class="display">-- none selected --</span>
				<span class="caret"> </span>
			</div>
			<?php
		}
		?>
		<div id="<?php echo $habtmModel . '_checklist'; ?>"
			class="checklist"
			<?php if(!empty($dropdown)) echo ' style="display:none;"'; ?>>
			<input id="<?php echo $habtmModel . $habtmModel; ?>"
				type="hidden" value=""
				name="data[<?php echo $habtmModel . '][' . $habtmModel; ?>]">
			
			<?php
			$varname = Inflector::variable(Inflector::pluralize($habtmModel));
			foreach($$varname as $pk => $pv) {
				$level = null;
				if(!empty($pv['children'])) $level = 'primary';
				$opts = getOpts($modelName, $habtmModel, $pv, $this->request->data, $level);
				echo $this->Form->input($habtmModel . '.' . $habtmModel . $pv[$habtmModel]['id'], $opts);
				
				if(!empty($pv['children'])) {
					foreach($pv['children'] as $sk => $sv) {
						$opts = getOpts($modelName, $habtmModel, $sv, $this->request->data, 'secondary');
						echo $this->Form->input($habtmModel . '.' . $habtmModel . $sv[$habtmModel]['id'], $opts);
						
						if(!empty($sv['children'])) {
							foreach($sv['children'] as $tk => $tv) {
								$opts = getOpts($modelName, $habtmModel, $tv, $this->request->data, 'tertiary');
								echo $this->Form->input($habtmModel . '.' . $habtmModel . $tv[$habtmModel]['id'], $opts);
							}
						}
					}
				}
			}
			?>
		</div>
	</div>
</div>

<?php
if(!empty($dropdown)) {
	?>
	<script>window.jQuery || document.write('<script src="\/\/code.jquery.com\/jquery-1.11.3.min.js"><\/script>')</script>
	<script>
	if(!dropdownChecklist) {
		var dropdownChecklist = 1;
		jQuery(document).ready(function() {
			var toggle = $('.checklist_toggle');
			var checklist = $('.checklist');
			toggle.each(function() {
				$(this).on('click', function() {
					$(this).next('.checklist').toggle();
				});
			});
			
			toggle.each(function(index) {
				var checklist = $(this).next('.checklist');
				dc_writeDisplay(this, checklist);
				var currentToggle = this;
				
				// rewrite the display on-change
				var inputlist = checklist.find('input[type=checkbox]');
				inputlist.each(function(key) {
					$(this).on('change', function() {
						dc_writeDisplay(currentToggle, checklist);
					});
				});
			});
		});
		
		// dc - namespace for dropdown-checklist
		function dc_writeDisplay(toggle, checklist) {
			var selected = checklist.find('input[type=checkbox]:checked');
			var values = [];
			selected.each(function(key) {
				values.push($(this).next('label').text());
			});
			var display = values.join(', ');
			if(!display) display = '-- none selected --';
			$(toggle).find('.display').text(display);
		}
	}
	</script>

	<?php
}
?>


