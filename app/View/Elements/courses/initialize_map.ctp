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
if($get_locations) $locations = $this->requestAction('courses/map');

$markers = array();
// get the markers
if(empty($locations) AND !empty($courses)) $locations = $courses;
if(!empty($locations) AND is_array($locations)) {
	$current_loc = null;
	$sorted = array();
	$i = 0;
	foreach($locations as $k => $record) {
		$current_loc = null;
		if(!empty($record['Course']['lat']) AND !empty($record['Course']['lon']))
			$current_loc = (string)$record['Course']['lat'] . ',' . (string)$record['Course']['lon'];
		if($current_loc) {
			if(!isset($sorted[$current_loc])) $sorted[$current_loc] = array();
			$sorted[$current_loc][] = $record;
		}
	}
	foreach($sorted as $loc => $list) {
		if(count($list) > 1) {
			// generate list marker
			$title = 'Multiple Courses';
			$content = '<h1>Multiple Courses</h1>';
			$content .= '<p>' . $this->Html->link('Click here to see the results for only this location in the table.', array(
				'geolocation' => $loc
			)) . '</p><ul>';
			foreach($list as $j => $record) {
				if($j >= 5) {
					$content .= '<li>... more</li>';
					break;
				}
				$content .= '<li>';
				$content .= $this->Html->link('Details', array(
					'controller' => 'courses',
					'action' => 'index',
					'id' => $record['Course']['id']
				));
				$content .= ' - ' . $record['Course']['name'] . ', ' . $record['University']['name'];
				$content .= '</li>';
			}
			$content .= '</ul>';
		}else{
			// a single item marker
			$record = $list[0];
			$title = $record['Course']['name'];
			$content = '<h1>' . $title . '</h1>';
			$content .= '<p>' . $record['University']['name'] . ',</p>';
			$content .= '<p>Department: ' . $record['Course']['department'] . '.</p>';
			$content .= '<p>' . $this->Html->link('Details', array(
				'controller' => 'courses',
				'action' => 'index',
				'id' => $record['Course']['id']
			)) . '</p>';
		}
		$marker = array();
		$marker['title'] = str_replace('"', '\\"', str_replace('\\', '\\\\', $title));
		$marker['content'] = str_replace('"', '\\"', str_replace('\\', '\\\\', $content));
		$marker['coordinates'] = array('lat' => $record['Course']['lat'], 'lon' => $record['Course']['lon']);
		$markers[] = $marker;
	}
}
?>

function initializeMap() {
	var mapProp = {
		mapTypeId:google.maps.MapTypeId.ROADMAP,
		zoom:3,
		center: new google.maps.LatLng(49.553915, 10.189551)
	};
	var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
	var bounds = new google.maps.LatLngBounds();
	
	var locations = JSON.parse('<?php echo json_encode($markers, JSON_HEX_APOS); ?>');
	var markers = [];
	var infowindows = [];
	var i = 0;
	for(var loc in locations) {
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[loc].coordinates.lat, locations[loc].coordinates.lon),
			map: map,
			title: locations[loc].title
		});
		var infowindow = new google.maps.InfoWindow({content: locations[loc].content});
		markers[markers.length] = marker;
		infowindows[infowindows.length] = infowindow;
		google.maps.event.addListener(marker, "click", function(marker, i) {
			return function() {
				infowindows[i].open(map, marker);
			}
		}(marker, i));
		bounds.extend(marker.getPosition());
		i++;
	}
	var markerCluster = new MarkerClusterer(map, markers, {maxZoom: 5});
	if(markers.length > 0) {
		if (bounds.getNorthEast().equals(bounds.getSouthWest())) {
			var extendPoint = new google.maps.LatLng(bounds.getNorthEast().lat() + 0.05, bounds.getNorthEast().lng() + 0.05);
			bounds.extend(extendPoint);
		}
		map.fitBounds(bounds);
	}
}
