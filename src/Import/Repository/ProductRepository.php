<?php

namespace Cronner\Import\Repository;

use Cronner\Import\Domain\Partner;
use Cronner\Import\Domain\Product;
use Cronner\Import\Domain\ProductCollection;
use Exception;
use PDO;
use Psr\Log\LoggerInterface;

class ProductRepository
{
    const NUMBER_OF_BATCHED_ELEMENT = 100;

    const TABLE_NAME          = 'product';

    const FIELD_PARTNER_ID    = 'partner_id';
    const FIELD_NAME          = 'name';
    const FIELD_CATEGORY      = 'category';
    const FIELD_NET_PRICE     = 'net_price';
    const FIELD_PRICE         = 'price';
    const FIELD_DELIVERY_TIME = 'delivery_time';
    const FIELD_DELIVERY_COST = 'delivery_cost';
    const FIELD_DESCRIPTION   = 'description';
    const FIELD_URL           = 'url';

    public static $fields = [
        self::FIELD_PARTNER_ID,
        self::FIELD_NAME,
        self::FIELD_CATEGORY,
        self::FIELD_NET_PRICE,
        self::FIELD_PRICE,
        self::FIELD_DELIVERY_TIME,
        self::FIELD_DELIVERY_COST,
        self::FIELD_DESCRIPTION,
        self::FIELD_URL,
    ];

    /** @var LoggerInterface */
    private $logger;

    /** @var PDO */
    private $dbConnection;

    /**
     * @param LoggerInterface $logger
     * @param PDO $dbConnection
     */
    public function __construct(LoggerInterface $logger, PDO $dbConnection)
    {
        $this->logger       = $logger;
        $this->dbConnection = $dbConnection;
    }

    /**
     * @param Partner $partner
     * @param ProductCollection $products
     *
     * @return void
     */
    public function importProducts(Partner $partner, ProductCollection $products)
    {
        try {
            $this->dbConnection->beginTransaction();

            $this->deleteProductOfPartner($partner);

            $this->insertProducts($products);

            $this->dbConnection->commit();
        } catch (Exception $exception) {
            $this->logger->error('Error has been occurred, the changes are not commited because of the following error: ', [$exception->getMessage()]);
        }
    }

    /**
     * @param Partner $partner
     *
     * @return void
     */
    private function deleteProductOfPartner(Partner $partner)
    {
        $statement = $this->dbConnection->prepare('DELETE FROM ' . self::TABLE_NAME . ' WHERE ' . self::FIELD_PARTNER_ID . '=?');
        $statement->execute([$partner->getId()]);
    }

    /**
     * @param ProductCollection $products
     *
     * @return void
     */
    private function insertProducts(ProductCollection $products)
    {
        $iterations = \ceil($products->count() / self::NUMBER_OF_BATCHED_ELEMENT);

        for ($iteration = 0; $iteration < $iterations; $iteration++) {
            $this->executeInsert(
                $products->slice(
                    $iteration * self::NUMBER_OF_BATCHED_ELEMENT,
                    self::NUMBER_OF_BATCHED_ELEMENT
                )
            );
        }
    }

    /**
     * @param ProductCollection $products
     *
     * @return void
     */
    private function executeInsert(ProductCollection $products)
    {
        $questionMarks = [];
        $insertValues  = [];

        /** @var Product $product */
        foreach ($products->getIterator() as $product) {
            $questionMarks[] = '('  . $this->placeholders('?', \count(self::$fields)) . ')';
            $insertValues = \array_merge(
                $insertValues,
                [
                    $product->getPartnerId(),
                    $product->getName(),
                    $product->getCategory(),
                    $product->getNetPrice(),
                    $product->getPrice(),
                    $product->getDeliveryTime(),
                    $product->getDeliveryCost(),
                    $product->getDescription(),
                    $product->getUrl()
                ]
            );
        }

        $sql = 'INSERT INTO ' . self::TABLE_NAME . ' (' . \implode(', ', self::$fields) . ') VALUES ' . \implode(', ', $questionMarks);

        $statement = $this->dbConnection->prepare($sql);
        $statement->execute($insertValues);
    }

    /**
     * @param string $text
     * @param int $count
     * @param string $separator
     *
     * @return string
     */
    private function placeholders($text, $count = 0, $separator = ",")
    {
        $text      = (string)$text;
        $count     = (int)$count;
        $separator = (string)$separator;

        $result = [];

        for ($step = 0; $step < $count; $step++) {
            $result[] = $text;
        }

        return \implode($separator, $result);
    }
}
