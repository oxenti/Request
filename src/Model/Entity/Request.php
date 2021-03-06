<?php
namespace Request\Model\Entity;

use Cake\ORM\Entity;

/**
 * Request Entity.
 *
 * @property int $id
 * @property int $owner_id
 * @property int $target_id
 * @property \Request\Model\Entity\User $user
 * @property \Cake\I18n\Time $duration
 * @property \Cake\I18n\Time $start_time
 * @property \Cake\I18n\Time $end_time
 * @property int $requeststatus_id
 * @property \Request\Model\Entity\Requeststatus $requeststatus
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $is_active
 * @property \Request\Model\Entity\Requesthistoric[] $requesthistorics
 * @property \Request\Model\Entity\Resource[] $resources
 */
class Request extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
        'owner_id' => false,
        'target_id' => false
    ];

    protected $_virtual = ['reason'];

    protected $_hidden = ['is_active', 'modified', 'historics', 'requeststatus_id'];


    
    /**
     * Default validation rules.
     *
     * @return \Request\Entity\Justification
     */
    protected function _getReason()
    {
        if (isset($this->_properties['Historics'][0]['justification'])) {
            return $this->_properties['Historics'][0]['justification']->justification;
        } else {
            return null;
        }
    }
}
