<?php
namespace Request\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Request\Model\Entity\Request;
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
class RequestsTable extends Table
{
    use SoftDeleteTrait;

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

        $this->belongsTo('Owner', [
            'foreignKey' => 'owner_id',
            'joinType' => 'INNER',
            'className' => 'Request.Users'
        ]);
        $this->belongsTo('Target', [
            'foreignKey' => 'target_id',
            'joinType' => 'INNER',
            'className' => 'Request.Users'
        ]);
        $this->belongsTo('Requeststatus', [
            'foreignKey' => 'requeststatus_id',
            'joinType' => 'INNER',
            'className' => 'Request.Requeststatus'
        ]);
        $this->belongsToMany('Resources', [
            'foreignKey' => 'request_id',
            'targetForeignKey' => 'resource_id',
            'joinTable' => 'requests_resources',
            'className' => 'Request.Resources'
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
            ->add('duration', 'valid', ['rule' => 'time'])
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
        return $rules;
    }
}