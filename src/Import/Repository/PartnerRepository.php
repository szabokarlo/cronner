<?php

namespace Cronner\Import\Repository;

use Cronner\Import\Domain\Partner;
use PDO;

class PartnerRepository
{
    const TABLE_NAME         = 'partner';

    const FIELD_ID           = 'id';
    const FIELD_NAME         = 'name';
    const FIELD_COUNTRY_CODE = 'country_code';

    /** @var PDO */
    private $dbConnection;

    /**
     * @param PDO $dbConnection
     */
    public function __construct(PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @param string $name
     * @param string $countryCode
     *
     * @return Partner
     */
    public function get($name, $countryCode)
    {
        $partner = new Partner((string)$name, (string) $countryCode);

        $statement = $this->dbConnection->prepare('SELECT ' .  self::FIELD_ID . ' FROM ' . self::TABLE_NAME . ' WHERE ' . self::FIELD_NAME . '=? AND ' . self::FIELD_COUNTRY_CODE . '=? LIMIT 0, 1');
        $statement->execute([$partner->getName(), $partner->getCountryCode()]);

        $id = $statement->fetchColumn();

        if (!$id) {
            $statement = $this->dbConnection->prepare('INSERT INTO ' . self::TABLE_NAME . ' VALUES (NULL, ?, ?)');
            $statement->execute([$partner->getName(), $partner->getCountryCode()]);

            $id = $this->dbConnection->lastInsertId();
        }

        $partner->setId($id);

        return $partner;
    }
}
