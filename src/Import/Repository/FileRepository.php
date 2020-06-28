<?php

namespace Cronner\Import\Repository;

use Cronner\Import\Domain\File;
use Cronner\Import\Domain\FileCollection;
use DirectoryIterator;

class FileRepository
{
    /** @var DirectoryIterator */
    private $directoryIterator;

    /**
     * @param DirectoryIterator $directoryIterator
     */
    public function __construct(DirectoryIterator $directoryIterator)
    {
        $this->directoryIterator = $directoryIterator;
    }

    /**
     * @return FileCollection
     */
    public function getFiles()
    {
        $fileCollection = new FileCollection();

        foreach ($this->directoryIterator as $element) {
            if ($element->isFile()) {
                $fileCollection->add(
                    new File(
                        $element->getRealPath(),
                        $element->getFilename(),
                        $element->getExtension()
                    )
                );
            }
        }

        return $fileCollection;
    }
}
