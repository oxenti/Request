<?php
namespace Request\Model\Table;

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

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

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
}
