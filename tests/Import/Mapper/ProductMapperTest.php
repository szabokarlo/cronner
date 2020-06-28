<?php

namespace Tests\Import\Mapper;

use Cronner\Import\Domain\Partner;
use Cronner\Import\Domain\Product;
use Cronner\Import\Domain\ProductCollection;
use Cronner\Import\Mapper\ProductMapper;
use League\Csv\Modifier\MapIterator;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ProductMapperTest extends TestCase
{
    public function testToCollection()
    {
        $partnerId = 20;

        $product1Data = [
            ProductMapper::KEY_NAME          => 'name',
            ProductMapper::KEY_CATEGORY      => 'category',
            ProductMapper::KEY_NET_PRICE     => 10,
            ProductMapper::KEY_PRICE         => 20,
            ProductMapper::KEY_DELIVERY_TIME => 14,
            ProductMapper::KEY_DELIVERY_COST => 7,
            ProductMapper::KEY_DESCRIPTION   => 'description',
            ProductMapper::KEY_URL           => 'http://test.url.com',
        ];

        $product2Data = [
            ProductMapper::KEY_NAME          => 'name2',
            ProductMapper::KEY_CATEGORY      => 'category2',
            ProductMapper::KEY_NET_PRICE     => 11,
            ProductMapper::KEY_PRICE         => 21,
            ProductMapper::KEY_DELIVERY_TIME => 15,
            ProductMapper::KEY_DELIVERY_COST => 8,
            ProductMapper::KEY_DESCRIPTION   => 'description2',
            ProductMapper::KEY_URL           => 'http://test.url2.com',
        ];

        $productsData = [$product1Data, $product2Data];

        /** @var Partner|PHPUnit_Framework_MockObject_MockObject $partner */
        $partner = $this->getMockBuilder(Partner::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();

        $partner->expects($this->any())
            ->method('getId')
            ->willReturn($partnerId);

        /** @var MapIterator|PHPUnit_Framework_MockObject_MockObject $products */
        $products = $this->getMockBuilder(MapIterator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockIterator($products, $productsData);

        $sut = new ProductMapper();

        $expectedProductCollection = new ProductCollection();
        $expectedProductCollection->add(
            new Product(
                $partnerId,
                $product1Data[ProductMapper::KEY_NAME],
                $product1Data[ProductMapper::KEY_CATEGORY],
                $product1Data[ProductMapper::KEY_NET_PRICE],
                $product1Data[ProductMapper::KEY_PRICE],
                $product1Data[ProductMapper::KEY_DELIVERY_TIME],
                $product1Data[ProductMapper::KEY_DELIVERY_COST],
                $product1Data[ProductMapper::KEY_DESCRIPTION],
                $product1Data[ProductMapper::KEY_URL]
            )
        );
        $expectedProductCollection->add(
            new Product(
                $partnerId,
                $product2Data[ProductMapper::KEY_NAME],
                $product2Data[ProductMapper::KEY_CATEGORY],
                $product2Data[ProductMapper::KEY_NET_PRICE],
                $product2Data[ProductMapper::KEY_PRICE],
                $product2Data[ProductMapper::KEY_DELIVERY_TIME],
                $product2Data[ProductMapper::KEY_DELIVERY_COST],
                $product2Data[ProductMapper::KEY_DESCRIPTION],
                $product2Data[ProductMapper::KEY_URL]
            )
        );

        $this->assertEquals(
            $expectedProductCollection,
            $sut->toCollection($partner, $products)
        );
    }

    public function testToCollectionWhenAProductIsInvalid()
    {
        $partnerId = 20;

        $product1Data = [
            ProductMapper::KEY_NAME          => 'name?',
            ProductMapper::KEY_CATEGORY      => 'category',
            ProductMapper::KEY_NET_PRICE     => 10,
            ProductMapper::KEY_PRICE         => 20,
            ProductMapper::KEY_DELIVERY_TIME => 14,
            ProductMapper::KEY_DELIVERY_COST => 7,
            ProductMapper::KEY_DESCRIPTION   => 'description',
            ProductMapper::KEY_URL           => 'http://test.url.com',
        ];

        $product2Data = [
            ProductMapper::KEY_NAME          => 'name2',
            ProductMapper::KEY_CATEGORY      => 'category2',
            ProductMapper::KEY_NET_PRICE     => 11,
            ProductMapper::KEY_PRICE         => 21,
            ProductMapper::KEY_DELIVERY_TIME => 15,
            ProductMapper::KEY_DELIVERY_COST => 8,
            ProductMapper::KEY_DESCRIPTION   => 'description2',
            ProductMapper::KEY_URL           => 'http://test.url2.com',
        ];

        $productsData = [$product1Data, $product2Data];

        /** @var Partner|PHPUnit_Framework_MockObject_MockObject $partner */
        $partner = $this->getMockBuilder(Partner::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();

        $partner->expects($this->any())
            ->method('getId')
            ->willReturn($partnerId);

        /** @var MapIterator|PHPUnit_Framework_MockObject_MockObject $products */
        $products = $this->getMockBuilder(MapIterator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockIterator($products, $productsData);

        $sut = new ProductMapper();

        $expectedProductCollection = new ProductCollection();
        $expectedProductCollection->add(
            new Product(
                $partnerId,
                $product2Data[ProductMapper::KEY_NAME],
                $product2Data[ProductMapper::KEY_CATEGORY],
                $product2Data[ProductMapper::KEY_NET_PRICE],
                $product2Data[ProductMapper::KEY_PRICE],
                $product2Data[ProductMapper::KEY_DELIVERY_TIME],
                $product2Data[ProductMapper::KEY_DELIVERY_COST],
                $product2Data[ProductMapper::KEY_DESCRIPTION],
                $product2Data[ProductMapper::KEY_URL]
            )
        );

        $this->assertEquals(
            $expectedProductCollection,
            $sut->toCollection($partner, $products)
        );
    }

    private function mockIterator(PHPUnit_Framework_MockObject_MockObject $iteratorMock, array $items)
    {
        $iteratorData           = new \stdClass();
        $iteratorData->array    = $items;
        $iteratorData->position = 0;

        $iteratorMock->expects($this->any())
            ->method('rewind')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        $iteratorData->position = 0;
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('current')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        return $iteratorData->array[$iteratorData->position];
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('key')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        return $iteratorData->position;
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('next')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        $iteratorData->position++;
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('valid')
            ->will(
                $this->returnCallback(
                    function () use ($iteratorData) {
                        return isset($iteratorData->array[$iteratorData->position]);
                    }
                )
            );

        return $iteratorMock;
    }
}
