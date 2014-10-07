<?php

namespace BehatTalkshop;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

/**
 * Defines application features from the specific context.
 */
class VendingMachineContext implements Context, SnippetAcceptingContext
{
    /** @var VendingMachine */
    private $vendingMachine;

    /** @var string[] */
    private $pickedUp;

    /** @var bool */
    private $exceptionThrown = false;

    /**
     * @Given a vending machine
     */
    public function aVendingMachine()
    {
        $this->vendingMachine = new VendingMachine();
        $this->vendingMachine->setMaximumCapacityForAllProduct(15);
    }

    /**
     * @When the product dealer replenish :count :product
     */
    public function theProductDealerReplenishCocaCola($count, $product)
    {
        $this->vendingMachine->replenish($product, $count);
    }

    /**
     * @Then there are :count :product left in the vending machine
     */
    public function thereAreLeftInTheVendingMachine($count, $product)
    {
        \PHPUnit_Framework_Assert::assertEquals($count, $this->vendingMachine->getStock($product));
    }

    /**
     * @When an employee picks up :count :product
     */
    public function anEmployeePicksUp($count, $product)
    {
        try {
            $this->pickedUp = $this->vendingMachine->pick($product, $count);
        } catch (\Exception $e) {
            $this->exceptionThrown = true;
            $this->pickedUp = array();
        }
    }

    /**
     * @Then the employee receives :count :product
     */
    public function theEmployeeReceives($count, $products)
    {
        $expected = $count ? array_fill(0, $count, $products) : array();
        \PHPUnit_Framework_Assert::assertEquals($expected, $this->pickedUp);
    }

    /**
     * @Given the vending machine contains :count :product
     */
    public function theVendingMachineContains($count, $product)
    {
        $this->vendingMachine->replenish($product, $count);
    }

    /**
     * @Given the vending machine can store :count items per product at most
     */
    public function theVendingMachineCanStoreItemsPerProductAtMost($count)
    {
        $this->vendingMachine->setMaximumCapacityForAllProduct($count);
    }

    /**
     * @Given an error message is displayed
     */
    public function anErrorMessageIsDisplayded()
    {
        \PHPUnit_Framework_Assert::assertTrue($this->exceptionThrown);
    }

    /**
     * @Given the vending machine comes with these products out of the box
     */
    public function theVendingMachineComesWithTheseProductsOutOfTheBox(\Behat\Gherkin\Node\TableNode $table)
    {
        foreach ($table->getTable() as $row) {
            list($count, $product) = $row;
            $this->vendingMachine->replenish($product, $count);
        }
    }

    /**
     * @Given the vending machine motd is
     */
    public function theVendingMachineMotdIs(\Behat\Gherkin\Node\PyStringNode $string)
    {
    }
}
