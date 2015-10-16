<?php
namespace Request\Test\TestCase\Controller;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Request\Controller\RequestsController;

/**
 * Request\Controller\RequestsController Test Case
 */
class RequestsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.request.requests',
        'plugin.request.requestsResources',
        'plugin.request.requeststatus',
        'plugin.request.requesthistorics',
        'plugin.request.justifications',
        'plugin.request.users',
        'plugin.request.resources'
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
        $this->Requests = TableRegistry::get('Request.Requests');
        if (empty($caso)) {
            $requests = $this->Requests->find()
                ->contain([
                    'Owner',
                    'Target',
                    'Resources',
                    'Requeststatus',
                    'Requesthistorics.Justifications'
                ]);
            $caso['params'] = '';
        } else {
            $requests = $this->Requests->find($caso['type'])
                ->contain([
                    'Owner',
                    'Target',
                    'Resources',
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

    /**
     * additionProvider method
     *
     * @return array
     */
    
    public function viewProvider()
    {
        $case1 = 1;
        $case2 = 44;

        return [[$case1, true], [$case2, false]];
    }

    /**
     * Test view method
     * @dataProvider viewProvider
     * @return void
     */
    public function testView($caso, $responseStatus)
    {
        $this->Requests = TableRegistry::get('Request.Requests');
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        if ($responseStatus) {
            $request = $this->Requests->get($caso, [
                'contain' => ['Owner', 'Target', 'Requeststatus', 'Resources', 'Requesthistorics.Justifications']
            ]);
        }
        $this->get('/request/requests/' . $caso);
        if ($responseStatus) {
            $this->assertResponseOK();
            $response = $this->_response->body();
            $expected = json_encode(['request' => $request], JSON_PRETTY_PRINT);
            $this->assertEquals($expected, $response, 'message');
        } else {
            $this->assertResponseError();
        }
    }

    /**
     * additionProvider method
     *
     * @return array
     */
    
    public function addProvider()
    {
        $case1 = [
            'owner_id' => 1,
            'target_id' => 1,
            'duration' => '11:31:11',
            'start_time' => Time::now(),
            'end_time' => Time::now(),
            'requeststatus_id' => 1,
        ];
        $case2 = [
            'owner_id' => 44,
            'target_id' => 44,
            'duration' => '11:31:11',
            'start_time' => Time::now(),
            'end_time' => Time::now(),
            'requeststatus_id' => 7,
        ];

        return [[$case1, true], [$case2, false]];
    }

    /**
     * Test add method
     * @dataProvider addProvider
     * @return void
     */
    public function testAdd($data, $responseStatus)
    {
        $this->Requests = TableRegistry::get('Request.Requests');
        $countInitial = $this->Requests->find()->count();
        $this->configRequest([
           'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/request/requests', $data);
        $countEnd = $this->Requests->find()->count();
        if ($responseStatus) {
            $this->assertResponseOK();
            $expected = $countInitial + 1;
            $response = json_decode($this->_response->body());
            $record = $this->Requests->get($response->id);
            $this->assertEquals($data['owner_id'], $record->owner_id, 'message');
            $this->assertEquals($data['target_id'], $record->target_id, 'message');
            $this->assertEquals(new Time($data['duration']), $record->duration, 'duration');
            $this->assertEquals($data['start_time'], $record->start_time, 'start_time');
            $this->assertEquals($data['end_time'], $record->end_time, 'end_time');
            $this->assertEquals($data['requeststatus_id'], $record->requeststatus_id, 'requeststatus_id');
        } else {
            $this->assertResponseError();
            $expected = $countInitial;
        }
        $this->assertEquals($expected, $countEnd, 'message');
    }

    /**
     * Test edit method
     * @dataProvider addProvider
     * @return void
     */
    public function testEdit($data, $responseStatus)
    {
        $id = 1;
        $this->Requests = TableRegistry::get('Request.Requests');
        $countInitial = $this->Requests->find()->count();
        $this->configRequest([
           'headers' => ['Accept' => 'application/json']
        ]);
        $this->put('/request/requests/' . $id, $data);
        $countEnd = $this->Requests->find()->count();
        $expected = $countInitial;
        if ($responseStatus) {
            $this->assertResponseOK();
            $response = json_decode($this->_response->body());
            $this->assertEquals($id, $response->id, 'message');
            $record = $this->Requests->get($response->id);
            $this->assertEquals($data['owner_id'], $record->owner_id, 'message');
            $this->assertEquals($data['target_id'], $record->target_id, 'message');
            $this->assertNotEquals(new Time($data['duration']), $record->duration, 'duration');
            $this->assertNotEquals($data['start_time'], $record->start_time, 'start_time');
            $this->assertNotEquals($data['end_time'], $record->end_time, 'end_time');
            $this->assertEquals($data['requeststatus_id'], $record->requeststatus_id, 'requeststatus_id');
        } else {
            $this->assertResponseError();
        }
        $this->assertEquals($expected, $countEnd, 'message');
    }

    public function deleteProvider()
    {
        return [[1, true], [44, false]];
    }

    /**
     * Test delete method
     * @dataProvider deleteProvider
     * @return void
     */
    public function testDelete($id, $responseStatus)
    {
        $this->Requests = TableRegistry::get('Request.Requests');
        $countInitial = $this->Requests->find()->count();
        $this->configRequest([
           'headers' => ['Accept' => 'application/json']
        ]);
        $result = $this->delete('/request/requests/' . $id);
        $countEnd = $this->Requests->find()->count();
        $request = $this->Requests->find('all', ['withDeleted'])
               ->where(['Requests.id' => $id])
               ->first();
        if ($responseStatus) {
            $this->assertResponseOk();
            $this->assertNotEmpty($request, 'message');
            $this->assertEquals(false, $request->is_active);
        } else {
            $this->assertResponseError();
            $this->assertEmpty($request, 'message');
        }
        // Check that the response was a 200
    }
}
