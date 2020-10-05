<?php

/**
 * A class to manage two array objects.
 * 
 * @author Ivan Moll Moll
 * @version 1.0
 */

namespace app\main;

use app\core\Product;
use app\core\Category;
use app\core\Fixed;
use app\core\Percent;

class Utils
{
    private $products;
    private $categories;

    public function __construct($arrayProducts, $arrayCategories)
    {
        $this->setProducts(new \ArrayObject());
        $this->setCategories(new \ArrayObject());
        $this->arrayOfProducts($arrayProducts);
        $this->arrayOfCategories($arrayCategories);
    }

    /**
     * Get the value of products
     */ 
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set the value of products
     *
     * @return  self
     */ 
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Get the value of categories
     */ 
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set the value of categories
     *
     * @return  self
     */ 
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Create new products and append to products array objects.
     * 
     * @param array products
     * @return void
     */
    public function arrayOfProducts($arrayProducts)
    {
        for ($i= 1; $i < count($arrayProducts); $i++) { 
            $product = new Product();
            $element = $arrayProducts[$i];
            for ($j = 0; $j < count($element); $j++) { 
                $method = "set" . ucfirst(strtolower($arrayProducts[0][$j]));
                $product->$method($element[$j]);
            }
            $this->getProducts()->append($product);
        }
    }

    /**
     * Create new categories and append to categories array objects.
     * 
     * @param array categories
     * @return void
     */
    public function arrayOfCategories($arrayCategories)
    {
        $patternFixedFirst = "/^[\+\-][0-9\.]+€/";
        $patternPercentFirst = "/^[\+\-][0-9\.]+%/";
        $patternFixedEnd = "/[\+\-][0-9\.]+€$/";
        $patternPercentEnd = "/[\+\-][0-9\.]+%$/";

        $patterns = array(
            "fixed0" => $patternFixedFirst,
            "percent1" => $patternPercentFirst,
            "fixed2" => $patternFixedEnd,
            "percent3" => $patternPercentEnd
        );

        foreach ($arrayCategories["categories"] as $name => $value) {
            $category = new Category();
            $category->setName($name);
            
            foreach ($patterns as $type => $pattern) {

                if(preg_match($pattern, $value, $match) == 1){
                    $onlyNumber = preg_replace("/[^\+\-0-9\.]/","",$match);
                    $valueOfFee = floatval($onlyNumber[0]);

                    if (strpos($type, "fixed") !== FALSE && !$category->haveFixedFee())
                        $category->addFee(new Fixed($valueOfFee));

                    if (strpos($type, "percent") !== FALSE && !$category->havePercentFee())
                        $category->addFee(new Percent($valueOfFee));

                }
            }
            $this->getCategories()->append($category);
        }
    }

    /**
     * Establish the relations of product objects and category objects.
     * 
     * @return void
     */
    public function setProductsAndCategories()
    {
        $itProducts = $this->getProducts()->getIterator();

        while ($itProducts->valid()) {
            $currentProduct = $itProducts->current();
            $itCategories = $this->getCategories()->getIterator();
        
            while ($itCategories->valid()) {
                $currentCategory = $itCategories->current();

                if ($currentProduct->getCategory() == $currentCategory->getName()) {
                    $currentProduct->setCategoryObject($currentCategory);
                    $currentCategory->addProduct($currentProduct);
                }
                $itCategories->next();
            }
            // Si no tiene categoria, crearla nueva con terminos de la generica
            if ($currentProduct->getCategoryObject() == NULL) {
                $category = new Category();
                $genericCategory = $this->getGenericCategory();

                $category->setName($currentProduct->getCategory());
                $category->setFees($genericCategory->getFees());

                $currentProduct->setCategoryObject($category);
                $category->addProduct($currentProduct);
                $this->getCategories()->append($category);
            }
            $itProducts->next();
        }
    }

    /**
     * Search generic category into categories array objects.
     * 
     * @return Category
     */
    public function getGenericCategory()
    {
        $itGenericCategory = $this->getCategories()->getIterator();
        
        while($itGenericCategory->valid()){
            if ($itGenericCategory->current()->getName() == "*") {
                return $itGenericCategory->current();
            }
            $itGenericCategory->next();
        }
        return NULL;
    }

    /**
     * Calculate a benefit of all categories.
     * 
     * @return void
     */
    public function calculateBenefitOfCategories()
    {
        $itCategories = $this->getCategories()->getIterator();

        while ($itCategories->valid()) {
            $currentCategory = $itCategories->current();
            if($currentCategory->getProducts()->count() > 0){
                $currentCategory->calcBenefit();
            }
            $itCategories->next();
        }
    }

    /**
     * Concat name and benefit of categories with benefit.
     * 
     * @return string
     */
    public function showCategoriesWithBenefits()
    {
        $itCategories = $this->getCategories()->getIterator();
        $toPrint = "";

        while ($itCategories->valid()) {
            $currentCategory = $itCategories->current();

            if($currentCategory->getBenefit() !== 0){
                $toPrint .= $currentCategory->showTotalBenefit() . "\n";
            }
            $itCategories->next();
        }
        echo (!is_null($toPrint)) ? $toPrint : "No hay categorias con beneficios";
    }
}
