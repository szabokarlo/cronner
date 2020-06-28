<?php

namespace Cronner\Import\Domain;

class File
{
    const FILENAME_EXTENSION_SEPARATOR   = '.';
    const PARTNER_COUNTRY_CODE_SEPARATOR = '_';
    const COUNTRY_CODE_LENGTH            = 2;

    /** @var string */
    private $path;

    /** @var string */
    private $partnerName;

    /** @var string */
    private $partnerCountryCode;

    /**
     * @param string $path
     * @param string $fileNameWithExtension
     * @param string $extension
     */
    public function __construct($path, $fileNameWithExtension, $extension)
    {
        $this->path            = (string)$path;
        $fileNameWithExtension = (string)$fileNameWithExtension;
        $extension             = (string)$extension;
        $fileName              = \substr(
            $fileNameWithExtension,
            0,
            \strlen($fileNameWithExtension) - (\strlen($extension) + \strlen(self::FILENAME_EXTENSION_SEPARATOR))
        );

        $this->partnerName = \substr(
            $fileName,
            0,
            \strlen($fileName) - self::COUNTRY_CODE_LENGTH - \strlen(self::PARTNER_COUNTRY_CODE_SEPARATOR)
        );

        $this->partnerCountryCode = \substr(
            $fileName,
            \strlen($fileName) - self::COUNTRY_CODE_LENGTH,
            \strlen($fileName)
        );
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getPartnerName()
    {
        return $this->partnerName;
    }

    /**
     * @return string
     */
    public function getPartnerCountryCode()
    {
        return $this->partnerCountryCode;
    }
}
