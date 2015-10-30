<?php
namespace Request\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Request\Model\Behavior\HistoricBehavior;

/**
 * Request\Model\Behavior\HistoricBehavior Test Case
 */
class HistoricBehaviorTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Historic = new HistoricBehavior();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Historic);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
