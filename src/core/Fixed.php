<?php

/**
 * A class define fixed fee
 * 
 * @author Ivan Moll Moll
 * @version 1.0
 */

namespace app\core;

use app\core\Fee;

class Fixed extends Fee
{
    public function __construct($value)
    {
        parent::setType("fixed");
        parent::setValue($value);
    }
}