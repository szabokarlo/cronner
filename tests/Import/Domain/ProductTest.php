<?php

namespace Tests\Import\Domain;

use Cronner\Import\Domain\Product;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @dataProvider dataProviderForTestGetters
     */
    public function testGetters(
        $partnerId,
        $name,
        $category,
        $netPrice,
        $price,
        $deliveryTime,
        $deliveryCost,
        $description,
        $url,
        $expectedDeliveryCost
    ) {
        $sut = new Product(
            $partnerId,
            $name,
            $category,
            $netPrice,
            $price,
            $deliveryTime,
            $deliveryCost,
            $description,
            $url
        );

        $this->assertEquals($partnerId, $sut->getPartnerId());
        $this->assertEquals($name, $sut->getName());
        $this->assertEquals($category, $sut->getCategory());
        $this->assertEquals($netPrice, $sut->getNetPrice());
        $this->assertEquals($price, $sut->getPrice());
        $this->assertEquals($deliveryTime, $sut->getDeliveryTime());
        $this->assertEquals($expectedDeliveryCost, $sut->getDeliveryCost());
        $this->assertEquals($description, \addslashes($sut->getDescription()));
        $this->assertEquals($url, $sut->getUrl());
    }

    public function dataProviderForTestGetters()
    {
        return [
            [
                'partnerId'            => 10,
                'name'                 => 'name',
                'category'             => 'category',
                'netPrice'             => 10,
                'price'                => 11,
                'deliveryTime'         => 12,
                'deliveryCost'         => 13,
                'description'          => 'description',
                'url'                  => 'http://test.com',
                'expectedDeliveryCost' => 13,
            ],
            [
                'partnerId'            => 10,
                'name'                 => 'name',
                'category'             => 'category',
                'netPrice'             => 10,
                'price'                => 11,
                'deliveryTime'         => 12,
                'deliveryCost'         => 'FREE',
                'description'          => 'description',
                'url'                  => 'http://test.com',
                'expectedDeliveryCost' => 0,
            ],
            [
                'partnerId'            => 20,
                'name'                 => 'Árvíztűrő tükörfúrógép test text Мъжка тениска с класическа кройка в бяло с джоб 0123456789 . , ( ) / \ + “ _ &',
                'category'             => 'Árvíztűrő tükörfúrógép test text Мъжка тениска с класическа кройка в бяло с джоб 0123456789 . , - > < /',
                'netPrice'             => 10,
                'price'                => 11,
                'deliveryTime'         => 12,
                'deliveryCost'         => 'FREE',
                'description'          => 'description',
                'url'                  => 'http://test.com',
                'expectedDeliveryCost' => 0,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForTestIfInvalidArgumentExceptionHasThrown
     */
    public function testIfInvalidArgumentExceptionHasThrown(
        $partnerId,
        $name,
        $category,
        $netPrice,
        $price,
        $deliveryTime,
        $deliveryCost,
        $description,
        $url
    ) {
        $this->expectException(InvalidArgumentException::class);

        new Product(
            $partnerId,
            $name,
            $category,
            $netPrice,
            $price,
            $deliveryTime,
            $deliveryCost,
            $description,
            $url
        );
    }

    public function dataProviderForTestIfInvalidArgumentExceptionHasThrown()
    {
        return [
            [
                'partnerId'    => 'invalid',
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => -10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 0,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'invalid?',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'invalid?',
                'netPrice'     => 10,
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 'invalid',
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => -10,
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 0,
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 'invalid',
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => -10,
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 0,
                'deliveryTime' => 12,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 11,
                'deliveryTime' => 'invalid',
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 11,
                'deliveryTime' => -10,
                'deliveryCost' => 13,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => 'invalid',
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => -10,
                'description'  => 'description',
                'url'          => 'http://test.com',
            ],
            [
                'partnerId'    => 10,
                'name'         => 'name',
                'category'     => 'category',
                'netPrice'     => 10,
                'price'        => 11,
                'deliveryTime' => 12,
                'deliveryCost' => 12,
                'description'  => 'description',
                'url'          => 'invalid',
            ],
        ];
    }
}
