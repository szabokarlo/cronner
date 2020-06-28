<?php

namespace Tests\Import\Repository;

use Cronner\Import\Domain\Partner;
use Cronner\Import\Domain\ProductCollection;
use Cronner\Import\Mapper\ProductMapper;
use Cronner\Import\Repository\CsvRepository;
use Cronner\Import\Repository\PartnerRepository;
use League\Csv\Modifier\MapIterator;
use League\Csv\Reader;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class CsvRepositoryTest extends TestCase
{
    public function testGetProducts()
    {
        $filePath = 'filePath';

        /** @var Partner|PHPUnit_Framework_MockObject_MockObject $partner */
        $partner = $this->getMockBuilder(Partner::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mapIterator = $this->getMockBuilder(MapIterator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $products = $this->getMockBuilder(ProductCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ProductMapper|PHPUnit_Framework_MockObject_MockObject $productMapper */
        $productMapper = $this->getMockBuilder(ProductMapper::class)
            ->disableOriginalConstructor()
            ->setMethods(['toCollection'])
            ->getMock();

        $productMapper->expects($this->once())
            ->method('toCollection')
            ->with($partner, $mapIterator)
            ->willReturn($products);

        $reader = $this->getMockBuilder(Reader::class)
            ->disableOriginalConstructor()
            ->setMethods(['fetchAssoc'])
            ->getMock();

        $reader->expects($this->once())
            ->method('fetchAssoc')
            ->willReturn($mapIterator);

        /** @var CsvRepository|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(CsvRepository::class)
            ->setConstructorArgs([$productMapper])
            ->setMethods(['getCsvReader'])
            ->getMock();

        $sut->expects($this->once())
            ->method('getCsvReader')
            ->willReturn($reader);

        $this->assertSame($products, $sut->getProducts($partner, $filePath));
    }
}
