<?php
namespace Tellaw\SunshineAdminBundle\Interfaces;

use Tellaw\SunshineAdminBundle\Entity\Context;

interface CrudServiceInterface {

    public function getEntityList ( Context $entityContext, $configuration );

    public function getEntity ( Context $context );

    public function deleteEntity ( Context $context );
    

}