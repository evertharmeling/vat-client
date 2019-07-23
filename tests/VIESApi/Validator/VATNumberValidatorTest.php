<?php

namespace Tests\VIESApi\Validator;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use VIESApi\Client\Client;
use VIESApi\Exception\InvalidVATNumberException;
use VIESApi\Model\Country;
use VIESApi\Validator\VATNumberValidator;

use function is_array;

/**
 * @link https://www.btw-nummer-controle.nl/Userfiles/images/Format%20btw-nummers%20EU(4).pdf
 *
 * @author Evert Harmeling <evert@freshheads.com>
 */
class VATNumberValidatorTest extends TestCase
{
    private const VALID_AT_VAT_NUMBER   = 'ATU123456789';
    private const VALID_BE_VAT_NUMBER   = 'BE0123456789';
    private const VALID_BG_VAT_NUMBER   = [ 'BG123456789', 'BG1234567890' ];
    private const VALID_CY_VAT_NUMBER   = 'CY12345678A';
    private const VALID_CZ_VAT_NUMBER   = [ 'CZ12345678', 'CZ123456789', 'CZ1234567890' ];
    private const VALID_DE_VAT_NUMBER   = 'DE123456789';
    private const VALID_DK_VAT_NUMBER   = 'DK12 34 56 78';
    private const VALID_EE_VAT_NUMBER   = 'EE123456789';
    private const VALID_EL_VAT_NUMBER   = 'EL123456789';
    private const VALID_ES_VAT_NUMBER   = [ 'ES123456789', 'ESA23456789', 'ESA2345678A', 'ES12345678A' ];
    private const VALID_FI_VAT_NUMBER   = 'FI12345678';
    private const VALID_FR_VAT_NUMBER   = [ 'FR12 123456789', 'FRA1 123456789', 'FR1A 123456789', 'FRAA 123456789' ];
    private const VALID_GB_VAT_NUMBER   = [ 'GB123 4567 89', 'GB123 4567 89 012', 'GBGD123', 'GBHA123' ];
    private const VALID_HR_VAT_NUMBER   = 'HR12345678901';
    private const VALID_HU_VAT_NUMBER   = 'HU12345678';
    private const VALID_IE_VAT_NUMBER   = [ 'IE1234567A', 'IE1A34567A', 'IE1+34567A', 'IE1*34567A' ];
    private const VALID_IT_VAT_NUMBER   = 'IT12345678901';
    private const VALID_LT_VAT_NUMBER   = [ 'LT123456789', 'LT123456789012' ];
    private const VALID_LU_VAT_NUMBER   = 'LU12345678';
    private const VALID_LV_VAT_NUMBER   = 'LV12345678901';
    private const VALID_MT_VAT_NUMBER   = 'MT12345678';
    private const VALID_NL_VAT_NUMBER   = 'NL123456789B01';
    private const VALID_PL_VAT_NUMBER   = 'PL1234567890';
    private const VALID_PT_VAT_NUMBER   = 'PT123456789';
    private const VALID_RO_VAT_NUMBER   = [ 'RO12', 'RO123', 'RO1234', 'RO12345', 'RO123456', 'RO1234567', 'RO12345678', 'RO123456789', 'RO1234567890' ];
    private const VALID_SE_VAT_NUMBER   = 'SE123456789012';
    private const VALID_SL_VAT_NUMBER   = 'SL12345678';
    private const VALID_SK_VAT_NUMBER   = 'SK1234567890';

    /**
     * @dataProvider validVATNumberProvider
     */
    public function testValidVATNumbers($values): void
    {
        if (!is_array($values)) {
            $values = [ $values ];
        }

        foreach ($values as $value) {
            Assert::assertTrue($this->getValidator()->validate($value));
        }
    }

    /**
     * @dataProvider invalidVATNumberProvider
     */
    public function testInvalidVATNumbers($values): void
    {
        $this->expectException(InvalidVATNumberException::class);

        if (!is_array($values)) {
            $values = [ $values ];
        }

        foreach ($values as $value) {
            $this->getValidator()->validate($value);
        }
    }

