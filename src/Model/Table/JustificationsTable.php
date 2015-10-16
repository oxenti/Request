<?php
namespace Request\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Request\Model\Entity\Justification;

/**
 * Justifications Model
 *
 * @property \Cake\ORM\Association\HasMany $Requesthistorics
 */
class JustificationsTable extends Table
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

        $this->table('justifications');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Requesthistorics', [
            'foreignKey' => 'justification_id',
            'className' => 'Request.Requesthistorics'
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
            ->requirePresence('justification', 'create')
            ->notEmpty('justification');

        $validator
            ->add('is_active', 'valid', ['rule' => 'numeric']);

        return $validator;
    }
}
