<?php
namespace Tellaw\SunshineAdminBundle\Interfaces;

use Tellaw\SunshineAdminBundle\Entity\Context;

interface ConfigurationReaderServiceInterface {

    public function getHeaderForLists ( Context $context );

    public function getConfigurationForKey ( Context $context, $viewContext );

    public function getFinalConfigurationForAViewContext ( Context $context , $viewContext );

    public function getPageConfiguration ( $pageId );

    public function getMenuConfiguration ( $menuId );
}