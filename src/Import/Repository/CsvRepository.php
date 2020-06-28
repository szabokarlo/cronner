<?php

namespace Cronner\Import\Repository;

use Cronner\Import\Domain\Partner;
use Cronner\Import\Domain\ProductCollection;
use Cronner\Import\Mapper\ProductMapper;
use InvalidArgumentException;
use League\Csv\Reader;
use SplFileObject;

class CsvRepository
{
    /** @var ProductMapper */
    private $productMapper;

    /**
     * @param ProductMapper $productMapper
     */
    public function __construct(ProductMapper $productMapper)
    {
        $this->productMapper = $productMapper;
    }

    /**
     * @param Partner $partner
     * @param string $filePath
     *
     * @return ProductCollection
     */
    public function getProducts(Partner $partner, $filePath)
    {
        $csvReader = $this->getCsvReader($filePath);

        return $this->productMapper->toCollection($partner, $csvReader->fetchAssoc());
    }

    /**
     * @param string $filePath
     *
     * @return Reader
     */
    public function getCsvReader($filePath)
    {
        $filePath = (string)$filePath;
        $file     = new SplFileObject($filePath);
        list($falseDelimiter, $enclosure, $escape) = $file->getCsvControl();

        $csvReader = Reader::createFromPath((string)$filePath);
        $csvReader->setDelimiter($this->getRightDelimiter($filePath));
        $csvReader->setEnclosure($enclosure);
        $csvReader->setEscape($escape);

        return $csvReader;
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    private function getRightDelimiter($filePath)
    {
        $file      = new SplFileObject((string)$filePath);
        $firstLine = $file->fgets();

        // Probably it would be avoidable using newer PHP version :-)
        $firstLine = \mb_substr($firstLine, 0, \mb_strpos($firstLine, 'Url') + mb_strlen('Url'));

        $tabCharacter   = "\t";
        $commaCharacter = ';';

        $numberOfTabs   = \mb_substr_count($firstLine, $tabCharacter);
        $numberOfCommas = \mb_substr_count($firstLine, $commaCharacter);

        if (
            ! (
                $numberOfTabs === 0
                || $numberOfCommas === 0
            )
        ) {
            throw new InvalidArgumentException('The header of the file is corrupt.');
        }

        $acceptableOccurrence = \count(ProductMapper::$mandatoryKeys) - 1;

        if ($numberOfTabs === $acceptableOccurrence) {
            return $tabCharacter;
        } elseif ($numberOfCommas === $acceptableOccurrence) {
            return $commaCharacter;
        }

        throw new InvalidArgumentException('There is some problem with the header of the file.');
    }
}
