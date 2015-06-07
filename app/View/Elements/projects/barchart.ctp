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
<p class="caption">New projects per year</p>
<div class="barchart">
	<?php
	$ruleSet = null;
	foreach($chartData['years'] as $year => $count) {
		$style = 'height:' . $chartData['unitY'] * $count . 'px;';
		echo '<div class="bar" style="' . $style . '">';
			echo '<span class="count">' . $count . '</span>';
		echo '</div>';
		if(empty($year)) $year = 'NULL';
		$ruleSet .= '<div class="bar"><span class="year">' . $year . '</span></div>';
	}
	?>
</div>
<div class="barchart-x-rule">
	<?php echo $ruleSet; ?>
</div>