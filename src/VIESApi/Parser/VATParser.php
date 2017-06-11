<?php

namespace VIESApi\Parser;

use VIESApi\Model\TaxableObject;

/**
 * @author Evert Harmeling <evert@freshheads.com>
 */
class VATParser
{
    /**
     * @param \stdClass $response
     * @return TaxableObject
     */
    public static function parse(\stdClass $response)
    {
        return new TaxableObject(
            $response->vat_number,
            $response->name,
            $response->address,
            $response->country_code
        );
    }
}
