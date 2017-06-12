VATClient
===================

[![Latest Stable Version](https://img.shields.io/packagist/v/evertharmeling/vat-client.svg?style=flat-square)](https://packagist.org/packages/evertharmeling/vat-client)
[![Build Status](https://travis-ci.org/evertharmeling/vat-client.png?branch=master)](https://travis-ci.org/evertharmeling/vat-client)

This libray supports validating a vat number and getting the info about a taxable object. The library uses the `http://www.controleerbtwnummer.nl/` API to retrieve the info and validation. 
The `http://www.controleerbtwnummer.nl/` API relies on the [VIES/EU](http://ec.europa.eu/taxation_customs/vies/?locale=en) service and thus supports VAT numbers from all EU-countries.

## Installation

`composer install evertharmeling/vat-client`

## Usage

It's required to use a PSR-7 supported HTTPClient like `guzzle` (^6.0) to inject in the `Client`.

```php
$client = new VIESApi\Client\Client(new GuzzleHttp\Client(), VIESApi\Parser\VATParser());

try {
    $taxableObject = $client->getInfo('<VATNumber>');
    
    var_dump($taxableObject);
catch (TaxableObjectNotFoundException $e) {
    // VAT number not found
}
```

## Roadmap

- Formatter, add formatter who according to the regexes defined in the validator, formats the VAT number
