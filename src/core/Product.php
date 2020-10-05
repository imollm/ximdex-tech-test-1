<?php

/**
 * A class to represent a product.
 * 
 * @author Ivan Moll Moll
 * @version 1.0
 */

 namespace app\core;

class Product
{
    private $product;
    private $category;
    private $cost;
    private $quantity;
    private $salePrice;
    private $categoryObject;

    public function __construct()
    {
        $this->setProduct('');
        $this->setCategory('');
        $this->setCost(0);
        $this->setQuantity(0);
        $this->setSalePrice(0);
        $this->setCategoryObject(NULL);
    }

    /**
     * Get the value of product
     */ 
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set the value of product
     *
     * @return  self
     */ 
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get the value of category
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of cost
     */ 
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set the value of cost
     *
     * @return  self
     */ 
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get the value of quantity
     */ 
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     * @return  self
     */ 
    public function setQuantity($quantity)
    {
        $this->quantity = intval(str_replace(".", "", $quantity));

        return $this;
    }

    /**
     * Get the value of salePrice
     */ 
    public function getSalePrice()
    {
        return $this->salePrice;
    }

    /**
     * Set the value of salePrice
     *
     * @return  self
     */ 
    public function setSalePrice($salePrice)
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    /**
     * Get the value of categoryObject
     */ 
    public function getCategoryObject()
    {
        return $this->categoryObject;
    }

    /**
     * Set the value of categoryObject
     *
     * @return  self
     */ 
    public function setCategoryObject($categoryObject)
    {
        $this->categoryObject = $categoryObject;

        return $this;
    }

    /**
     * Calc PVP with fees of related category.
     * 
     * @return void
     */
    public function calcPVP()
    {
        if(!is_null($this->getCategoryObject())){
            $itFees = $this->getCategoryObject()->getFees()->getIterator();
            $pvp = 0;

            while ($itFees->valid()) {
                $currentFee = $itFees->current();

                if($currentFee instanceof Percent){
                    if($pvp == 0)
                        $pvp = $this->getCost() + ($this->getCost() * ($currentFee->getValue() / 100));
                    else
                        $pvp += ($pvp * ($currentFee->getValue() / 100));
                }
                elseif($currentFee instanceof Fixed){
                    if ($pvp == 0)
                        $pvp = $this->getCost() + $currentFee->getValue();
                    else
                        $pvp += $currentFee->getValue();
                }
                $itFees->next();
            }
            $this->setSalePrice($pvp);
        }
    }

    /**
     * To print name and benefit.
     * 
     * @return string
     */
    public function __toString()
    {
        $toPrint = "";
        $toPrint .= "Producto: " . $this->getProduct() . "\n";
        if (is_object($this->getCategoryObject()))
            $toPrint .= "Categoria: " . $this->getCategoryObject()->getName() . "\n";
        $toPrint .= "Coste: " . $this->getCost() . "\n";
        $toPrint .= "Cantidad: " . $this->getQuantity() . "\n";
        $toPrint .= "PVP: " . $this->getSalePrice() . "\n";

        return $toPrint;
    }
}