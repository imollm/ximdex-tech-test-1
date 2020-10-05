<?php
/**
 * A class define percent fee
 * 
 * @author Ivan Moll Moll
 * @version 1.0
 */

namespace app\core;

use app\core\Fee;

class Percent extends Fee
{
    public function __construct($value)
    {
        parent::setType("percent");
        parent::setValue($value);
    }
}