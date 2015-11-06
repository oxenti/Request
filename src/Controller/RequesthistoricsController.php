<?php
namespace Request\Controller;

use Request\Controller\AppController;

/**
 * Requesthistorics Controller
 *
 * @property \Request\Model\Table\RequesthistoricsTable $Requesthistorics
 */
class RequesthistoricsController extends AppController
{

    /**
     * isAUtorized method
     *
     * @return void
     */
    public function isAuthorized($user)
    {
        $requestId = isset($this->request->params['request_id'])?$this->request->params['request_id']:0;
        $this->Auth->config('unauthorizedRedirect', false);
        if ($this->request->action === 'index' && $this->Requesthistorics->Requests->viewAuthorized($user, $requestId)) {
            return true;
        } elseif ($this->Requesthistorics->Requests->adminAuthorized($user)) {
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
        $finder = !isset($this->request->query['finder'])?'all': $this->request->query['finder'];
        $this->paginate = [
            'finder' => $finder,
            'contain' => ['Requeststatus', 'Justifications']
        ];
        $this->set('requesthistorics', $this->paginate($this->Requesthistorics));
        $this->set('_serialize', ['requesthistorics']);
    }

    // /**
    //  * View method
    //  *
    //  * @param string|null $id Requesthistoric id.
    //  * @return void
    //  * @throws \Cake\Network\Exception\NotFoundException When record not found.
    //  */
    // public function view($id = null)
    // {
    //     $requesthistoric = $this->Requesthistorics->get($id, [
    //         'contain' => ['Requests', 'Requeststatus', 'Justifications']
    //     ]);
    //     $this->set('requesthistoric', $requesthistoric);
    //     $this->set('_serialize', ['requesthistoric']);
    // }

    // /**
    //  * Add method
    //  *
    //  * @return void Redirects on successful add, renders view otherwise.
    //  */
    // public function add()
    // {
    //     $requesthistoric = $this->Requesthistorics->newEntity();
    //     if ($this->request->is('post')) {
    //         $requesthistoric = $this->Requesthistorics->patchEntity($requesthistoric, $this->request->data);
    //         if ($this->Requesthistorics->save($requesthistoric)) {
    //             $this->Flash->success(__('The requesthistoric has been saved.'));
    //             return $this->redirect(['action' => 'index']);
    //         } else {
    //             $this->Flash->error(__('The requesthistoric could not be saved. Please, try again.'));
    //         }
    //     }
    //     $requests = $this->Requesthistorics->Requests->find('list', ['limit' => 200]);
    //     $requeststatus = $this->Requesthistorics->Requeststatus->find('list', ['limit' => 200]);
    //     $justifications = $this->Requesthistorics->Justifications->find('list', ['limit' => 200]);
    //     $this->set(compact('requesthistoric', 'requests', 'requeststatus', 'justifications'));
    //     $this->set('_serialize', ['requesthistoric']);
    // }

    // *
    //  * Edit method
    //  *
    //  * @param string|null $id Requesthistoric id.
    //  * @return void Redirects on successful edit, renders view otherwise.
    //  * @throws \Cake\Network\Exception\NotFoundException When record not found.
     
    // public function edit($id = null)
    // {
    //     $requesthistoric = $this->Requesthistorics->get($id, [
    //         'contain' => []
    //     ]);
    //     if ($this->request->is(['patch', 'post', 'put'])) {
    //         $requesthistoric = $this->Requesthistorics->patchEntity($requesthistoric, $this->request->data);
    //         if ($this->Requesthistorics->save($requesthistoric)) {
    //             $this->Flash->success(__('The requesthistoric has been saved.'));
    //             return $this->redirect(['action' => 'index']);
    //         } else {
    //             $this->Flash->error(__('The requesthistoric could not be saved. Please, try again.'));
    //         }
    //     }
    //     $requests = $this->Requesthistorics->Requests->find('list', ['limit' => 200]);
    //     $requeststatus = $this->Requesthistorics->Requeststatus->find('list', ['limit' => 200]);
    //     $justifications = $this->Requesthistorics->Justifications->find('list', ['limit' => 200]);
    //     $this->set(compact('requesthistoric', 'requests', 'requeststatus', 'justifications'));
    //     $this->set('_serialize', ['requesthistoric']);
    // }

    // /**
    //  * Delete method
    //  *
    //  * @param string|null $id Requesthistoric id.
    //  * @return void Redirects to index.
    //  * @throws \Cake\Network\Exception\NotFoundException When record not found.
    //  */
    // public function delete($id = null)
    // {
    //     $this->request->allowMethod(['post', 'delete']);
    //     $requesthistoric = $this->Requesthistorics->get($id);
    //     if ($this->Requesthistorics->delete($requesthistoric)) {
    //         $this->Flash->success(__('The requesthistoric has been deleted.'));
    //     } else {
    //         $this->Flash->error(__('The requesthistoric could not be deleted. Please, try again.'));
    //     }
    //     return $this->redirect(['action' => 'index']);
    // }
}
