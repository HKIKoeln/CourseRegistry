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
<?php $name = (!empty($data['name'])) ? $data['name'] : 'User'; ?>
Dear <?php echo $name; ?>!

We have noticed, that information URLs of one or more entries you maintain in 
the Digital Humanities Course Registry are not valid any more. 

Please have a look at the listed erroneous courses and update the information soon.
Log in before to find a handy edit link ("review") next to the linked course descriptions.
<?php
echo Router::url(array(
	'admin' => false,
	'plugin' => null,
	'controller' => 'users',
	'action' => 'login'
), $full = true);
echo "\n\n";

foreach($data as $id => $fields) {
	if($id == 'name') continue;
	
	echo "Course: ";
	echo Router::url(array(
		'admin' => false,
		'plugin' => null,
		'controller' => 'courses',
		'action' => 'index',
		'id' => $id
	), $full = true);
	echo "\n";
	foreach($fields as $field => $errors) {
		$field = ($field == 'url') ? 'Information URL' : $field;
		$field = ($field == 'guide_url') ? 'Curriculum URL' : $field;
		echo $field . ": \n";
		foreach($errors as $error) {
			echo "\t" . $error . "\n";
		}
		echo "\n";
	}
}
?>

Many thanks! 

