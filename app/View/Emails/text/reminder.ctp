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
<?php $name = (!empty($data[key($data)]['AppUser']['name'])) ? $data[key($data)]['AppUser']['name'] : 'User'; ?>
Dear <?php echo $name; ?>!

We found, that one or more entries you maintain in 
the Digital Humanities Course Registry has not been updated for a year or more. 

To prevent the registry from showing outdated information, 
please review the listed records. 
Please note, that you must submit the edit form, even if the information does not change.  
This will update the 'last-modification-date' of your record. 

Information older than <?php echo Configure::read('App.CourseExpirationPeriod') / (60*60*24*365); ?> years will be removed 
from the registry automatically. 

Log in before to find a handy edit link ("review") next to the linked course descriptions.
<?php
echo Router::url(array(
	'admin' => false,
	'plugin' => null,
	'controller' => 'users',
	'action' => 'login'
), $full = true);
echo "\n\n";

foreach($data as $id => $record) {
	echo "Course: " . $record['Course']['name'] . "\n";
	echo Router::url(array(
		'admin' => false,
		'plugin' => null,
		'controller' => 'courses',
		'action' => 'index',
		'id' => $id
	), $full = true);
	echo "\n";
}
?>

Many thanks! 

