<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Exceptions;

use Exception;

class VendorNotSameException extends Exception
{
    public function __construct($message = 'Vendor not same', $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
