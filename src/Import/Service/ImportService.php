<?php

namespace Cronner\Import\Service;

use Cronner\Import\Domain\File;
use Cronner\Import\Repository\CsvRepository;
use Cronner\Import\Repository\FileRepository;
use Cronner\Import\Repository\PartnerRepository;
use Cronner\Import\Repository\ProductRepository;
use Exception;
use Psr\Log\LoggerInterface;

class ImportService
{
    /** @var LoggerInterface */
    private $logger;

    /** @var FileRepository */
    private $fileRepository;

    /** @var PartnerRepository */
    private $partnerRepository;

    /** @var CsvRepository */
    private $csvRepository;

    /** @var ProductRepository */
    private $productRepository;

    public function __construct(
        LoggerInterface $logger,
        FileRepository $fileRepository,
        PartnerRepository $partnerRepository,
        CsvRepository $csvRepository,
        ProductRepository $productRepository
    ) {
        $this->logger            = $logger;
        $this->fileRepository    = $fileRepository;
        $this->partnerRepository = $partnerRepository;
        $this->csvRepository     = $csvRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @return void
     */
    public function execute()
    {
        try {
            $start = microtime(true);
            $this->logger->info($start . ': Starting the import');

            $files = $this->fileRepository->getFiles();

            /** @var File $file */
            foreach ($files->getIterator() as $file) {
                $partner = $this->partnerRepository->get($file->getPartnerName(), $file->getPartnerCountryCode());

                $filePath = $file->getPath();

                $this->logger->info('Starting import the file: ', [$filePath]);
                $products = $this->csvRepository->getProducts($partner, $filePath);

                $this->productRepository->importProducts($partner, $products);
                $this->logger->info('Products are imported: ', [$products->count()]);
            }

            $end  = microtime(true);
            $time = $end - $start;

            $this->logger->info($end . ': The import has been finished, it took (' . $time . ' seconds)');
        } catch (Exception $exception) {
            $this->logger->error('Error has occurred during the import: ', [$exception->getMessage()]);
        }
    }
}
