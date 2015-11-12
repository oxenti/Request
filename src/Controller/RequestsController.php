<?php
namespace Request\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Network\Exception\BadRequestException;
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
        } elseif ($this->request->action === 'edit' && $this->Requests->editAuthorized($user, $this->request->id)) {
            return true;
        } elseif ($this->request->action === 'index' && $this->Requests->indexAuthorized($user, $params)) {
            return true;
        } elseif ($this->Requests->adminAuthorized($user)) {
            return true;
        } elseif ($this->request->action === 'delete' || $this->request->action === 'index') {
            return false;
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
        $finder = !isset($this->request->query['finder'])?'all': $this->request->query['finder'];
        $where = [];
        if (isset($this->request->params['owner_id']) || isset($this->request->params['target_id'])) {
            $where = isset($this->request->params['owner_id'])?['owner_id' => $this->request->params['owner_id']]:['target_id' => $this->request->params['target_id']];
        }
        $this->paginate = [
            'finder' => $finder,
            'contain' => [
                'Resources',
                'Requeststatus',
                'Historics.Justifications'
            ]
        ];
        $this->set('requests', $this->paginate($this->Requests->find()->where($where)));
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
        $request = $this->Requests->get($id, [
            'contain' => [ 'Requeststatus', 'Resources', 'Historics.Justifications']
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
        if ($this->request->is('post')) {
            $data = $this->request->data;
            if (isset($data['start_time'])) {
                $data['start_time'] = new Time($data['start_time']);
            }
            if (isset($data['end_time'])) {
                $data['end_time'] = new Time($data['end_time']);
            }
            $data['owner'] = $this->Auth->user($this->Requests->getFieldOwner());
            $data['requeststatus_id'] = 1;
            $request = $this->Requests->newEntity($data, [
                'accessibleFields' => [
                    'owner_id' => true,
                    'target_id' => true
                ]
            ]);
            if ($this->Requests->save($request)) {
                $message = 'The request has been saved.';
                $this->set([
                   'success' => true,
                   'message' => $message,
                   'id' => $request->id,
                   '_serialize' => ['success', 'message', 'id'],
                ]);
            } else {
                debug($request);
                die();
                throw new BadRequestException('The Request could not be saved. Please, try again.');
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Request id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $request = $this->Requests->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            if (isset($data['justification'])) {
                $data['justification'] = ['justification' => $data['justification']];
            }
            $request = $this->Requests->patchEntity($request, $data);
            if ($this->Requests->save($request)) {
                $message = 'The Request has been saved.';
                $this->set([
                   'success' => true,
                   'message' => $message,
                   'id' => $request->id,
                   '_serialize' => ['success', 'message', 'id'],
                ]);
            } else {
                throw new badRequestException('The Request could not be saved. Please, try again.');
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Request id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
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
}
