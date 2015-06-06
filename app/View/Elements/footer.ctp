<p>
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
	
	echo $this->Html->link('About', array(
		'plugin' => null,
		'controller' => 'pages',
		'action' => 'display',
		'about'
	));
	?>
</p>
<p>
	Copyright 2015 
	<?php echo $this->Html->link('Hendrik Schmeer', 'mailto:hendrik.schmeer@yahoo.de'); ?>
	on behalf of <?php echo $this->Html->link('Dariah-EU', 'https://dariah.eu/', array('target' => '_blank')); ?>,
	<?php echo $this->Html->link('DARIAH-VCC2', 'https://dariah.eu/activities/general-vcc-meetings/2nd-general-vcc-meeting.html', array('target' => '_blank')); ?>, 
	<?php echo $this->Html->link('Dariah-DE', 'https://de.dariah.eu/', array('target' => '_blank')); ?>.
</p>
<p>
	Credits to: <br>
	<?php echo $this->Html->link('Royal Netherlands Academy of Arts and Sciences', 'http://www.knaw.nl/', array('target' => '_blank')); ?>, <br>
	<?php echo $this->Html->link('the eHumanities Group', 'https://www.knaw.nl/en/institutes/e-humanities-group', array('target' => '_blank')); ?>, <br>
	<?php echo $this->Html->link('Data Archiving and Networked Services', 'http://www.dans.knaw.nl/', array('target' => '_blank')); ?>, <br>
	<?php echo $this->Html->link('Erasmus University Rotterdam', 'http://www.eur.nl/', array('target' => '_blank')); ?>, <br>
	<?php echo $this->Html->link('University of Cologne', 'http://www.uni-koeln.de/', array('target' => '_blank')); ?>, <br>
	<?php echo $this->Html->link('PIREH', 'http://www.univ-paris1.fr/axe-de-recherche/pole-informatique-de-recherche-et-denseignement-en-histoire/', array('target' => '_blank')); ?> /
	<?php echo $this->Html->link('University Paris 1', 'https://www.univ-paris1.fr/', array('target' => '_blank')); ?>
</p>
