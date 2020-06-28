<?php

namespace Tests\Import\Domain;

use Cronner\Import\Domain\Partner;
use PHPUnit\Framework\TestCase;

class PartnerTest extends TestCase
{
    public function testGetters()
    {
        $id          = 10;
        $name        = 'name';
        $countryCode = 'hu';

        $sut = new Partner($name, $countryCode, $id);

        $this->assertEquals($id, $sut->getId());
        $this->assertEquals($name, $sut->getName());
        $this->assertEquals($countryCode, $sut->getCountryCode());
    }

    public function testSetter()
    {
        $id          = 0;
        $name        = 'name';
        $countryCode = 'hu';

        $sut = new Partner($name, $countryCode);

        $this->assertEquals($id, $sut->getId());

        $id = 11;

        $sut->setId($id);

        $this->assertEquals($id, $sut->getId());
    }
}
