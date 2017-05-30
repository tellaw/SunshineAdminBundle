<?php

namespace Tellaw\SunshineAdminBundle\Entity;

use Tellaw\SunshineAdminBundle\Interfaces\CrudServicesInterface;

/**
 * Class Context
 * @package Tellaw\SunshineAdminBundle\Entity
 *
 * Bean used to store the current context of an entity
 *
 */
class Context {

    private $entityName;
    private $startPage;
    private $nbItemPerPage;

    /**
     * @var int
     */
    private $totalCount;

    private $identifier;
    private $className;
    private $searchKey;
    private $filters;
    private $orderBy;
    private $orderWay;
    private $targetId;

    private $dql;

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param mixed $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return mixed
     */
    public function getIsDisplayedInMenu()
    {
        return $this->isDisplayedInMenu;
    }

    /**
     * @param mixed $isDisplayedInMenu
     */
    public function setIsDisplayedInMenu($isDisplayedInMenu)
    {
        $this->isDisplayedInMenu = $isDisplayedInMenu;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
    private $isDisplayedInMenu;
    private $roles;

    /**
     * @return mixed
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param mixed $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @return mixed
     */
    public function getStartPage()
    {
        return $this->startPage;
    }

    /**
     * @param mixed $startPage
     */
    public function setStartPage($startPage)
    {
        $this->startPage = $startPage;
    }

    /**
     * @return mixed
     */
    public function getNbItemPerPage()
    {
        return $this->nbItemPerPage;
    }

    /**
     * @param mixed $nbItemPerPage
     */
    public function setNbItemPerPage($nbItemPerPage)
    {
        $this->nbItemPerPage = $nbItemPerPage;
    }

    /**
     * @return mixed
     */
    public function getSearchKey()
    {
        return $this->searchKey;
    }

    /**
     * @param mixed $searchKey
     */
    public function setSearchKey($searchKey)
    {
        $this->searchKey = $searchKey;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param mixed $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getOrderBy() {
        return $this->orderBy;
    }
    public function setOrderBy( $orderBy ) {
        $this->orderBy = $orderBy;
    }

    public function getOrderWay() {
        return $this->orderWay;
    }
    public function setOrderWay( $orderWay ) {
        $this->orderWay = $orderWay;
    }

    /**
     * @return mixed
     */
    public function getDql()
    {
        return $this->dql;
    }

    /**
     * @param mixed $dql
     */
    public function setDql($dql)
    {
        $this->dql = $dql;
    }

    /**
     * @return mixed
     */
    public function getTargetId()
    {
        return $this->targetId;
    }

    /**
     * @param mixed $targetId
     */
    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @param int $totalCount
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
    }

}