    public function validVATNumberProvider(): array
    {
        return [
            Country::CODE_AUSTRIA           => [ self::VALID_AT_VAT_NUMBER ],
            Country::CODE_BELGIUM           => [ self::VALID_BE_VAT_NUMBER ],
            Country::CODE_BULGARY           => [ self::VALID_BG_VAT_NUMBER ],
            Country::CODE_CZECH_REPUBLIC    => [ self::VALID_CZ_VAT_NUMBER ],
            Country::CODE_CYPRUS            => [ self::VALID_CY_VAT_NUMBER ],
            Country::CODE_GERMANY           => [ self::VALID_DE_VAT_NUMBER ],
            Country::CODE_DENMARK           => [ self::VALID_DK_VAT_NUMBER ],
            Country::CODE_ESTONIA           => [ self::VALID_EE_VAT_NUMBER ],
            Country::CODE_GREECE            => [ self::VALID_EL_VAT_NUMBER ],
            Country::CODE_SPAIN             => [ self::VALID_ES_VAT_NUMBER ],
            Country::CODE_FINLAND           => [ self::VALID_FI_VAT_NUMBER ],
            Country::CODE_FRANCE            => [ self::VALID_FR_VAT_NUMBER ],
            Country::CODE_GREAT_BRITAIN     => [ self::VALID_GB_VAT_NUMBER ],
            Country::CODE_CROATIA           => [ self::VALID_HR_VAT_NUMBER ],
            Country::CODE_HUNGARY           => [ self::VALID_HU_VAT_NUMBER ],
            Country::CODE_IRELAND           => [ self::VALID_IE_VAT_NUMBER ],
            Country::CODE_ITALY             => [ self::VALID_IT_VAT_NUMBER ],
            Country::CODE_LITHUANIA         => [ self::VALID_LT_VAT_NUMBER ],
            Country::CODE_LUXEMBOURG        => [ self::VALID_LU_VAT_NUMBER ],
            Country::CODE_LATVIA            => [ self::VALID_LV_VAT_NUMBER ],
            Country::CODE_MALTA             => [ self::VALID_MT_VAT_NUMBER ],
            Country::CODE_NETHERLANDS       => [ self::VALID_NL_VAT_NUMBER ],
            Country::CODE_POLAND            => [ self::VALID_PL_VAT_NUMBER ],
            Country::CODE_PORTUGAL          => [ self::VALID_PT_VAT_NUMBER ],
            Country::CODE_ROMANIA           => [ self::VALID_RO_VAT_NUMBER ],
            Country::CODE_SWEDEN            => [ self::VALID_SE_VAT_NUMBER ],
            Country::CODE_SLOVENIA          => [ self::VALID_SL_VAT_NUMBER ],
            Country::CODE_SLOVAKIA          => [ self::VALID_SK_VAT_NUMBER ]
        ];
    }

    public function invalidVATNumberProvider(): array
    {
        return [
            Country::CODE_AUSTRIA           => [ [ 'ATU12345678', 'ATU1234567890' ] ],
            Country::CODE_BELGIUM           => [ [ 'BE012345678', 'BE01234567890' ] ],
            Country::CODE_BULGARY           => [ [ 'BG12345678', 'BG12345678901' ] ],
            Country::CODE_CYPRUS            => [ [ 'CY12345678', 'CY123456789' ] ],
            Country::CODE_CZECH_REPUBLIC    => [ [ 'CZ1234567', 'CZ12345678901' ] ],
            Country::CODE_GERMANY           => [ [ 'DE12345678', 'DE1234567890' ] ],
            Country::CODE_DENMARK           => [ [ 'DK12345678', 'DK12 34 56 78 9', 'DK12 34 56 78 90' ] ],
            Country::CODE_ESTONIA           => [ [ 'EE12345678', 'EE1234567890' ] ],
            Country::CODE_GREECE            => [ [ 'EL12345678', 'EL1234567890' ] ],
            Country::CODE_SPAIN             => [ [ 'ES12345678', 'ESA234567890' ] ],
            Country::CODE_FINLAND           => [ [ 'FI1234567', 'FI123456789' ] ],
            Country::CODE_FRANCE            => [ [ 'FR12123456789', 'FRA1 1234567890' ] ],
            Country::CODE_GREAT_BRITAIN     => [ [ 'GB123456789', 'GB123456789012', 'GBGD12', 'GBAA123', 'GBAA1234' ] ],
            Country::CODE_CROATIA           => [ [ 'HR1234567890', 'HR123456789012' ] ],
            Country::CODE_HUNGARY           => [ [ 'HU1234567', 'HU123456789' ] ],
            Country::CODE_IRELAND           => [ [ 'IE1234567', 'IE1234567AA', 'IE1_34567A', 'IE1*345678' ] ],
            Country::CODE_ITALY             => [ [ 'IT1234567890', 'IT123456789012' ] ],
            Country::CODE_LITHUANIA         => [ [ 'LT12345678', 'LT1234567890', 'LT12345678901' ] ],
            Country::CODE_LUXEMBOURG        => [ [ 'LU1234567', 'LU123456789' ] ],
            Country::CODE_LATVIA            => [ [ 'LV1234567890', 'LV123456789012' ] ],
            Country::CODE_MALTA             => [ [ 'MT1234567', 'MT123456789' ] ],
            Country::CODE_NETHERLANDS       => [ [ 'NL12345678B01', 'NL123456789B012', 'NL123456789A01', 'NL1234567890B01' ] ],
            Country::CODE_POLAND            => [ [ 'PL123456789', 'PL12345678901' ] ],
            Country::CODE_PORTUGAL          => [ [ 'PT12345678', 'PT1234567890' ] ],
            Country::CODE_ROMANIA           => [ [ 'RO1', 'RO12345678901' ] ],
            Country::CODE_SWEDEN            => [ [ 'SE12345678901', 'SE1234567890123' ] ],
            Country::CODE_SLOVENIA          => [ [ 'SL1234567', 'SL123456789' ] ],
            Country::CODE_SLOVAKIA          => [ [ 'SK123456789', 'SK12345678901' ] ],
        ];
    }

    private function getValidator(): VATNumberValidator
    {
        return new VATNumberValidator($this->createClientMock());
    }

    /**
     * @return MockObject|Client
     */
    private function createClientMock(): MockObject
    {
        return $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
