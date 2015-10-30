<?php
namespace Request\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Request\Model\Table\RequesthistoricsTable;

/**
 * Request\Model\Table\RequesthistoricsTable Test Case
 */
class RequesthistoricsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.request.requesthistorics',
        'plugin.request.requests',
        'plugin.request.requeststatus',
        'plugin.request.justifications'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Requesthistorics') ? [] : ['className' => 'Request\Model\Table\RequesthistoricsTable'];
        $this->Requesthistorics = TableRegistry::get('Requesthistorics', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Requesthistorics);

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

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $case1 = [
            'request_id' => 1,
            'requeststatus_id' => 1,
            'justification_id' => 1
        ];
        $result = $this->Requesthistorics->validator()->errors($case1);
        $this->assertEmpty($result, 'message');

        $case2 = [
            'request_id' => '',
            'requeststatus_id' => '',
            'justification_id' => '',
            'is_active' => ''
        ];
        $result = $this->Requesthistorics->validator()->errors($case2);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'request_id' => ['_empty' => 'This field cannot be left empty'],
            'requeststatus_id' => ['_empty' => 'This field cannot be left empty'],
            'justification_id' => ['_empty' => 'This field cannot be left empty'],
            'is_active' => ['_empty' => 'This field cannot be left empty']
        ];
        $this->assertEquals($expected, $result, 'message');

        $case3 = [];
        $result = $this->Requesthistorics->validator()->errors($case3);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'requeststatus_id' => ['_required' => 'This field is required']
        ];
        $this->assertEquals($expected, $result, 'message');

        $case4 = [
            'id' => 'gyfyig',
            'request_id' => 'mjfsdhfghsgf',
            'requeststatus_id' => ',fsejgsjg',
            'justification_id' => 'l,gsdg',
            'is_active' => ',mfdejçj'
        ];
        $result = $this->Requesthistorics->validator()->errors($case4);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'id' => ['valid' => 'The provided value is invalid'],
            'request_id' => ['valid' => 'The provided value is invalid'],
            'requeststatus_id' => ['valid' => 'The provided value is invalid'],
            'justification_id' => ['valid' => 'The provided value is invalid'],
            'is_active' => ['valid' => 'The provided value is invalid']
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
            'request_id' => 1,
            'requeststatus_id' => 1,
            'justification_id' => 1
        ];
        $case1 = $this->Requesthistorics->newEntity($data);
        $result = $this->Requesthistorics->save($case1);
        $this->assertInstanceOf('Request\Model\Entity\Requesthistoric', $result, 'message');

        $data = [
            'request_id' => 44,
            'requeststatus_id' => 44,
            'justification_id' => 44
        ];
        $case2 = $this->Requesthistorics->newEntity($data);
        $result = $this->Requesthistorics->save($case2);
        $this->assertFalse($result, 'message');
        $expected = [
            'request_id' => ['_existsIn' => 'This value does not exist'],
            'requeststatus_id' => ['_existsIn' => 'This value does not exist'],
            'justification_id' => ['_existsIn' => 'This value does not exist']
        ];
        $this->assertEquals($expected, $case2->errors(), 'message');
    }
}
