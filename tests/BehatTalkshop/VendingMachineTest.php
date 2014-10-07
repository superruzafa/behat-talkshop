<?php

namespace BehatTalkshop;

class VendingMachineTest extends \PHPUnit_Framework_TestCase
{
    /** @var VendingMachine */
    private $vendingMachine;

    protected function setUp()
    {
        $this->vendingMachine = new VendingMachine();
    }

    /** @test */
    public function replenishNewProduct()
    {
        $this->vendingMachine->replenish('product1', 5);
        $this->assertEquals(5, $this->vendingMachine->getStock('product1'));
    }

    /** @test */
    public function replenishAlreadyExistingProduct()
    {
        $this->vendingMachine->replenish('product1', 5);
        $this->vendingMachine->replenish('product1', 3);
        $this->assertEquals(8, $this->vendingMachine->getStock('product1'));
    }

    /** @test */
    public function replenishProductToMaxCapacity()
    {
        $this->vendingMachine->setMaximumCapacityForAllProduct(20);
        $this->vendingMachine->replenish('product1', 99);
        $this->assertEquals(20, $this->vendingMachine->getStock('product1'));
    }

    /** @test */
    public function pickUnavailableProduct()
    {
        $this->setExpectedException('\RuntimeException');
        $this->vendingMachine->pick('non existing');
    }

    /** @test */
    public function pickOneElementOfAProduct()
    {
        $this->vendingMachine->replenish('product1', 10);
        $products = $this->vendingMachine->pick('product1');
        $this->assertEquals(array('product1'), $products);
        $this->assertEquals(9, $this->vendingMachine->getStock('product1'));
    }

    /** @test */
    public function pickAllElementsOfAProduct()
    {
        $this->vendingMachine->replenish('product1', 3);
        $products = $this->vendingMachine->pick('product1', 3);
        $this->assertEquals(array('product1', 'product1', 'product1'), $products);
        $this->assertEquals(0, $this->vendingMachine->getStock('product1'));
    }

    /** @test */
    public function pickAllElementsOfAProductAndEvenMore()
    {
        $this->vendingMachine->replenish('product1', 3);
        $products = $this->vendingMachine->pick('product1', 9);
        $this->assertEquals(array('product1', 'product1', 'product1'), $products);
        $this->assertEquals(0, $this->vendingMachine->getStock('product1'));
    }
}
