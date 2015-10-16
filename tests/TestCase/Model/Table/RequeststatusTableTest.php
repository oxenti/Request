<?php
namespace Request\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Request\Model\Table\RequeststatusTable;

/**
 * Request\Model\Table\RequeststatusTable Test Case
 */
class RequeststatusTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.request.requeststatus',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Requeststatus') ? [] : ['className' => 'Request\Model\Table\RequeststatusTable'];
        $this->Requeststatus = TableRegistry::get('Requeststatus', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Requeststatus);

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
            'status' => 'Lorem ipsum dolor sit amet',
        ];
        $result = $this->Requeststatus->validator()->errors($case1);
        $this->assertEmpty($result, 'message');

        $case2 = [
            'id' => '',
            'status' => '',
            'is_active' => ''
        ];
        $result = $this->Requeststatus->validator()->errors($case2);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'status' => ['_empty' => 'This field cannot be left empty'],
            'is_active' => ['_empty' => 'This field cannot be left empty'],
        ];
        $this->assertEquals($expected, $result, 'message');

        $case3 = [];
        $result = $this->Requeststatus->validator()->errors($case3);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'status' => ['_required' => 'This field is required'],
        ];
        $this->assertEquals($expected, $result, 'message');

        $case4 = [
            'id' => 'vdsvdsvsd',
            'status' => 'Lorem ipsum dolor sit amet',
            'is_active' => '\zcxsasvav'
        ];
        $result = $this->Requeststatus->validator()->errors($case4);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'id' => ['valid' => 'The provided value is invalid'],
            'is_active' => ['valid' => 'The provided value is invalid'],
        ];
        $this->assertEquals($expected, $result, 'message');
    }
}
