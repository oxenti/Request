<?php
namespace Request\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Request\Model\Entity\Requeststatus;

/**
 * Requeststatus Model
 *
 * @property \Cake\ORM\Association\HasMany $Requesthistorics
 * @property \Cake\ORM\Association\HasMany $Requests
 */
class RequeststatusTable extends Table
{

    protected $config = [];
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->config = $this->config = configure::read('Requests_plugin');
        // debug($this->config);
        // die();
        $this->table('requeststatus');
        $this->displayField('status');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Requesthistorics', [
            'foreignKey' => 'requeststatus_id',
            'className' => 'Request.Requesthistorics'
        ]);
        $this->hasMany('Requests', [
            'foreignKey' => 'requeststatus_id',
            'className' => 'Request.Requests'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->add('is_active', 'valid', ['rule' => 'numeric']);

        return $validator;
    }

    /**
     * verify in permission get status request
     *
     * @param array $user info user
     * @return bool
     */
    public function indexAuthorized($user)
    {
        $fieldTarget = $this->config['target']['userIndexValidator'];
        $fieldOwner = $this->config['owner']['userIndexValidator'];
        $fieldAdmin = $this->config['admin']['userIndexValidator'];
        $valueAdmin = $this->config['admin']['value'];
        if (isset($user[$fieldOwner]) || isset($user[$fieldTarget]) || (isset($user[$fieldAdmin]) && $user[$fieldAdmin] == $valueAdmin)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * verify in permission get status request
     *
     * @return array
     */
    public function getChangeStatus($requestId, $user)
    {
        $fieldTarget = $this->config['target']['userIndexValidator'];
        $fieldOwner = $this->config['owner']['userIndexValidator'];
        $fieldAdmin = $this->config['admin']['userIndexValidator'];
        $valueAdmin = $this->config['admin']['value'];
        $requeststatus = $this->Requests->get($requestId, ['fields' => ['requeststatus_id']]);
        if (isset($user[$fieldOwner])) {
            return isset($this->config['owner']['statusChangeRules'][$requeststatus->requeststatus_id])?$this->config['owner']['statusChangeRules'][$requeststatus->requeststatus_id]:[];
        } elseif (isset($user[$fieldTarget])) {
            return isset($this->config['target']['statusChangeRules'][$requeststatus->requeststatus_id])?$this->config['target']['statusChangeRules'][$requeststatus->requeststatus_id]:[];
        } elseif (isset($user[$fieldAdmin]) && $user[$fieldAdmin] == $valueAdmin) {
            return isset($this->config['admin']['statusChangeRules'][$requeststatus->requeststatus_id])?$this->config['admin']['statusChangeRules'][$requeststatus->requeststatus_id]:[];
        }
    }
}
