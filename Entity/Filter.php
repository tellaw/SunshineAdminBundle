<?php

namespace Tellaw\SunshineAdminBundle\Entity;



/**
 * Class Context
 * @package Tellaw\SunshineAdminBundle\Entity
 *
 * Bean used to store the current context of an entity
 *
 */
class Filter
{
    private $key;
    private $value;

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
