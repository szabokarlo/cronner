<?php

namespace Tests\Import\Repository;

use Cronner\Import\Domain\Partner;
use Cronner\Import\Repository\PartnerRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class PartnerRepositoryTest extends TestCase
{
    public function testWhenPartnerIsInTheDB()
    {
        $name        = 'name';
        $countryCode = 'countryCode';
        $id          = 10;

        $statement = $this->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute', 'fetchColumn'])
            ->getMock();

        $statement->expects($this->once())
            ->method('execute')
            ->with([$name, $countryCode])
            ->willReturn($statement);

        $statement->expects($this->once())
            ->method('fetchColumn')
            ->willReturn($id);

        /** @var PDO|PHPUnit_Framework_MockObject_MockObject $pdo */
        $pdo = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare'])
            ->getMock();

        $pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT id FROM partner WHERE name=? AND country_code=? LIMIT 0, 1')
            ->willReturn($statement);

        $sut = new PartnerRepository($pdo);

        $this->assertEquals(new Partner($name, $countryCode, $id), $sut->get($name, $countryCode));
    }

    public function testWhenPartnerIsNotInTheDB()
    {
        $name        = 'name';
        $countryCode = 'countryCode';
        $id          = 10;

        $statement1 = $this->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute', 'fetchColumn'])
            ->getMock();

        $statement1->expects($this->once())
            ->method('execute')
            ->with([$name, $countryCode])
            ->willReturn(false);

        $statement1->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(false);

        $statement2 = $this->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $statement1->expects($this->once())
            ->method('execute')
            ->with([$name, $countryCode])
            ->willReturn(true);

        /** @var PDO|PHPUnit_Framework_MockObject_MockObject $pdo */
        $pdo = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'lastInsertId'])
            ->getMock();

        $pdo->expects($this->at(0))
            ->method('prepare')
            ->with('SELECT id FROM partner WHERE name=? AND country_code=? LIMIT 0, 1')
            ->willReturn($statement1);

        $pdo->expects($this->at(1))
            ->method('prepare')
            ->with('INSERT INTO partner VALUES (NULL, ?, ?)')
            ->willReturn($statement2);

        $pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn($id);

        $sut = new PartnerRepository($pdo);

        $this->assertEquals(new Partner($name, $countryCode, $id), $sut->get($name, $countryCode));
    }
}
