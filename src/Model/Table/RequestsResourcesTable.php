<?php
namespace Request\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Request\Model\Entity\RequestsResource;

/**
 * RequestsResources Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Requests
 * @property \Cake\ORM\Association\BelongsTo $Resouces
 * @property \Cake\ORM\Association\BelongsTo $Services
 */
class RequestsResourcesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('requests_resources');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Requests', [
            'foreignKey' => 'request_id',
            'joinType' => 'INNER',
            'className' => 'Request.Requests'
        ]);
        $this->belongsTo('Resources', [
            'foreignKey' => 'resource_id',
            'joinType' => 'INNER',
            'className' => 'Request.Resources'
        ]);
        $this->belongsTo('Services', [
            'foreignKey' => 'service_id',
            'className' => 'Request.Services'
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
            ->add('is_active', 'valid', ['rule' => 'numeric'])
            ->notEmpty('is_active');

        $validator
            ->add('request_id', 'valid', ['rule' => 'numeric'])
            ->notEmpty('request_id');

        $validator
            ->add('resource_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('resource_id', 'create')
            ->notEmpty('resource_id');

        $validator
            ->add('service_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('service_id', 'create')
            ->notEmpty('service_id');

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
        $rules->add($rules->existsIn(['request_id'], 'Requests'));
        $rules->add($rules->existsIn(['resource_id'], 'Resources'));
        $rules->add($rules->existsIn(['service_id'], 'Services'));
        $rules->add($rules->isUnique(['request_id', 'resource_id', 'service_id']));
        return $rules;
    }
}
