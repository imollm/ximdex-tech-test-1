<?php

/**
 * A abstract class to define a File
 * 
 * @author Ivan Moll Moll
 * @version 1.0
 */

namespace app\core;

abstract class File
{
    private $name;
    private $handler;
    private $extension;

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of handler
     */ 
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Set the value of handler
     *
     * @return  self
     */ 
    public function setHandler($handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Get the value of extension
     */ 
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set the value of extension
     *
     * @return  self
     */ 
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    protected function sanitizeName()
    {
        if (preg_match('/^.*\.('.$this->getExtension().')$/i', $this->getName()) < 1) {
             $this->setName($this->getName() . "." . $this->getExtension());
        }
    }

    protected function exists()
    {
        if (!file_exists($this->getName())){
            throw new \Exception("El fichero " . $this->getName() . ", no existe");
        }
    }

    protected function openForRead()
    {
        try {
            $this->setHandler(fopen($this->getName(), "r"));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected abstract function read();

    protected function toString()
    {
        return stream_get_contents($this->getHandler());
    }
}
