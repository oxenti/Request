<?php
namespace Request\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use Request\Controller\RequesthistoricsController;

/**
 * Request\Controller\RequesthistoricsController Test Case
 */
class RequesthistoricsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.request.requesthistorics'
    ];

    /**
     * additionProvider method
     *
     * @return array
     */
    
    public function additionProvider()
    {
        $caso1 = [
            'type' => 'list',
            'limit' => 2,
            'page' => 1,
            'params' => '?limit=2&page=1&sort=name&finder=list'
        ];
        $caso2 = [
            'type' => 'all',
            'limit' => 4,
            'page' => 2,
            'params' => '?limit=4&page=2&sort=name&finder=all'
        ];
        $caso3 = [];
        $caso4 = [
            'type' => 'all',
            'limit' => 10,
            'page' => 20,
            'params' => '?limit=4&page=20&sort=name&finder=all'
        ];

        return [[$caso1, true], [$caso2, false], [$caso3, true], [$caso4, false]];
    }

    /**
     * Test index method
     * @dataProvider additionProvider
     * @return void
     */
    public function testIndex($caso, $responseStatus)
    {
        $this->Requesthistorics = TableRegistry::get('Request.Requesthistorics');
        if (empty($caso)) {
            $requests = $this->Requesthistorics->find()
                ->contain([
                    'Requeststatus',
                    'Justifications'
                ]);
            $caso['params'] = '';
        } else {
            $requests = $this->Requesthistorics->find($caso['type'])
                ->contain([
                    'Requeststatus',
                    'Requesthistorics.Justifications'
                ])
                ->limit($caso['limit'])
                ->page($caso['page'])
                ->toArray();
        }
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->get('/request/requests' . $caso['params']);
        if ($responseStatus) {
            $this->assertResponseOK();
            $response = json_decode($this->_response->body());
            $expected = json_encode($requests, JSON_PRETTY_PRINT);
            $this->assertEquals($expected, json_encode($response->requests, JSON_PRETTY_PRINT), 'message');
        } else {
            $this->assertResponseError();
        }
    }

    // /**
    //  * Test view method
    //  *
    //  * @return void
    //  */
    // public function testView()
    // {
    //     $this->markTestIncomplete('Not implemented yet.');
    // }

    // /**
    //  * Test add method
    //  *
    //  * @return void
    //  */
    // public function testAdd()
    // {
    //     $this->markTestIncomplete('Not implemented yet.');
    // }

    // /**
    //  * Test edit method
    //  *
    //  * @return void
    //  */
    // public function testEdit()
    // {
    //     $this->markTestIncomplete('Not implemented yet.');
    // }

    // /**
    //  * Test delete method
    //  *
    //  * @return void
    //  */
    // public function testDelete()
    // {
    //     $this->markTestIncomplete('Not implemented yet.');
    // }
}
