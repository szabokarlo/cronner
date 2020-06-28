<?php

namespace Cronner\Import\Mapper;

use Cronner\Import\Domain\Partner;
use Cronner\Import\Domain\Product;
use Cronner\Import\Domain\ProductCollection;
use Exception;
use InvalidArgumentException;
use League\Csv\Modifier\MapIterator;

class ProductMapper
{
    const KEY_NAME          = 'Name';
    const KEY_CATEGORY      = 'Category';
    const KEY_NET_PRICE     = 'NetPrice';
    const KEY_PRICE         = 'Price';
    const KEY_DELIVERY_TIME = 'DeliveryTime';
    const KEY_DELIVERY_COST = 'DeliveryCost';
    const KEY_DESCRIPTION   = 'Description';
    const KEY_URL           = 'Url';

    public static $mandatoryKeys = [
        self::KEY_NAME,
        self::KEY_CATEGORY,
        self::KEY_PRICE,
        self::KEY_NET_PRICE,
        self::KEY_DELIVERY_TIME,
        self::KEY_DELIVERY_COST,
        self::KEY_DESCRIPTION,
        self::KEY_URL,
    ];

    /**
     * @param Partner $partner
     * @param MapIterator $products
     *
     * @return ProductCollection
     */
    public function toCollection(Partner $partner, MapIterator $products)
    {
        $productCollection = new ProductCollection();

        foreach ($products as $product) {
            try {
                $productCollection->add(
                    $this->toDomain($partner, $product)
                );
            } catch (Exception $exception) {
                //var_dump($exception->getMessage());
                continue;
            }
        }

        return $productCollection;
    }

    /**
     * @param Partner $partner
     * @param array $product
     *
     * @throws InvalidArgumentException
     *
     * @return Product
     */
    private function toDomain(Partner $partner, $product)
    {
        return new Product(
            $partner->getId(),
            $product[self::KEY_NAME],
            $product[self::KEY_CATEGORY],
            $product[self::KEY_NET_PRICE],
            $product[self::KEY_PRICE],
            $product[self::KEY_DELIVERY_TIME],
            $product[self::KEY_DELIVERY_COST],
            $product[self::KEY_DESCRIPTION],
            $product[self::KEY_URL]
        );
    }
}
