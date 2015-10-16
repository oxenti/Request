<?php
namespace Request\Model\Entity;

use Cake\ORM\Entity;

/**
 * Requeststatus Entity.
 *
 * @property int $id
 * @property string $status
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $is_active
 * @property \Request\Model\Entity\Requesthistoric[] $requesthistorics
 * @property \Request\Model\Entity\Request[] $requests
 */
class Requeststatus extends Entity
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
    ];

    protected $_hidden = ['created', 'is_active', 'modified'];
}
