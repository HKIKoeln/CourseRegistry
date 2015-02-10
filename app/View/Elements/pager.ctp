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

	if($this->Paginator->hasNext() OR $this->Paginator->hasPrev()) {
		// unset page - otherwise we would stay on the same page (paging variables are stored within key "paging" in params array)
		unset($this->request->params['named']['page']);
		
		// paginator last & first return an empty string if there is no prev or next!
		$text = $this->Paginator->first('|<', array('url' => $this->params['named'])) . ' ';
		if(!$this->Paginator->hasPrev()) {
			$text .= $this->Html->tag('span', '|<', array('class' => 'disabled'));
		}
		$text .= $this->Paginator->prev('<<', null, null, array('class' => 'prev disabled'));
		$text .= $this->Paginator->counter();
		$text .= $this->Paginator->next('>>', null, null, array('class' => 'next disabled'));
		$text .= $this->Paginator->last(' >|', array('url' => $this->params['named']));
		if(!$this->Paginator->hasNext()) {
			$text .= $this->Html->tag('span', '>|', array('class' => 'disabled'));
		}
		
		echo $this->Html->tag('div', $text, array('class' => 'paging'));
	}
	
	$sel_opts = array(5,10,20,40,80,160);
	$params = $this->Paginator->params();
	if(!empty($params['count']) AND $params['count'] > $sel_opts[0]) {
		if(empty($paging_form_count)) $paging_form_count = 1;
		echo $this->Form->create('Pager', array('class' => 'paging_limit', 'id' => 'paging_form' . $paging_form_count));
		echo $this->Form->input('limit', array(
			'label' => 'results per page',
			'onchange' => 'this.form.submit()',
			'options' => array_combine($sel_opts, $sel_opts),
			'default' => 10,
			'selected' => $params['limit']
		));
		$id = 'submit_paging_form' . $paging_form_count;
		echo $this->Form->end(array(
			'label' => 'apply',
			'div' => array('id' => $id)
		));
		
		$this->append('onload', 'document.getElementById("' . $id . '").style.display = "none";');
		$this->set('paging_form_count', ++$paging_form_count);
	}
	
?>

