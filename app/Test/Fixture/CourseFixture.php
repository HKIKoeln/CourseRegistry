<?php
/**
 * CourseFixture
 *
 */
class CourseFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'country_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'city_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'university_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'department' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'parent_type_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'type_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'dh_type' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'language_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'access_requirements' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'enrollment_period' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'comment' => 'possibly to be dropped', 'charset' => 'utf8'),
		'url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'ects' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'contact_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'contact_mail' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'keywords' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'to be dropped', 'charset' => 'utf8'),
		'lon' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'lat' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'country_id' => array('column' => array('country_id', 'city_id', 'type_id'), 'unique' => 0),
			'university_id' => array('column' => 'university_id', 'unique' => 0),
			'lon' => array('column' => array('lon', 'lat'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'country_id' => 1,
			'city_id' => 1,
			'university_id' => 1,
			'department' => 'Lorem ipsum dolor sit amet',
			'parent_type_id' => 1,
			'type_id' => 1,
			'dh_type' => 1,
			'language_id' => 1,
			'access_requirements' => 'Lorem ipsum dolor sit amet',
			'enrollment_period' => 'Lorem ipsum dolor sit amet',
			'url' => 'Lorem ipsum dolor sit amet',
			'ects' => 1,
			'contact_name' => 'Lorem ipsum dolor sit amet',
			'contact_mail' => 'Lorem ipsum dolor sit amet',
			'keywords' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'lon' => 'Lorem ipsum dolor sit amet',
			'lat' => 'Lorem ipsum dolor sit amet'
		),
	);

}
