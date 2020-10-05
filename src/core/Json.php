<?php

/**
 * A class to manage JSON file
 * 
 * @author Ivan Moll Moll
 * @version 1.0
 */

namespace app\core;

use app\core\File;

class Json extends File
{
    public function __construct($fileName, $extension = "json")
    {
        parent::setName($fileName);
        parent::setExtension($extension);
        parent::sanitizeName();
        parent::exists();
        parent::openForRead();
    }

    public function read()
    {
        return json_decode(parent::toString(), true);
    }
}