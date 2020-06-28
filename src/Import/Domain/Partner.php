<?php

namespace Cronner\Import\Domain;

class Partner
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $countryCode;

    /**
     * @param string $name
     * @param string $countryCode
     * @param int    $id
     */
    public function __construct($name, $countryCode, $id = 0)
    {
        $this->id          = (int)$id;
        $this->name        = (string)$name;
        $this->countryCode = (string)$countryCode;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function setId($id)
    {
        return $this->id = (int)$id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
}
