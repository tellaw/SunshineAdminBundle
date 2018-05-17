<?php

namespace Tellaw\SunshineAdminBundle\Event;

/**
 * Sunshine events
 */
final class SunshineEvents
{
    /**
     * Triggered on entity update/creation before it is flushed
     *
     * @Event("AppBundle\Event\EntityEvent")
     */
    const ENTITY_PRE_FLUSHED = 'entity.pre_flushed';

    /**
     * Triggered on entity edit
     *
     * @Event("AppBundle\Event\EntityEvent")
     */
    const ENTITY_PRE_EDIT = 'entity.pre_edit';
}
