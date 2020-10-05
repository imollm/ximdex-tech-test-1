<?php

/**
 * A class to manage CSV file
 * 
 * @author Ivan Moll Moll
 * @version 1.0
 */

namespace app\core;

abstract class Fee
{
    private $type;
    private $value;
    
    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of value
     */ 
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of value
     *
     * @return  self
     */ 
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}