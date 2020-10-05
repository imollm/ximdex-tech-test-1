<?php

/**
 * A class to manage CSV file
 * 
 * @author Ivan Moll Moll
 * @version 1.0
 */

namespace app\core;

use app\core\File;

class Csv extends File
{
    public function __construct($fileName, $extension = "csv")
    {
        parent::setName($fileName);
        parent::setExtension($extension);
        parent::sanitizeName();
        parent::exists();
        parent::openForRead();
    }

    /**
     * Read CSV formatted file and return an array with info.
     * 
     * @return array
     */
    public function read()
    {
        $content = array();
        $lineNum = 0;
        $patternSearch = "/€/";
        $patternReplace = "/[^0-9\.\,]/";

        while(($line = fgetcsv(parent::getHandler(), 0, ";")) !== FALSE){
            $content[$lineNum] = array();
            for ($i = 0; $i < count($line); $i++) {
                if(preg_match($patternSearch, $line[$i]) == 1){
                    $replaced = preg_replace($patternReplace, '', $line[$i]);
                    $number = floatval(str_replace(',', '.', str_replace('.', '', $replaced)));
                    array_push($content[$lineNum], $number);
                }else{
                    array_push($content[$lineNum], $line[$i]);
                }
            }
            $lineNum++;
        }
        return $content;
    }
}