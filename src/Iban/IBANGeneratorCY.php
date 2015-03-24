<?php
/**
 * Iban Rules for Cyprus
 */


namespace Qobo\FinancialTools\Iban;

use IBAN\Generation\IBANGenerator;

class IBANGeneratorCY extends IBANGenerator
{    	
    public function __construct()
    {
        parent::__construct(CustomRuleFactory::CY());
    }
}
