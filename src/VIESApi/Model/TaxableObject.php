<?php

namespace VIESApi\Model;

/**
 * @author Evert Harmeling <evertharmeling@gmail.com>
 */
class TaxableObject
{
    /**
     * @var string
     */
    private $VATNumber;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @param string $VATNumber
     * @param string $name
     * @param string $address
     * @param string $countryCode
     */
    public function __construct($VATNumber, $name, $address, $countryCode)
    {
        $this->VATNumber = $VATNumber;
        $this->name = $name;
        $this->address = $address;
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getVATNumber()
    {
        return $this->VATNumber;
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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
}
