<?php
App::uses('University', 'Model');

/**
 * University Test Case
 *
 */
class UniversityTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.university',
		'app.city',
		'app.country',
		'app.course'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->University = ClassRegistry::init('University');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->University);

		parent::tearDown();
	}

}
