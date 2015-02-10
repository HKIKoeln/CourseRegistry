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
<?php
echo "Dear " . $user[$model]['name'] . ",\n";
echo "we have added you as a trusted user to the Digital Humanities Course Registry.\n";
echo "You are encouraged to enter courses and curicula of your university \n";
echo "or institute to our database.\n";

echo "To access your account, please set a password:\n";
echo "Click the link below within 24 hours,\n";
echo "thereafter you can still request the password by following the 'I forgot my password' link.\n";
echo "\n";
echo Router::url(array(
	'admin' => false,
	'plugin' => 'users',
	'controller' => 'users',
	'action' => 'reset_password',
	$user[$model]['password_token']
), $full = true);
echo "\n";
?>