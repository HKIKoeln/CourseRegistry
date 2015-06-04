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

<div id="googleMap"></div>


<?php
// rendering the initializeMap js method based on the present courses - optionally fetching from cache

// to disable element cache, the key "cache" must not be present in the options array
$options = array();
$get_locations = false;
// locations will be (bool)false if empty because of set filters
if($this->action == 'index' AND !isset($locations)) {
	$options = array('cache' => true);
	$get_locations = true;
}
$this->Html->scriptBlock(
	$this->element('courses/initialize_map', array('get_locations' => $get_locations), $options),
	array('inline' => false)
);




// Please create new Google API key for JavaScript V3. Please Follow instructions under https://code.google.com/apis/console/?pli=1 and enter key in [ToDo]
$this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyBR4ndG2xpJKvCXdpHmvNG0w9lzAzkTFwM&sensor=false', array('inline' => false));
// as the google marker clusterer provokes "mixed content errors" while on https, their code has been adapted (images loading via https)
//$this->Html->script('https://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js', array('inline' => false));
$this->Html->script('markerclusterer.js', array('inline' => false));

$this->append('onload', 'var map = initializeMap();');
?>