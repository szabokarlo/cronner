<?php

namespace Tests\Import\Service;

use Cronner\Import\Domain\File;
use Cronner\Import\Domain\FileCollection;
use Cronner\Import\Domain\Partner;
use Cronner\Import\Domain\ProductCollection;
use Cronner\Import\Repository\CsvRepository;
use Cronner\Import\Repository\FileRepository;
use Cronner\Import\Repository\PartnerRepository;
use Cronner\Import\Repository\ProductRepository;
use Cronner\Import\Service\ImportService;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerInterface;

class ImportServiceTest extends TestCase
{
    public function testExecute()
    {
        $partnerName        = 'partnerName';
        $partnerCountryCode = 'partnerCountryCode';
        $filePath           = 'filePath';

        $file = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPartnerName', 'getPartnerCountryCode', 'getPath'])
            ->getMock();

        $file->expects($this->once())
            ->method('getPartnerName')
            ->willReturn($partnerName);

        $file->expects($this->once())
            ->method('getPartnerCountryCode')
            ->willReturn($partnerCountryCode);

        $file->expects($this->once())
            ->method('getPath')
            ->willReturn($filePath);

        $fileCollection = $this->getMockBuilder(FileCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIterator'])
            ->getMock();

        $fileCollection->expects($this->once())
            ->method('getIterator')
            ->willReturn([$file]);

        /** @var FileRepository|PHPUnit_Framework_MockObject_MockObject $fileRepository */
        $fileRepository = $this->getMockBuilder(FileRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFiles'])
            ->getMock();

        $fileRepository->expects($this->once())
            ->method('getFiles')
            ->willReturn($fileCollection);

        $partner = $this->getMockBuilder(Partner::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var PartnerRepository|PHPUnit_Framework_MockObject_MockObject $partnerRepository */
        $partnerRepository = $this->getMockBuilder(PartnerRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $partnerRepository->expects($this->once())
            ->method('get')
            ->with($partnerName, $partnerCountryCode)
            ->willReturn($partner);

        $products = $this->getMockBuilder(ProductCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var CsvRepository|PHPUnit_Framework_MockObject_MockObject $csvRepository */
        $csvRepository = $this->getMockBuilder(CsvRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getProducts'])
            ->getMock();

        $csvRepository->expects($this->once())
            ->method('getProducts')
            ->with($partner, $filePath)
            ->willReturn($products);

        /** @var ProductRepository|PHPUnit_Framework_MockObject_MockObject $productRepository */
        $productRepository = $this->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['importProducts'])
            ->getMock();

        $productRepository->expects($this->once())
            ->method('importProducts')
            ->with($partner, $products);

        /** @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sut = new ImportService($logger, $fileRepository, $partnerRepository, $csvRepository, $productRepository);

        $sut->execute();
    }
}
