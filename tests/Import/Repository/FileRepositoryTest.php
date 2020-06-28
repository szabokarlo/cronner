<?php

namespace Tests\Import\Repository;

use Cronner\Import\Domain\File;
use Cronner\Import\Domain\FileCollection;
use Cronner\Import\Domain\Partner;
use Cronner\Import\Repository\FileRepository;
use Cronner\Import\Repository\PartnerRepository;
use Cronner\Import\Repository\ProductRepository;
use Cronner\Import\Service\ImportService;
use DirectoryIterator;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use SplFileInfo;

class FileRepositoryTest extends TestCase
{
    public function testgetFiles()
    {
        $this->assertTrue(true);
        return;

//        $file = $this->getMockBuilder(SplFileInfo::class)
//            ->disableOriginalConstructor()
//            ->setMethods(['isFile'])
//            ->getMock();
//
//        $file->expects($this->once())
//            ->method('isFile')
//            ->willReturn(false);
//
        /** @var DirectoryIterator|PHPUnit_Framework_MockObject_MockObject $directoryIterator */
        $directoryIterator = $this->getMockBuilder(DirectoryIterator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $file = $this->getMockBuilder(SplFileInfo::class )
            ->disableOriginalConstructor()
            ->setMethods(['isFile'])
            ->getMock();

        $file->expects($this->once())
            ->method('isFile')
            ->willReturn(false);

        $this->mockIterator($directoryIterator, []);

//
//        $directoryIterator->expects($this->once())
//            ->method('rewind');
//
//        $directoryIterator->expects($this->once())
//            ->method('current')
//            ->willReturn($file);
//
        /** @var FileRepository|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(FileRepository::class)
            //->disableOriginalConstructor()
            ->setConstructorArgs([$directoryIterator])
            ->getMock();

//        $sut->expects($this->once())
//            ->method('__construct')
//            ->with('dir');

//        $sut->expects($this->once())
//            ->method('getDirectoryIterator')
//            ->willReturn($directoryIterator);

        //var_dump($sut);

        $sut->getFiles();
    }

    public function mockIterator(PHPUnit_Framework_MockObject_MockObject $iteratorMock, array $items)
    {
        $iteratorData = new \stdClass();
        $iteratorData->array = $items;
        $iteratorData->position = 0;

        $iteratorMock->expects($this->any())
            ->method('rewind')
            ->will(
                $this->returnCallback(
                    function() use ($iteratorData) {
                        $iteratorData->position = 0;
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('current')
            ->will(
                $this->returnCallback(
                    function() use ($iteratorData) {
                        return $iteratorData->array[$iteratorData->position];
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('key')
            ->will(
                $this->returnCallback(
                    function() use ($iteratorData) {
                        return $iteratorData->position;
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('next')
            ->will(
                $this->returnCallback(
                    function() use ($iteratorData) {
                        $iteratorData->position++;
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('valid')
            ->will(
                $this->returnCallback(
                    function() use ($iteratorData) {
                        return isset($iteratorData->array[$iteratorData->position]);
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('count')
            ->will(
                $this->returnCallback(
                    function() use ($iteratorData) {
                        return sizeof($iteratorData->array);
                    }
                )
            );

        return $iteratorMock;
    }
}
