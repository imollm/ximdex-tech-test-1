<?php

/**
 * XIMDEX TEST: CALC OF BENEFITS
 * 
 * @author Ivan Moll Moll
 * @version 1.0
 */

require_once './vendor/autoload.php';

use app\core\{Csv, Json};
use app\main\Utils;

if (isset($argv[1]) && isset($argv[2])) {
    $csv = new Csv($argv[1]);
    $json = new Json($argv[2]);

    $arrayProducts = $csv->read();
    $arrayCategories = $json->read();

    $utils = new Utils($arrayProducts, $arrayCategories);

    $utils->setProductsAndCategories();
    $utils->calculateBenefitOfCategories();
    $utils->showCategoriesWithBenefits();

} else {
    throw new Exception("Debes indicar dos parametros");
}
