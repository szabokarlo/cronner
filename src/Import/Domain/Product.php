<?php

namespace Cronner\Import\Domain;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Product
{
    /** @var int */
    private $partnerId;

    /** @var string */
    private $name;

    /** @var string */
    private $category;

    /** @var int */
    private $price;

    /** @var int */
    private $netPrice;

    /** @var int */
    private $deliveryTime;

    /** @var int */
    private $deliveryCost;

    /** @var string */
    private $description;

    /** @var string */
    private $url;

    /**
     * @param int $partnerId
     * @param string $name
     * @param string $category
     * @param int $netPrice
     * @param int $price
     * @param int $deliveryTime
     * @param int $deliveryCost
     * @param string $description
     * @param string $url
     */
    public function __construct(
        $partnerId,
        $name,
        $category,
        $netPrice,
        $price,
        $deliveryTime,
        $deliveryCost,
        $description,
        $url
    ) {
        $this->partnerId    = $this->getValidatedPartnerId($partnerId);
        $this->name         = $this->getValidatedName($name);
        $this->category     = $this->getValidatedCategory($category);
        $this->netPrice     = $this->getValidatedNetPrice($netPrice);
        $this->price        = $this->getValidatedPrice($price);
        $this->deliveryTime = $this->getValidatedDeliveryTime($deliveryTime);
        $this->deliveryCost = $this->getValidatedDeliveryCost($deliveryCost);
        $this->description  = \addslashes((string)$description);
        $this->url          = $this->getValidatedUrl($url);
    }

    /**
     * @return int
     */
    public function getPartnerId()
    {
        return $this->partnerId;
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
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return int
     */
    public function getNetPrice()
    {
        return $this->netPrice;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getDeliveryTime()
    {
        return $this->deliveryTime;
    }

    /**
     * @return int
     */
    public function getDeliveryCost()
    {
        return $this->deliveryCost;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $partnerId
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    private function getValidatedPartnerId($partnerId)
    {
        $partnerId = (string)$partnerId;
        Assert::integerish($partnerId, 'The partner id has to be an integer: ' . $partnerId);

        $partnerId = (int)$partnerId;
        Assert::greaterThan($partnerId, 0, 'The partner id has to be bigger than 0: ' . $partnerId);

        return $partnerId;
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    private function getValidatedName($name)
    {
        $name = (string)$name;
        Assert::regex(
            $name,
            '/^[\p{L}0-9 \.,\-\(\)\/\\\+“_&]+$/u',
            'The name contains some illegal characters: ' . $name
        );

        return $name;
    }

    /**
     * @param string $category
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    private function getValidatedCategory($category)
    {
        $category = (string)$category;
        Assert::regex(
            $category,
            '/^[\p{L}0-9 \.,-<>\/]+$/u',
            'The category contains some illegal characters: ' . $category
        );

        return $category;
    }

    /**
     * @param string $netPrice
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    private function getValidatedNetPrice($netPrice)
    {
        $netPrice = (string)$netPrice;
        Assert::integerish($netPrice, 'The net price has to be an integer: ' . $netPrice);

        $netPrice = (int)$netPrice;
        Assert::greaterThan($netPrice, 0, 'The net price has to be bigger than 0: ' . $netPrice);

        return $netPrice;
    }

    /**
     * @param string $price
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    private function getValidatedPrice($price)
    {
        $price = (string)$price;
        Assert::integerish($price, 'The price has to be an integer: ' . $price);

        $price = (int)$price;
        Assert::greaterThan($price, 0, 'The price has to be bigger than 0: ' . $price);

/*
        I didn't applied this code because there were too many wrong records and I wasn't sure about if there official agreement about the rounding method.
        $expected = round($this->netPrice * 1.27);
        Assert::eq($price, $expected, 'The price [' . $price . '] is not equal with the expected value: ' . $expected . '. The net price is: ' . $this->netPrice);
 */
        return $price;
    }

    /**
     * @param string $deliveryTime
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    private function getValidatedDeliveryTime($deliveryTime)
    {
        $deliveryTime = (string)$deliveryTime;
        Assert::integerish($deliveryTime, 'The delivery time has to be an integer: ' . $deliveryTime);

        $deliveryTime = (int)$deliveryTime;
        Assert::greaterThanEq($deliveryTime, 0, 'The delivery time can not be a negative value: ' . $deliveryTime);

        return $deliveryTime;
    }

    /**
     * @param string $deliveryCost
     *
     * @throws InvalidArgumentException
     *
     * @return int
     */
    private function getValidatedDeliveryCost($deliveryCost)
    {
        $deliveryCost = (string)$deliveryCost;

        if ($deliveryCost === 'FREE') {
            $deliveryCost = 0;
        }

        Assert::integerish($deliveryCost, 'The delivery cost has to be an integer: ' . $deliveryCost);

        $deliveryCost = (int)$deliveryCost;
        Assert::greaterThanEq($deliveryCost, 0, 'The delivery cost can not be a negative value: ' . $deliveryCost);

        return $deliveryCost;
    }

    /**
     * @param string $url
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    private function getValidatedUrl($url)
    {
        $url = (string)$url;

        if (\filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('The given string is not a valid url: ' . $url);
        }

        return $url;
    }
}
