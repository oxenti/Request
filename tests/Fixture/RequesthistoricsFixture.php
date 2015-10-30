<?php
namespace Request\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RequesthistoricsFixture
 *
 */
class RequesthistoricsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'request_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'requeststatus_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'justification_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'is_active' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_requestshistory_requeststatus_idx' => ['type' => 'index', 'columns' => ['requeststatus_id'], 'length' => []],
            'fk_requestshistory_requests_idx' => ['type' => 'index', 'columns' => ['request_id'], 'length' => []],
            'fk_requestshistory_justifications_idx' => ['type' => 'index', 'columns' => ['justification_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_requestshistory_justifications' => ['type' => 'foreign', 'columns' => ['justification_id'], 'references' => ['justifications', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_requestshistory_requests' => ['type' => 'foreign', 'columns' => ['request_id'], 'references' => ['requests', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_requestshistory_requeststatus' => ['type' => 'foreign', 'columns' => ['requeststatus_id'], 'references' => ['requeststatus', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'request_id' => 1,
            'requeststatus_id' => 1,
            'justification_id' => 1,
            'created' => '2015-10-13 17:28:15',
            'modified' => '2015-10-13 17:28:15',
            'is_active' => 0
        ],
        [
            'id' => 2,
            'request_id' => 1,
            'requeststatus_id' => 2,
            'created' => '2015-10-13 17:28:15',
            'modified' => '2015-10-13 17:28:15',
            'is_active' => 1
        ],
    ];
}
