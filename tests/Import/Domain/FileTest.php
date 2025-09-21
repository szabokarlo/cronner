<?php

namespace Tests\Import\Domain;

use Cronner\Import\Domain\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testGetters()
    {
        $path                  = '/var/www/public/import/feed_bg.csv';
        $fileNameWithExtension = 'feed_bg.csv';
        $extension             = 'csv';

        $sut = new File($path, $fileNameWithExtension, $extension);

        $this->assertEquals($path, $sut->getPath());
        $this->assertEquals('feed', $sut->getPartnerName());
        $this->assertEquals('bg', $sut->getPartnerCountryCode());
    }

    public function testDifferentCountryCode()
    {
        $path = '/data';
        $fileNameWithExtension = 'partnername_ro.csv';
        $extension = 'csv';
        $sut = new File($path, $fileNameWithExtension, $extension);

        $this->assertEquals('partnername', $sut->getPartnerName());
        $this->assertEquals('ro', $sut->getPartnerCountryCode());
    }

    public function testPartnerNameWithUnderscore()
    {
        $path = '/data';
        $fileNameWithExtension = 'partner_name_hu.csv';
        $extension = 'csv';
        $sut = new File($path, $fileNameWithExtension, $extension);

        $this->assertEquals('partner_name', $sut->getPartnerName());
        $this->assertEquals('hu', $sut->getPartnerCountryCode());
    }
}
