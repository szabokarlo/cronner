<?php

namespace Tests\Import\Repository;

use Cronner\Import\Domain\Partner;
use Cronner\Import\Domain\Product;
use Cronner\Import\Domain\ProductCollection;
use Cronner\Import\Mapper\ProductMapper;
use Cronner\Import\Repository\PartnerRepository;
use Cronner\Import\Repository\ProductRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerInterface;

class ProductRepositoryTest extends TestCase
{
    public function testWhenPartnerIsInTheDB()
    {
        $partnerId = 10;

        $products = new ProductCollection();

        $product1Data = [
            ProductMapper::KEY_NAME          => 'name1',
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

        $products->add(
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

        $products->add(
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

        /** @var Partner|PHPUnit_Framework_MockObject_MockObject $partner */
        $partner = $this->getMockBuilder(Partner::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();

        $partner->expects($this->once())
            ->method('getId')
            ->willReturn($partnerId);

        $deleteStatement = $this->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $deleteStatement->expects($this->once())
            ->method('execute')
            ->with([$partnerId])
            ->willReturn(true);

        $insertStatement = $this->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $insertStatement->expects($this->once())
            ->method('execute')
            ->with(array_merge([$partnerId], array_values($product1Data), [$partnerId], array_values($product2Data)))
            ->willReturn(true);

        /** @var PDO|PHPUnit_Framework_MockObject_MockObject $pdo */
        $pdo = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->setMethods(['beginTransaction', 'prepare', 'commit'])
            ->getMock();

        $pdo->expects($this->once())
            ->method('beginTransaction');

        $pdo->expects($this->at(1))
            ->method('prepare')
            ->with('DELETE FROM product WHERE partner_id=?')
            ->willReturn($deleteStatement);

        $pdo->expects($this->at(2))
            ->method('prepare')
            ->with('INSERT INTO product (partner_id, name, category, net_price, price, delivery_time, delivery_cost, description, url) VALUES (?,?,?,?,?,?,?,?,?), (?,?,?,?,?,?,?,?,?)')
            ->willReturn($insertStatement);

        $pdo->expects($this->once())
            ->method('commit');

        /** @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sut = new ProductRepository($logger, $pdo);

        $sut->importProducts($partner, $products);
    }
}
