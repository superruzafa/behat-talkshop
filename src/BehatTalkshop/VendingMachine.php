<?php

namespace BehatTalkshop;

class VendingMachine
{
    /** @var int[]  Stores how many elements there are by product */
    private $productStock = array();

    /** @var int    The maximum number of elements per product */
    private $productCapacity = 10;

    /**
     * Replenish the vending machine with products
     *
     * @param   string  $productName
     * @param   int     $quantity
     */
    public function replenish($productName, $quantity) {
        if (!(isset($this->productStock[$productName]))) {
            $this->productStock[$productName] = min($this->productCapacity, + $quantity);
        } else {
            $this->productStock[$productName] = min($this->productCapacity, $this->productStock[$productName] + $quantity);
        }
    }

    /**
     * Sets the maximum capacity for all the products
     *
     * @param int   $capacity   Vending machine capacity per product
     */
    public function setMaximumCapacityForAllProduct($capacity)
    {
        $this->productCapacity = $capacity;
    }

    /**
     * Picks up products from the vending machine
     *
     * @param   string  $product
     * @param   int     $count
     * @return  string[]
     */
    public function pick($product, $count = 1) {
        if (!isset($this->productStock[$product])) {
            throw new \RuntimeException("The vending machine doesn't serve any $product");
        }

        $number = min($count, $this->productStock[$product]);
        if ($number == 0) {
            $products = array();
        } else {
            $products = array_fill(0, $number, $product);
            $this->productStock[$product] = max(0, $this->productStock[$product] - $count);
        }

        return $products;
    }

    /**
     * Gets how many elements for a product left in the vending machine
     *
     * @param   string  $product
     * @return  int
     */
    public function getStock($product)
    {
        return isset($this->productStock[$product]) ? $this->productStock[$product] : 0;
    }
}
