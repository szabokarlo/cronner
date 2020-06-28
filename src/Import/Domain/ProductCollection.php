<?php

namespace Cronner\Import\Domain;

use ArrayIterator;

class ProductCollection extends ArrayIterator
{
    /** @var array */
    private $products = [];

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function add(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * @param int $offset
     * @param int $length
     *
     * @return $this
     */
    public function slice($offset, $length)
    {
        $offset = (int)$offset;
        $length = (int)$length;

        $products = array_slice($this->products, $offset, $length);

        return self::createFromArray($products);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->products);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->products);
    }

    /**
     * @param array $products
     *
     * @return $this
     */
    private function createFromArray($products)
    {
        $collection = new self();

        foreach ($products as $product) {
            $collection->add($product);
        }

        return $collection;
    }
}
