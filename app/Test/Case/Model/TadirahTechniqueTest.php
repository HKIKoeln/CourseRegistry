<?php
App::uses('TadirahTechnique', 'Model');

/**
 * TadirahTechnique Test Case
 *
 */
class TadirahTechniqueTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tadirah_technique',
		'app.course',
		'app.country',
		'app.city',
		'app.university',
		'app.parent_type',
		'app.type',
		'app.language',
		'app.courses_tadirah_technique',
		'app.tadirah_activity',
		'app.courses_tadirah_activity',
		'app.tadirah_activities_tadirah_technique'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TadirahTechnique = ClassRegistry::init('TadirahTechnique');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TadirahTechnique);

		parent::tearDown();
	}

}
