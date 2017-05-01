<?php
namespace Tellaw\SunshineAdminBundle\Interfaces;

interface ContextInterface {

    public function getIdentifier();
    public function setIdentifier( $id );

    public function getClassName();
    public function setClassName( $className );

    public function getIsDisplayedInMenu();
    public function setIsDisplayedInMenu( $isDisplayedInMenu );

    public function getRoles();
    public function setRoles( $roles );

    public function getEntityName();
    public function setEntityName( $name );

    public function getStartPage();
    public function setStartPage( $startPage );

    public function getNbItemPerPage();
    public function setNbItemPerPage( $nbItemPerPage );

    public function getFilters();
    public function setFilters( $filters );

    public function getSearchKey();
    public function setSearchKey( $searchKey );

    public function getOrderBy();
    public function setOrderBy( $orderBy );

    public function getOrderWay();
    public function setOrderWay( $orderWay );

    public function getTargetId();
    public function setTargetId( $targetId );

}

