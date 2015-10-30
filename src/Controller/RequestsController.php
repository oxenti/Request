<?php
namespace Request\Controller;

use Cake\I18n\Time;
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
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $finder = !isset($this->request->query['finder'])?'all': $this->request->query['finder'];
        $this->paginate = [
            'finder' => $finder,
            'contain' => [
                'Owner',
                'Target',
                'Resources',
                'Requeststatus',
                'Historics.Justifications'
            ]
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
        if (isset($this->Auth)) {
            //echo 'Com Auth';
        } else {
            //echo 'Sem auth';
        }
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data['start_time'] = new Time($data['start_time']);
            $data['end_time'] = new Time($data['end_time']);
            $request = $this->Requests->newEntity($data, [
                'accessibleFields' => [
                    'owner_id' => true,
                    'target_id' => true
                ]
            ]);
            if ($this->Requests->save($request)) {
                $message = 'The request has been saved.';
                //debug($request);
                $this->set([
                   'success' => true,
                   'message' => $message,
                   'id' => $request->id,
                   '_serialize' => ['success', 'message', 'id'],
                ]);
            } else {
                throw new NotFoundException('The Request could not be saved. Please, try again.');
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
            // debug($request->get('justification'));
            // die();
            if ($this->Requests->save($request)) {
                $message = 'The Request has been saved.';
                $this->set([
                   'success' => true,
                   'message' => $message,
                   'id' => $request->id,
                   '_serialize' => ['success', 'message', 'id'],
                ]);
            } else {
                throw new NotFoundException('The Request could not be saved. Please, try again.');
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
