<?php
namespace Request\Model\Entity;

use Cake\ORM\Entity;

/**
 * RequestsResource Entity.
 *
 * @property int $id
 * @property int $request_id
 * @property \Request\Model\Entity\Request $request
 * @property int $resource_id
 * @property \Request\Model\Entity\Resource $resource
 * @property int $service_id
 * @property \Request\Model\Entity\Service $service
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property int $is_active
 */
class RequestsResource extends Entity
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

    protected $_hidden = ['created', 'is_active', 'modified', 'id'];
}
