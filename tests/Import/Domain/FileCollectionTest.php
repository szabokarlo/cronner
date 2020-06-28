<?php

namespace Tests\Import\Domain;

use ArrayIterator;
use Cronner\Import\Domain\File;
use Cronner\Import\Domain\FileCollection;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class FileCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testArrayIterator()
    {
        $sut = new FileCollection();

        $this->assertInstanceOf(ArrayIterator::class, $sut->getIterator());
    }

    public function testAdd()
    {
        /** @var File|PHPUnit_Framework_MockObject_MockObject $file1 */
        $file1 = $this->getMockBuilder(File::class)
            ->setMethods(['construct'])
            ->setConstructorArgs(['name1', 'hu', 10])
            ->getMock();

        /** @var File|PHPUnit_Framework_MockObject_MockObject $file2 */
        $file2 = $this->getMockBuilder(File::class)
            ->setMethods(['construct'])
            ->setConstructorArgs(['name2', 'hu', 20])
            ->getMock();

        $sut = new FileCollection();
        $sut->add($file1);
        $sut->add($file2);

        $iterator = $sut->getIterator();

        $this->assertEquals($file1, $iterator->current());
        $iterator->next();
        $this->assertEquals($file2, $iterator->current());
    }
}
