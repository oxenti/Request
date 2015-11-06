<?php
namespace Request\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RequestsFixture
 *
 */
class RequestsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'owner_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'target_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'duration' => ['type' => 'time', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'start_time' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'end_time' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'requeststatus_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '2', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'is_active' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_requests_requeststatus_idx' => ['type' => 'index', 'columns' => ['requeststatus_id'], 'length' => []],
            'fk_requests_users_idx' => ['type' => 'index', 'columns' => ['owner_id'], 'length' => []],
            'fk_requests_1_users_idx' => ['type' => 'index', 'columns' => ['target_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_requests_1_users' => ['type' => 'foreign', 'columns' => ['target_id'], 'references' => ['users', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_requests_requeststatus' => ['type' => 'foreign', 'columns' => ['requeststatus_id'], 'references' => ['requeststatus', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_requests_users' => ['type' => 'foreign', 'columns' => ['owner_id'], 'references' => ['users', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'owner_id' => 2,
            'target_id' => 1,
            'duration' => '17:31:11',
            'start_time' => '2015-10-13 17:31:11',
            'end_time' => '2015-10-13 17:31:11',
            'requeststatus_id' => 1,
            'created' => '2015-10-13 17:31:11',
            'modified' => '2015-10-13 17:31:11',
            'is_active' => 1
        ],
        [
            'id' => 2,
            'owner_id' => 1,
            'target_id' => 2,
            'duration' => '17:31:11',
            'start_time' => '2015-10-13 17:31:11',
            'end_time' => '2015-10-13 17:31:11',
            'requeststatus_id' => 1,
            'created' => '2015-10-13 17:31:11',
            'modified' => '2015-10-13 17:31:11',
            'is_active' => 1
        ],
    ];
}
