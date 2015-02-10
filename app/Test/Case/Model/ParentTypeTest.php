<?php
App::uses('ParentType', 'Model');

/**
 * ParentType Test Case
 *
 */
class ParentTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.parent_type',
		'app.type',
		'app.course'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ParentType = ClassRegistry::init('ParentType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ParentType);

		parent::tearDown();
	}

}
