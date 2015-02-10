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

$this->extend('/Layouts/base');

$this->start('header');
?>
<div id="header">
	<?php
	echo $this->Html->image('/img/DARIAH-EURGB-Klein.png', array(
		'alt' => 'DARIA-EU',
		'class' => 'left',
		'url' => '/',
		'width' => 202,
		'height' => 61
	));
	?>
	<h1><?php echo $this->Html->link('Digital Humanities Course Registry', '/'); ?></h1>
	
</div>
<?php
$this->end();

// pass content to parent view
echo $this->fetch('content');
?>