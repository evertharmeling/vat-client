<?php

namespace Tests\VIESApi\Client;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client as MockClient;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use VIESApi\Client\Client;
use VIESApi\Exception\TaxableObjectNotFoundException;
use VIESApi\Model\TaxableObject;
use VIESApi\Parser\VATParser;

use function file_get_contents;
use function GuzzleHttp\Psr7\parse_response;
use function sprintf;
use function ucfirst;

/**
 * @author Evert Harmeling <evertharmeling@gmail.com>
 */
class ClientTest extends TestCase
{
    private const VAT_NUMBER            = 'NL818918172B01';
    private const VAT_NUMBER_INVALID    = 'NL818918172B99';

    public function testGetInfo(): void
    {
        $client = $this->createClient($this->loadMockResponse('response.get_vat_number'));
        $taxableObject = $client->getInfo(self::VAT_NUMBER);

        Assert::assertInstanceOf(TaxableObject::class, $taxableObject);
        Assert::assertEquals(self::VAT_NUMBER, sprintf('%s%s', $taxableObject->getCountryCode(), $taxableObject->getVATNumber()));
        Assert::assertNotNull($taxableObject->getName());
        Assert::assertNotNull($taxableObject->getAddress());
    }

    public function testGetInvalidInfo(): void
    {
        $this->expectException(TaxableObjectNotFoundException::class);

        $client = $this->createClient($this->loadMockResponse('response.invalid_vat_number'));
        $client->getInfo(self::VAT_NUMBER_INVALID);
    }

    private function loadMockResponse(string $name): Response
    {
        [$dir, $name] = explode('.', $name);

        return parse_response(
            file_get_contents(
                sprintf('%s/../../Mock/%s/%s', __DIR__, ucfirst($dir), $name)
            )
        );
    }

    private function createClient(Response $mockedResponse): Client
    {
        $httpClient = new MockClient();
        $httpClient->addResponse($mockedResponse);

        return new Client($httpClient, new VATParser());
    }
}
