<?php
namespace Request\Model\Table;

use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Request\Model\Entity\Request;
use Request\Model\Table\AppTable;
use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * Requests Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Requeststatus
 * @property \Cake\ORM\Association\HasMany $Requesthistorics
 * @property \Cake\ORM\Association\BelongsToMany $Resources
 */
class RequestsTable extends AppTable
{
    use SoftDeleteTrait;

    protected $config;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('requests');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Historic.Historic', [
                'class' => 'Request.Requesthistorics',
                'fields' => ['requeststatus_id', 'justification']
            ]);

        $this->config = configure::read('Requests_plugin');
        
        $this->belongsTo('Owner', [
            'foreignKey' => 'owner_id',
            'joinType' => 'INNER',
            'className' => $this->config['owner']['class']
        ]);
        $this->belongsTo('Target', [
            'foreignKey' => 'target_id',
            'joinType' => 'INNER',
            'className' => $this->config['target']['class']
        ]);
        $this->belongsTo('Requeststatus', [
            'foreignKey' => 'requeststatus_id',
            'joinType' => 'INNER',
            'className' => 'Request.Requeststatus'
        ]);
        if (isset($this->config['resources']['class'])) {
            $this->belongsToMany('Resources', [
                'foreignKey' => 'request_id',
                'targetForeignKey' => 'resource_id',
                'joinTable' => 'requests_resources',
                'className' => $this->config['resources']['class']
            ]);
        }

        $this->hasMany('RequestsResources', [
            'foreignKey' => 'request_id',
        ]);
        $this->_setAppRelations($this->config['relations']);
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
            //->add('duration', 'valid', ['rule' => 'time'])
            ->requirePresence('duration', 'create')
            ->notEmpty('duration');

        $validator
            ->add('start_time', 'valid', ['rule' => 'datetime'])
            ->requirePresence('start_time', 'create')
            ->notEmpty('start_time');

        $validator
            ->add('end_time', 'valid', ['rule' => 'datetime'])
            ->requirePresence('end_time', 'create')
            ->notEmpty('end_time');

        $validator
            ->add('is_active', 'valid', ['rule' => 'numeric']);

        $validator
            ->add('owner_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('owner_id', 'create')
            ->notEmpty('owner_id');

        $validator
            ->add('target_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('target_id', 'create')
            ->notEmpty('target_id');

        $validator
            ->add('requeststatus_id', 'valid', ['rule' => 'numeric'])
            ->notEmpty('requeststatus_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['owner_id'], 'Owner'));
        $rules->add($rules->existsIn(['target_id'], 'Target'));
        $rules->add($rules->existsIn(['requeststatus_id'], 'Requeststatus'));
        $rules->add($rules->existsIn(['owner_id'], 'Owner'));
        $rules->add($rules->existsIn(['target_id'], 'Target'));
        return $this->_setExtraBuildRules($rules, $this->config['rules']);
        return $rules;
    }

    /**
     * verify in permission view request
     *
     * @param array $user info user
     * @return bool
     */
    public function viewAuthorized($user, $requestId)
    {
        $fieldValidatorOwner = $this->config['owner']['userIndexValidator'];
        $fieldValidatorTarget = $this->config['target']['userIndexValidator'];
        $ownerId = isset($user[$fieldValidatorOwner])?$user[$fieldValidatorOwner]:0;
        $targetId = isset($user[$fieldValidatorTarget])?$user[$fieldValidatorTarget]:0;
        return $this->exists([
            'Or' => [
                'owner_id' => $ownerId,
                'target_id' => $targetId
            ],
            'id' => $requestId
        ]);
    }

    /**
     * return id do user for ownber
     *
     * @return id for owner
     */
    public function getFieldOwner()
    {
        return $this->config['owner']['userIndexValidator'];
    }

    /**
     * return id do user for target
     *
     * @return id for owner
     */
    public function getFieldTarget()
    {
        return $this->config['target']['userIndexValidator'];
    }

    /**
     * return id do user for Admin
     *
     * @return id for owner
     */
    public function getFieldAdmin()
    {
        return $this->config['admin']['userIndexValidator'];
    }

    /**
     * return id do user for Admin value
     *
     * @return id for owner
     */
    public function getFieldAdminValue()
    {
        return $this->config['admin']['value'];
    }

    /**
     *
     *
     * @return durationm request
     */
    public function getDuration($endTime, $startTime)
    {
        $duration = date_diff($endTime, $startTime);
        return $duration;
    }

    /**
     * verify in permission add request
     *
     * @param array $user info user
     * @return bool
     */
    public function indexAuthorized($user, $params)
    {
        if (isset($params['owner_id']) && isset($user[$this->getFieldOwner()]) && $params['owner_id'] == $user[$this->getFieldOwner()]) {
            return true;
        } elseif (isset($params['target_id']) && isset($user[$this->getFieldTarget()]) && $params['target_id'] == $user[$this->getFieldTarget()]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * verify in permission add request
     *
     * @param array $user info user
     * @return bool
     */
    public function addAuthorized($user)
    {
        $fieldValidatorOwner = $this->config['owner']['userIndexValidator'];
        if ((isset($user[$fieldValidatorOwner])?true:false) && $this->Owner->exists([ 'id' => $user[$fieldValidatorOwner]])) {
            return true;
        }
        return false;
    }

    /**
     * return id status cancel for user
     *
     * @param $user and $requestId $action info user an id the request
     * @return bool or id new status request
     */
    public function getStatus($user, $requestId, $action)
    {
        $statusAtual = $this->get($requestId, ['fields' => ['requeststatus_id']])->requeststatus_id;
        $perfil = $this->getPerfilUser($user);
        $statusChange = isset($this->config[$perfil]['statusChangeRules'][$statusAtual])?$this->config[$perfil]['statusChangeRules'][$statusAtual]:[];
        $status = isset($this->config[$perfil]['status'][$action])?$this->config[$perfil]['status'][$action]:null;
        if ($status && in_array($status, $statusChange)) {
            return $status;
        } else {
            return false;
        }
    }

    /**
     * return the perfil for request (owner or Target or Admin)
     *
     * @param array $user info the user
     * @return bool or string perfil
     */
    public function getPerfilUser($user)
    {
        if (isset($user[$this->getFieldOwner()])) {
            return 'owner';
        } elseif (isset($user[$this->getFieldTarget()])) {
            return 'target';
        } elseif (isset($user[$this->getFieldAdmin()]) && $user[$this->getFieldAdmin()] == $this->getFieldAdminValue()) {
            return 'admin';
        } else {
            return false;
        }
    }

    /**
     * verify in permission Index request
     *
     * @param array $user info user
     * @return bool
     */
    public function adminAuthorized($user)
    {
        if ($this->config['admin']['active']) {
            $fieldValidatorAdmin = $this->config['admin']['userIndexValidator'];
            $adminId = isset($user[$fieldValidatorAdmin])?$user[$fieldValidatorAdmin]:0;
            if ($adminId == $this->config['admin']['value']) {
                return true;
            }
        }
        return false;
    }
}
