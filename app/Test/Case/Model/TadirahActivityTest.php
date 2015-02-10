<?php
App::uses('TadirahActivity', 'Model');

/**
 * TadirahActivity Test Case
 *
 */
class TadirahActivityTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tadirah_activity',
		'app.course',
		'app.country',
		'app.city',
		'app.university',
		'app.parent_type',
		'app.type',
		'app.language',
		'app.courses_tadirah_activity',
		'app.tadirah_technique',
		'app.tadirah_activities_tadirah_technique'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TadirahActivity = ClassRegistry::init('TadirahActivity');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TadirahActivity);

		parent::tearDown();
	}

}
