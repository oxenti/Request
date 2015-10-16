<?php
namespace Request\Controller;

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
                'Requesthistorics.Justifications'
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
            'contain' => ['Owner', 'Target', 'Requeststatus', 'Resources', 'Requesthistorics.Justifications']
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
        $request = $this->Requests->newEntity();
        if ($this->request->is('post')) {
            $request = $this->Requests->patchEntity($request, $this->request->data);
            if ($this->Requests->save($request)) {
                $message = 'The request has been saved.';
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
            $request = $this->Requests->patchEntity($request, $this->request->data);
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
