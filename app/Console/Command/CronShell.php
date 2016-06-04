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
class CronShell extends AppShell {
    
	public $tasks = array(
		'CheckUrls',
		'SendReminders'
	);
	
	public function main() {
        $this->out("Available tasks: \n\t CheckUrls [C]\n\t SendReminders[S]");
		$this->out("Please note: \nperforming these tasks will send out emails to recipients, \nif the application is not in debug-mode./nYou can enter an alternative debug-mail recipient.");
		$this->hr();
		$task = $this->in('Choose an action', array('C','S','Q'), 'Q');
		$to = $this->in('All Emails to (alternative debug-mail): ', null, 'recipients');
		if($to == 'recipients') $to = null;
		$emails = $this->in('Send Emails?', array('Y','N'), 'N');
		
		if(strtolower($emails) == 'y') $emails = true;
		elseif(strtolower($emails) == 'n') $emails = false;
		if(strtolower($task) == 'c') $this->CheckUrls->execute($emails, $to);
		elseif(strtolower($task) == 's') $this->SendReminders->execute($emails, $to);
    }
}


?>