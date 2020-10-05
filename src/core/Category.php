<?php

/**
 * A class to represent a category.
 * 
 * @author Ivan Moll Moll
 * @version 1.0
 */

namespace app\core;

class Category
{
    private $name;
    private $fees;
    private $benefit;
    private $products;

    public function __construct()
    {
        $this->setName('');
        $this->setFees(new \ArrayObject());
        $this->setBenefit(0);
        $this->setProducts();
    }

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
     * Get the value of orderOfFees
     */ 
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * Get the value of orderOfFees
     */ 
    public function setFees($fees)
    {
        $this->fees = $fees;
    }

    /**
     * Add new fee - FIFO system to apply on product PVP
     * 
     * @param Fee New fee is added on category
     * @return void
     */
    public function addFee($fee)
    {
        $this->getFees()->append($fee);
    }

    /**
     * Get the value of benefit
     */ 
    public function getBenefit()
    {
        return $this->benefit;
    }

    /**
     * Set the value of benefit
     *
     * @return  self
     */ 
    public function setBenefit($benefit)
    {
        $this->benefit = $benefit;

        return $this;
    }

    /**
     * Set the value of products
     *
     * @return  self
     */ 
    public function addProduct($product)
    {
        $this->getProducts()->append($product);

        return $this;
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
    public function setProducts()
    {
        $this->products = new \ArrayObject();

        return $this;
    }

    /**
     * Iterate over cateogry fees and return
     * true if it have type of fee specified
     * 
     * @param string name of fee type
     * @return bool
     */
    private function searchFee($type)
    {
        $itFees = $this->getFees()->getIterator();

        while($itFees->valid()){
            $currentFee = $itFees->current();

            if($currentFee->getType() == $type)
                return TRUE;

            $itFees->next();
        }
        return FALSE;
    }

    /**
     * Say if category have already fixed fee
     * 
     * @return bool
     */
    public function haveFixedFee()
    {
        return $this->searchFee("fixed");
    }

    /**
     * Say if category have already fixed fee
     * 
     * @return bool
     */
    public function havePercentFee()
    {
        return $this->searchFee("percent");
    }

    /**
     * Calc a total benefit.
     * 
     * @return void
     */
    public function calcBenefit()
    {
        $itProducts = $this->getProducts()->getIterator();
        $benefit = 0;

        while ($itProducts->valid()) {
            $currentProduct = $itProducts->current();

            $currentProduct->calcPVP();

            if ($currentProduct->getQuantity() > 0) 
                $totalSales = $currentProduct->getQuantity() * $currentProduct->getSalePrice();
                $totalCost = $currentProduct->getQuantity() * $currentProduct->getCost(); 
                $benefit += ($totalSales - $totalCost);
                $this->setBenefit($benefit);

            $itProducts->next();
        }
    }

    /**
     * To print name and benefit.
     * 
     * @return string
     */
    public function showTotalBenefit()
    {
        return $this->getName() . ": " . number_format($this->getBenefit(), 2, '.', '');
    }
}