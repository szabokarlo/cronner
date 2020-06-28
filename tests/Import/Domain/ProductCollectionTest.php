<?php

namespace Tests\Import\Domain;

use ArrayIterator;
use Cronner\Import\Domain\File;
use Cronner\Import\Domain\FileCollection;
use Cronner\Import\Domain\Product;
use Cronner\Import\Domain\ProductCollection;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class ProductCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testArrayIterator()
    {
        $sut = new ProductCollection();

        $this->assertInstanceOf(ArrayIterator::class, $sut->getIterator());
    }

    public function testFunctions()
    {
        /** @var Product|PHPUnit_Framework_MockObject_MockObject $product1 */
        $product1 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var Product|PHPUnit_Framework_MockObject_MockObject $product2 */
        $product2 = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sut = new ProductCollection();

        $this->assertEquals(0, $sut->count());

        $sut->add($product1);

        $this->assertEquals(1, $sut->count());

        $sut->add($product2);

        $this->assertEquals(2, $sut->count());

        $iterator = $sut->getIterator();

        $this->assertEquals($product1, $iterator->current());
        $iterator->next();
        $this->assertEquals($product2, $iterator->current());

        $this->assertEquals((new ProductCollection())->add($product1), $sut->slice(0, 1));
        $this->assertEquals((new ProductCollection())->add($product1)->add($product2), $sut->slice(0, 2));
        $this->assertEquals((new ProductCollection())->add($product2), $sut->slice(1, 1));
        $this->assertEquals((new ProductCollection()), $sut->slice(3, 1));
    }
}
