<?php

namespace Cronner\Import\Domain;

use ArrayIterator;

class FileCollection extends ArrayIterator
{
    /** @var array */
    private $files = [];

    /**
     * @param File $file
     *
     * @return $this
     */
    public function add(File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->files);
    }
}
