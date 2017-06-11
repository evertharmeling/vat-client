<?php

namespace Tests\VIESApi\Client;

use GuzzleHttp\Psr7\Response;
use VIESApi\Client\Client;
use VIESApi\Model\TaxableObject;
use VIESApi\Parser\VATParser;

/**
 * @author Evert Harmeling <evertharmeling@gmail.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    const VAT_NUMBER            = 'NL818918172B01';
    const VAT_NUMBER_INVALID    = 'NL818918172B02';

    public function testGetInfo()
    {
        $client = $this->createClient($this->loadMockResponse('response.get_vat_number'));
        $taxableObject = $client->getInfo(self::VAT_NUMBER);

        static::assertInstanceOf(TaxableObject::class, $taxableObject);
        static::assertEquals(self::VAT_NUMBER, sprintf('%s%s', $taxableObject->getCountryCode(), $taxableObject->getVATNumber()));
        static::assertNotNull($taxableObject->getName());
        static::assertNotNull($taxableObject->getAddress());
    }

    /**
     * @expectedException \VIESApi\Exception\TaxableObjectNotFoundException
     */
    public function testGetInvalidInfo()
    {
        $client = $this->createClient($this->loadMockResponse('response.invalid_vat_number'));
        $taxableObject = $client->getInfo(self::VAT_NUMBER_INVALID);
    }

    /**
     * @param string $name
     * @return Response
     */
    private function loadMockResponse($name)
    {
        list($dir, $name) = explode('.', $name);

        return \GuzzleHttp\Psr7\parse_response(
            file_get_contents(
                sprintf('%s/../../Mock/%s/%s', __DIR__, ucfirst($dir), $name)
            )
        );
    }

    /**
    * @param Response $mockedResponse
    *
    * @return Client
    */
    private function createClient(Response $mockedResponse)
    {
        $httpClient = new \Http\Mock\Client();
        $httpClient->addResponse($mockedResponse);

        return new Client($httpClient, new VATParser());
    }
}
