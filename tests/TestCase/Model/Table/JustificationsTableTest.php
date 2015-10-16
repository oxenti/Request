<?php
namespace Request\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Request\Model\Table\JustificationsTable;

/**
 * Request\Model\Table\JustificationsTable Test Case
 */
class JustificationsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
       'plugin.request.justifications',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Justifications') ? [] : ['className' => 'Request\Model\Table\JustificationsTable'];
        $this->Justifications = TableRegistry::get('Justifications', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Justifications);

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
            'justification' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
        ];
        $result = $this->Justifications->validator()->errors($case1);
        $this->assertEmpty($result, 'message');

        $case2 = [
            'id' => '',
            'justification' => '',
            'is_active' => ''
        ];
        $result = $this->Justifications->validator()->errors($case2);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'justification' => ['_empty' => 'This field cannot be left empty'],
            'is_active' => ['_empty' => 'This field cannot be left empty']
        ];
        $this->assertEquals($expected, $result, 'message');

        $case3 = [];
        $result = $this->Justifications->validator()->errors($case3);
        $this->assertNotEmpty($result, 'message');
        $expected = ['justification' => ['_required' => 'This field is required']];
        $this->assertEquals($expected, $result, 'message');

        $case4 = [
            'id' => 'fedsgfwegg',
            'justification' => 'efsehfggh',
            'is_active' => 'fejswgfjçgj'
        ];
        $result = $this->Justifications->validator()->errors($case4);
        $this->assertNotEmpty($result, 'message');
        $expected = [
            'id' => ['valid' => 'The provided value is invalid'],
            'is_active' => ['valid' => 'The provided value is invalid']
        ];
        $this->assertEquals($expected, $result, 'message');
    }
}
