<?php
namespace Request\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RequeststatusFixture
 *
 */
class RequeststatusFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'requeststatus';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'status' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'is_active' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
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
            'status' => 'requested',
            'created' => '2015-10-13 18:54:44',
            'modified' => '2015-10-13 18:54:44',
            'is_active' => 1
        ],
        [
            'id' => 2,
            'status' => 'Accepted',
            'created' => '2015-10-13 18:54:44',
            'modified' => '2015-10-13 18:54:44',
            'is_active' => 1
        ],
        [
            'id' => 3,
            'status' => 'Rejected',
            'created' => '2015-10-13 18:54:44',
            'modified' => '2015-10-13 18:54:44',
            'is_active' => 1
        ],
        [
            'id' => 4,
            'status' => 'Cancel for Owner',
            'created' => '2015-10-13 18:54:44',
            'modified' => '2015-10-13 18:54:44',
            'is_active' => 1
        ],
        [
            'id' => 5,
            'status' => 'Cancel for Target',
            'created' => '2015-10-13 18:54:44',
            'modified' => '2015-10-13 18:54:44',
            'is_active' => 1
        ],
        [
            'id' => 6,
            'status' => 'Cancel for Sistem',
            'created' => '2015-10-13 18:54:44',
            'modified' => '2015-10-13 18:54:44',
            'is_active' => 1
        ],
    ];
}
