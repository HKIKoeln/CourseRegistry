<?php
App::uses('TadirahObject', 'Model');

/**
 * TadirahObject Test Case
 *
 */
class TadirahObjectTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tadirah_object',
		'app.course',
		'app.country',
		'app.city',
		'app.university',
		'app.parent_type',
		'app.type',
		'app.language',
		'app.courses_tadirah_object'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TadirahObject = ClassRegistry::init('TadirahObject');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TadirahObject);

		parent::tearDown();
	}

}
