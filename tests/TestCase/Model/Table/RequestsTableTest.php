<?php
namespace Request\Test\TestCase\Model\Table;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Request\Model\Table\RequestsTable;

/**
 * Request\Model\Table\RequestsTable Test Case
 */
class RequestsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.request.requests',
        'plugin.request.users',
        'plugin.request.Requeststatus'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Requests') ? [] : ['className' => 'Request\Model\Table\RequestsTable'];
        $this->Requests = TableRegistry::get('Requests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Requests);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    // public function testInitialize()
    // {
    //     $this->markTestIncomplete('Not implemented yet.');
    // }


    public function additionProvider()
    {
        $cases = [
            [
                'owner_id' => 1,
                'target_id' => 1,
                'duration' => '17:31:11',
                'start_time' => '2015-10-13 17:31:11',
                'end_time' => '2015-10-13 17:31:11',
                'requeststatus_id' => 1,
            ],
            [
                'id' => '',
                'owner_id' => '',
                'target_id' => '',
                'duration' => '',
                'start_time' => '',
                'end_time' => '',
                'requeststatus_id' => '',
                ],
            [
                'id' => 'wffqwffqw',
                'owner_id' => 'dsafasfasfaf',
                'target_id' => 'fwesfwefewfwff',
                'duration' => 'fqwfwqfwqfwqfqwf',
                'start_time' => 'qfqwfqwfwqfwqffq',
                'end_time' => 'qwfdqwfqfwqf',
                'requeststatus_id' => 'rehgerfhrehreh',
            ],
        ];
        return [[$cases]];
    }

    /**
     * Test validationDefault method
     * @dataProvider additionProvider
     * @return void
     */
    public function testValidationDefault($cases)
    {
        $result = $this->Requests->validator()->errors($cases[0]);
        $this->assertEmpty($result, 'message');

        $result = $this->Requests->validator()->errors($cases[1]);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'owner_id' => ['_empty' => 'This field cannot be left empty'],
            'target_id' => ['_empty' => 'This field cannot be left empty'],
            'duration' => ['_empty' => 'This field cannot be left empty'],
            'start_time' => ['_empty' => 'This field cannot be left empty'],
            'end_time' => ['_empty' => 'This field cannot be left empty'],
            'requeststatus_id' => ['_empty' => 'This field cannot be left empty']
        ];
        $this->assertEquals($expected, $result, 'message');

        $result = $this->Requests->validator()->errors([]);
        $this->assertNotEmpty($result, 'message');

        $expected = [
            'owner_id' => ['_required' => 'This field is required'],
            'target_id' => ['_required' => 'This field is required'],
            'duration' => ['_required' => 'This field is required'],
            'start_time' => ['_required' => 'This field is required'],
            'end_time' => ['_required' => 'This field is required'],
        ];
        $this->assertEquals($expected, $result, 'message');

        $result = $this->Requests->validator()->errors($cases[2]);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'id' => ['valid' => 'The provided value is invalid'],
            'owner_id' => ['valid' => 'The provided value is invalid'],
            'target_id' => ['valid' => 'The provided value is invalid'],
            'duration' => ['valid' => 'The provided value is invalid'],
            'start_time' => ['valid' => 'The provided value is invalid'],
            'end_time' => ['valid' => 'The provided value is invalid'],
            'requeststatus_id' => ['valid' => 'The provided value is invalid']
        ];
        $this->assertEquals($expected, $result, 'message');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $data = [
            'owner_id' => 1,
            'target_id' => 1,
            'duration' => '17:31:11',
            'start_time' => Time::now(),
            'end_time' => Time::now(),
            'requeststatus_id' => 1,
        ];

        $case1 = $this->Requests->newEntity($data, [
            'accessibleFields' => [
                'owner_id' => true,
                'target_id' => true
            ]
        ]);
        $result = $this->Requests->save($case1);
        $this->assertInstanceOf('Request\Model\Entity\Request', $result, 'message');
        $data['owner_id'] = 44;
        $data['target_id'] = 44;
        $data['requeststatus_id'] = 44;
        $case2 = $this->Requests->newEntity($data, [
            'accessibleFields' => [
                'owner_id' => true,
                'target_id' => true
            ]
        ]);
        $result = $this->Requests->save($case2);
        $this->assertFalse($result, 'message');
        $expected = [
            'owner_id' => ['_existsIn' => 'This value does not exist'],
            'target_id' => ['_existsIn' => 'This value does not exist'],
            'requeststatus_id' => ['_existsIn' => 'This value does not exist']
        ];
        $this->assertEquals($expected, $case2->errors(), 'message');
    }
}
