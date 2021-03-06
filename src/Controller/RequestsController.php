<?php
namespace Request\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\Time;
use App\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Request\Controller\AppController;

/**
 * Requests Controller
 *
 * @property \Request\Model\Table\RequestsTable $Requests
 */
class RequestsController extends AppController
{

    /**
     * isAUtorized method
     *
     * @return void
     */
    public function isAuthorized($user)
    {
        $this->Auth->config('unauthorizedRedirect', false);
        $params = $this->request->params;
        if ($this->request->action === 'view' && $this->Requests->viewAuthorized($user, $this->request->id)) {
            return true;
        } elseif ($this->request->action === 'add' && $this->Requests->addAuthorized($user, $this->request->id)) {
            return true;
        } elseif ($this->request->action === 'index' && $this->Requests->indexAuthorized($user, $params)) {
            return true;
        } elseif ($this->Requests->adminAuthorized($user) && $this->request->action !== 'accept' && $this->request->action !== 'reject' && $this->request->action !== 'add') {
            return true;
        } elseif ($this->request->action === 'delete' || $this->request->action === 'index') {
            return false;
        } elseif ($this->request->action === 'cancel' && $this->Requests->viewAuthorized($user, $params['pass'][0])) {
            return true;
        } elseif (($this->request->action === 'accept' || $this->request->action === 'reject') && isset($user[$this->Requests->getFieldTarget()]) && $this->Requests->viewAuthorized($user, $params['pass'][0])) {
            return true;
        } else {
            parent::isAuthorized($user);
        }
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->request->allowMethod(['get']);
        $finder = isset($this->request->query['complete'])? 'all' : 'list';
        $where = [];
        if (isset($this->request->params['owner_id']) || isset($this->request->params['target_id'])) {
            if (isset($this->request->params['owner_id'])) {
                $where = ['owner_id' => $this->request->params['owner_id']];
                $join = 'Target';
            } else {
                $where = ['target_id' => $this->request->params['target_id']];
                $join = 'Owner';
            }
        }
        if (isset($this->request->query['status'])) {
            $where['Requeststatus.id'] = $this->request->query['status'];
        }
        $this->paginate = [
            'finder' => $finder,
            'contain' => [
                'Resources',
                'Requeststatus',
                'Historics.Justifications',
                $join
            ],
            'conditions' => $where
        ];
        $this->set('requests', $this->paginate($this->Requests));
        $this->set('_serialize', ['requests']);
    }

    /**
     * View method
     *
     * @param string|null $id Request id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->request->allowMethod(['get']);
        $request = $this->Requests->get($id, [
            'contain' => ['Owner', 'Target', 'Requeststatus', 'Resources', 'Historics.Justifications']
        ]);
        $this->set('request', $request);
        $this->set('_serialize', ['request']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);
        if (!isset($this->request->data['service_id'])) {
            throw new BadRequestException('The Request could not be saved. Please, try again.');
        }
        $data = $this->request->data;
        $data['start_time'] = isset($data['start_time'])?new Time($data['start_time']):null;
        $data['end_time'] = isset($data['end_time'])?new Time($data['end_time']):null;
        $data['duration'] = date_diff($data['end_time'], $data['start_time'])->format('%d %H:%I:%S');
        $data['owner_id'] = $this->Auth->user($this->Requests->getFieldOwner());
        foreach ($data['resources'] as $index => $topicId) {
            $data['requests_resources'][$index] = [
                    'service_id' => $data['service_id'],
                    'resource_id' => $topicId
            ];
        }
        unset($data['resources']);
        $data['requeststatus_id'] = 1;
        $request = $this->Requests->newEntity($data, [
            'accessibleFields' => [
                'owner_id' => true,
                'target_id' => true
            ]
        ]);
        // debug($request);
        // die();
        if (!$this->Requests->save($request)) {
            throw new BadRequestException('The Request could not be saved. Please, try again.');
        }
        $message = 'The request has been saved.';
        $this->set([
           'success' => true,
           'message' => $message,
           'id' => $request->id,
           '_serialize' => ['success', 'message', 'id'],
        ]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Request id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    // public function edit($id = null)
    // {
    //     $this->request->allowMethod(['put']);
    //     $request = $this->Requests->get($id, [
    //         'contain' => []
    //     ]);
    //     $data = $this->Requests->editFormatData($id, $this->request->data, $this->Auth->user());
    //     $request = $this->Requests->patchEntity($request, $data);
    //     if ($this->Requests->save($request)) {
    //         $message = 'The Request has been saved.';
    //         $this->set([
    //            'success' => true,
    //            'message' => $message,
    //            'id' => $request->id,
    //            '_serialize' => ['success', 'message', 'id'],
    //         ]);
    //     } else {
    //         throw new badRequestException('The Request could not be saved. Please, try again.');
    //     }
    // }

    /**
     * Delete method
     *
     * @param string|null $id Request id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $request = $this->Requests->get($id);
        if ($this->Requests->delete($request)) {
            $message = 'The user has been saved.';
            $this->set([
               'message' => $message,
               '_serialize' => ['message']
            ]);
        } else {
            throw new NotFoundException('The user could not be saved. Please, try again.');
        }
    }

    /**
     * cancel method
     *
     * @param string|null $id Request id.
     * @throws \Cake\Network\Exception\badRequestException When record not found.
     */
    public function cancel($id = null)
    {
        $this->request->allowMethod(['post', 'patch']);
        $statusCancel = $this->Requests->getStatus($this->Auth->user(), $id, 'cancel');
        if ($statusCancel) {
            $request = $this->Requests->get($id);
            $request->requeststatus_id = $statusCancel;
            if (!$this->Requests->save($request)) {
                throw new badRequestException('The Request not canceled.');
            }
            $message = 'Request cancel with sucess.';
            $this->set([
               'message' => $message,
               '_serialize' => ['message']
            ]);
        } else {
            throw new badRequestException('The Request not canceled.');
        }
    }

    /**
     * cancel method
     *
     * @param string|null $id Request id.
     * @throws \Cake\Network\Exception\badRequestException When record not found.
     */
    public function accept($id = null)
    {
        $this->request->allowMethod(['patch', 'get']);
        $statusAccept = $this->Requests->getStatus($this->Auth->user(), $id, 'accept');
        if ($statusAccept) {
            $request = $this->Requests->get($id);
            $request->requeststatus_id = $statusAccept;
            if ($this->Requests->save($request)) {
                $message = 'Request Accept with sucess.';
                $this->set([
                   'message' => $message,
                   '_serialize' => ['message']
                ]);
            } else {
                throw new badRequestException('The Request not change status. Please, try again.');
            }
        } else {
            throw new badRequestException('The Request not change status. Please, try again.');
        }
    }

    /**
     * cancel method
     *
     * @param string|null $id Request id.
     * @throws \Cake\Network\Exception\badRequestException When record not found.
     */
    public function reject($id = null)
    {
        $this->request->allowMethod(['patch','post']);
        $statusReject = $this->Requests->getStatus($this->Auth->user(), $id, 'reject');
        if ($statusReject) {
            $request = $this->Requests->get($id);
            $request = $this->Requests->patchEntity($request, $this->request->data);
            $request->requeststatus_id = $statusReject;
            if ($this->Requests->save($request)) {
                $message = 'Request Reject with sucess.';
                $this->set([
                   'message' => $message,
                   '_serialize' => ['message']
                ]);
            } else {
                throw new BadRequestException('The Request not change status. Please, try again.');
            }
        } else {
            throw new badRequestException('The Request not change status. Please, try again.');
        }
    }
}
