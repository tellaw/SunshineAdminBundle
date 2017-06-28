<?php

namespace Tellaw\SunshineAdminBundle\Event;

use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Evénement encapsulant une entité
 */
class EntityEvent extends GenericEvent
{
    /**
     * Entité
     *
     * @var Object
     */
    private $entity;

    /**
     * Encapsulate an event with an entity and arguments
     *
     * @param Object $entity
     * @param array $arguments
     */
    public function __construct($entity, array $arguments = array())
    {
        $this->entity = $entity;

        parent::__construct(null, $arguments);
    }

    /**
     * @return Object
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param Object $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}
