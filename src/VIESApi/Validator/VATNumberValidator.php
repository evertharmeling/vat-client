<?php

namespace VIESApi\Validator;

use VIESApi\Client\Client;
use VIESApi\Exception\InvalidVATNumberException;
use VIESApi\Exception\VIESApiExceptionInterface;
use VIESApi\Model\Country;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
class VATNumberValidator
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $value
     * @return bool
     * @throws InvalidVATNumberException
     */
    public function validate($value)
    {
        self::checkFormat($value);

        try {
            $this->client->getInfo($value);

            return true;
        } catch (VIESApiExceptionInterface $e) { }

        return false;
    }

    /**
     * Checks if the $value is the right format according to the country specifications
     *
     * @param string $value
     * @return string
     * @throws InvalidVATNumberException
     */
    private static function checkFormat($value)
    {
        $countryCode = substr($value, 0, 2);

        if (isset(self::getFiscalNumberFormatsPerCountry()[$countryCode])) {
            $regexes = self::getFiscalNumberFormatsPerCountry()[$countryCode];

            foreach ($regexes as $regex) {
                preg_match($regex, $value, $matches);
                if (count($matches)) {
                    return true;
                }
            }

            throw new InvalidVATNumberException(sprintf("'%s' isn't a valid VATNumber according to the country (%s) specifications. It should match the regex: '%s'", $value, $countryCode, implode(', ', $regexes)));
        }

        throw new InvalidVATNumberException(sprintf("'%s' isn't a valid VATNumber according to the country (%s) specifications", $value, $countryCode));
    }

    /**
     * @link https://www.btw-nummer-controle.nl/Userfiles/images/Format%20btw-nummers%20EU(4).pdf
     *
     * @return array
     */
    private static function getFiscalNumberFormatsPerCountry()
    {
        return [
            Country::CODE_AUSTRIA => [
                self::regexify('U\d{9}')
            ],
            Country::CODE_BELGIUM => [
                self::regexify('0\d{9}')
            ],
            Country::CODE_BULGARY => [
                self::regexify('\d{9,10}')
            ],
            Country::CODE_CYPRUS => [
                self::regexify('\d{8}[A-Z]{1}')
            ],
            Country::CODE_CZECH_REPUBLIC => [
                self::regexify('\d{8,10}')
            ],
            Country::CODE_GERMANY => [
                self::regexify('\d{9}')
            ],
            Country::CODE_DENMARK => [
                self::regexify('\d{2}\s{1}\d{2}\s{1}\d{2}\s{1}\d{2}')
            ],
            Country::CODE_ESTONIA => [
                self::regexify('\d{9}')
            ],
            Country::CODE_GREECE => [
                self::regexify('\d{9}')
            ],
            Country::CODE_SPAIN => [
                self::regexify('[0-9A-Z]{1}\d{7}[0-9A-Z]{1}')
            ],
            Country::CODE_FINLAND => [
                self::regexify('\d{8}')
            ],
            Country::CODE_FRANCE => [
                self::regexify('[0-9A-Z]{2}\s{1}\d{9}')
            ],
            Country::CODE_GREAT_BRITAIN => [
                self::regexify('\d{3}\s{1}\d{4}\s{1}\d{2}'),
                self::regexify('\d{3}\s{1}\d{4}\s{1}\d{2}\s{1}\d{3}'),
                self::regexify('GD|HA\d{3}')
            ],
            Country::CODE_CROATIA => [
                self::regexify('\d{11}')
            ],
            Country::CODE_HUNGARY => [
                self::regexify('\d{8}')
            ],
            Country::CODE_IRELAND => [
                self::regexify('\d{1}[0-9A-Z+*]{1}\d{5}[A-Z]{1}')
            ],
            Country::CODE_ITALY => [
                self::regexify('\d{11}')
            ],
            Country::CODE_LITHUANIA => [
                self::regexify('\d{9}'),
                self::regexify('\d{12}')
            ],
            Country::CODE_LUXEMBOURG => [
                self::regexify('\d{8}')
            ],
            Country::CODE_LATVIA => [
                self::regexify('\d{11}')
            ],
            Country::CODE_MALTA => [
                self::regexify('\d{8}')
            ],
            Country::CODE_NETHERLANDS => [
                self::regexify('\d{9}B\d{2}')
            ],
            Country::CODE_POLAND => [
                self::regexify('\d{10}')
            ],
            Country::CODE_PORTUGAL => [
                self::regexify('\d{9}')
            ],
            Country::CODE_ROMANIA => [
                self::regexify('\d{2,10}')
            ],
            Country::CODE_SWEDEN => [
                self::regexify('\d{12}')
            ],
            Country::CODE_SLOVENIA => [
                self::regexify('\d{8}')
            ],
            Country::CODE_SLOVAKIA => [
                self::regexify('\d{10}')
            ]
        ];
    }

    /**
     * @param string $value
     * @return string
     */
    private static function regexify($value)
    {
        // always add 2-letter country code to the regex
        return sprintf('/^[A-Z]{2}%s$/', $value);
    }
}
