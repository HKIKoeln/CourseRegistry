<div class="actions">
	<?php
	echo $this->Html->link('Review this record', array(
		'action' => 'review',
		$record[$modelName]['id']
	));
	?>
</div>
<div class="top">
	<?php
	if(!empty($detailsFieldlist['top'])) {
		echo $this->element('definitionlist', array('fieldlist' => $detailsFieldlist['top']));
	}
	?>
</div>
<div class="left wide">
	<?php
	if(!empty($detailsFieldlist['left'])) {
		echo $this->element('definitionlist', array('fieldlist' => $detailsFieldlist['left']));
	}
	?>
</div>
<div class="left narrow">
	<?php
	if(!empty($detailsFieldlist['right'])) {
		echo $this->element('definitionlist', array('fieldlist' => $detailsFieldlist['right']));
	}
	?>
</div>