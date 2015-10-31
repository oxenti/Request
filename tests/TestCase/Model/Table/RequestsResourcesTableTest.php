<?php
namespace Request\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Request\Model\Table\RequestsResourcesTable;

/**
 * Request\Model\Table\RequestsResourcesTable Test Case
 */
class RequestsResourcesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.request.requests_resources',
        'plugin.request.requests',
        'services',
        'resources'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('RequestsResources') ? [] : ['className' => 'Request\Model\Table\RequestsResourcesTable'];
        $this->RequestsResources = TableRegistry::get('RequestsResources', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RequestsResources);

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
            'resource_id' => 1,
            'service_id' => 1
        ];
        $result = $this->RequestsResources->validator()->errors($case1);
        $this->assertEmpty($result, 'message');

        $case2 = [
            'request_id' => '',
            'resource_id' => '',
            'service_id' => '',
            'is_active' => ''
        ];
        $result = $this->RequestsResources->validator()->errors($case2);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'request_id' => ['_empty' => 'This field cannot be left empty'],
            'resource_id' => ['_empty' => 'This field cannot be left empty'],
            'service_id' => ['_empty' => 'This field cannot be left empty'],
            'is_active' => ['_empty' => 'This field cannot be left empty']
        ];
        $this->assertEquals($expected, $result, 'message');

        $case3 = [];
        $result = $this->RequestsResources->validator()->errors($case3);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'resource_id' => ['_required' => 'This field is required'],
            'service_id' => ['_required' => 'This field is required']
        ];
        $this->assertEquals($expected, $result, 'message');

        $case4 = [
            'id' => 'safsdgfdsgsd',
            'request_id' => 'csafvcsdgdsbg',
            'resource_id' => 'sfdsgsgsg',
            'service_id' => 'dsdggggdsg',
            'is_active' => 'fewgrgherreh'
        ];
        $result = $this->RequestsResources->validator()->errors($case4);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'id' => ['valid' => 'The provided value is invalid'],
            'request_id' => ['valid' => 'The provided value is invalid'],
            'resource_id' => ['valid' => 'The provided value is invalid'],
            'service_id' => ['valid' => 'The provided value is invalid'],
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
            'request_id' => 2,
            'resource_id' => 1,
            'service_id' => 1
        ];
        $case1 = $this->RequestsResources->newEntity($data);
        $result = $this->RequestsResources->save($case1);
        $this->assertInstanceOf('Request\Model\Entity\RequestsResource', $result, 'message');

        $case2 = $this->RequestsResources->newEntity($data);
        $result = $this->RequestsResources->save($case2);
        $this->assertFalse($result, 'message');
        $expected = ['request_id' => ['_isUnique' => 'This value is already in use']];
        $this->assertEquals($expected, $case2->errors(), 'message');

        $data = [
            'request_id' => 44,
            'resource_id' => 44,
            'service_id' => 44
        ];
        $case2 = $this->RequestsResources->newEntity($data);
        $result = $this->RequestsResources->save($case2);
        $this->assertFalse($result, 'message');
        $expected = [
            'request_id' => ['_existsIn' => 'This value does not exist'],
            'resource_id' => ['_existsIn' => 'This value does not exist'],
            'service_id' => ['_existsIn' => 'This value does not exist']
        ];
        $this->assertEquals($expected, $case2->errors(), 'message');
    }
}
