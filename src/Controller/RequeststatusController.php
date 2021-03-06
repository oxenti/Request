<?php
namespace Request\Controller;

use Request\Controller\AppController;

/**
 * Requeststatus Controller
 *
 * @property \Request\Model\Table\RequeststatusTable $Requeststatus
 */
class RequeststatusController extends AppController
{

    /**
     * isAUtorized method
     *
     * @return void
     */
    public function isAuthorized($user)
    {
        if ($this->request->action === 'index' && $this->Requeststatus->indexAuthorized($user)) {
            return true;
        } elseif ($this->request->action === 'changeStatus') {
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
        $where = [];
        if (isset($this->request->params['request_id'])) {
            $where = ['id in' => $this->Requeststatus->getChangeStatus($this->request->params['request_id'], $this->Auth->user())];
        }
        $this->set('requeststatus', $this->Requeststatus->find('list')->where($where));
        $this->set('_serialize', ['requeststatus']);
    }

    /**
     * View method
     *
     * @param string|null $id Requeststatus id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    // public function view($id = null)
    // {
    //     $requeststatus = $this->Requeststatus->get($id, [
    //         'contain' => ['Requesthistorics', 'Requests']
    //     ]);
    //     $this->set('requeststatus', $requeststatus);
    //     $this->set('_serialize', ['requeststatus']);
    // }

    // /**
    //  * Add method
    //  *
    //  * @return void Redirects on successful add, renders view otherwise.
    //  */
    // public function add()
    // {
    //     $requeststatus = $this->Requeststatus->newEntity();
    //     if ($this->request->is('post')) {
    //         $requeststatus = $this->Requeststatus->patchEntity($requeststatus, $this->request->data);
    //         if ($this->Requeststatus->save($requeststatus)) {
    //             $this->Flash->success(__('The requeststatus has been saved.'));
    //             return $this->redirect(['action' => 'index']);
    //         } else {
    //             $this->Flash->error(__('The requeststatus could not be saved. Please, try again.'));
    //         }
    //     }
    //     $this->set(compact('requeststatus'));
    //     $this->set('_serialize', ['requeststatus']);
    // }

    // /**
    //  * Edit method
    //  *
    //  * @param string|null $id Requeststatus id.
    //  * @return void Redirects on successful edit, renders view otherwise.
    //  * @throws \Cake\Network\Exception\NotFoundException When record not found.
    //  */
    public function changeStatus($desc = null)
    {
        debug($desc);
        die();
        // $requeststatus = $this->Requeststatus->get($id, [
        //     'contain' => []
        // ]);
        // if ($this->request->is(['patch', 'post', 'put'])) {
        //     $requeststatus = $this->Requeststatus->patchEntity($requeststatus, $this->request->data);
        //     if ($this->Requeststatus->save($requeststatus)) {
        //         $this->Flash->success(__('The requeststatus has been saved.'));
        //         return $this->redirect(['action' => 'index']);
        //     } else {
        //         $this->Flash->error(__('The requeststatus could not be saved. Please, try again.'));
        //     }
        // }
        // $this->set(compact('requeststatus'));
        // $this->set('_serialize', ['requeststatus']);
    }

    // /**
    //  * Delete method
    //  *
    //  * @param string|null $id Requeststatus id.
    //  * @return void Redirects to index.
    //  * @throws \Cake\Network\Exception\NotFoundException When record not found.
    //  */
    // public function delete($id = null)
    // {
    //     $this->request->allowMethod(['post', 'delete']);
    //     $requeststatus = $this->Requeststatus->get($id);
    //     if ($this->Requeststatus->delete($requeststatus)) {
    //         $this->Flash->success(__('The requeststatus has been deleted.'));
    //     } else {
    //         $this->Flash->error(__('The requeststatus could not be deleted. Please, try again.'));
    //     }
    //     return $this->redirect(['action' => 'index']);
    // }
}
