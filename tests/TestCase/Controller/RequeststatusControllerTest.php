<?php
namespace Request\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Request\Controller\RequeststatusController;

/**
 * Request\Controller\RequeststatusController Test Case
 */
class RequeststatusControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.request.requeststatus'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->Requeststatus = TableRegistry::get('Request.Requeststatus');
        $statusList = $this->Requeststatus->find('list');
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->get('/request/requeststatus');
        $this->assertResponseOK();
        $expected = json_encode(['requeststatus' => $statusList], JSON_PRETTY_PRINT);
        $this->assertEquals($expected, $this->_response->body(), 'message');
    }
}
