<?php
namespace Request\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Request\Model\Entity\Requesthistoric;

/**
 * Requesthistorics Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Requests
 * @property \Cake\ORM\Association\BelongsTo $Requeststatus
 * @property \Cake\ORM\Association\BelongsTo $Justifications
 */
class RequesthistoricsTable extends Table
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

        $this->table('requesthistorics');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Requests', [
            'foreignKey' => 'request_id',
            'joinType' => 'INNER',
            'className' => 'Request.Requests'
        ]);
        $this->belongsTo('Requeststatus', [
            'foreignKey' => 'requeststatus_id',
            'joinType' => 'INNER',
            'className' => 'Request.Requeststatus'
        ]);
        $this->belongsTo('Justifications', [
            'foreignKey' => 'justification_id',
            'className' => 'Request.Justifications'
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
            ->requirePresence('request_id', 'create')
            ->notEmpty('request_id');

        $validator
            ->add('requeststatus_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('requeststatus_id', 'create')
            ->notEmpty('requeststatus_id');

        $validator
            ->add('justification_id', 'valid', ['rule' => 'numeric'])
            ->notEmpty('justification_id');

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
        $rules->add($rules->existsIn(['requeststatus_id'], 'Requeststatus'));
        $rules->add($rules->existsIn(['justification_id'], 'Justifications'));
        return $rules;
    }
}
